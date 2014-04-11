<?php

namespace HyperLight;


class HyperLight {
    /** @var  HyperLightCompiledLanguage */
    private $_lang;
    private $_result;
    private $_states;
    private $_omitSpans;
    private $_postProcessors = array();
    private $_code;

    public function __construct($lang = 'iphp')
    {
        $this->setLanguage($lang);
        foreach ($this->_lang->postProcessors() as $ppkey => $ppvalue)
            $this->_postProcessors[$ppkey] = new HyperLight($ppvalue);

        $this->reset();
    }

    protected function setLanguage($lang)
    {
        if (is_string($lang))
            $this->_lang = HyperLanguage::compileFromName(strtolower($lang));
        else if ($lang instanceof HyperLightCompiledLanguage)
            $this->_lang = $lang;
        else if ($lang instanceof HyperLanguage)
            $this->_lang = HyperLanguage::compile($lang);
        else
            trigger_error(
                'Invalid argument type for $lang to HyperLight::__construct',
                E_USER_ERROR
            );
    }
    
    public function language() {
        return $this->_lang;
    }

    public function reset() {
        $this->_states = array('init');
        $this->_omitSpans = array();
    }

    public function render($code) {
        // Normalize line breaks.
        $this->_code = preg_replace('/\r\n?/', "\n", $code);
        $fm = HyperLightHelpers::calculateFoldMarks($this->_code, $this->language()->id());
        return HyperLightHelpers::applyFoldMarks($this->renderCode(), $fm);
    }

    protected function renderAndPrint($code) {
        echo $this->render($code);
    }


    private function renderCode() {
        $code = $this->_code;
        $pos = 0;
        $len = strlen($code);
        $this->_result = '';
        $state = array_peek($this->_states);

        // If there are open states (reentrant parsing), open the corresponding
        // tags first:

        for ($i = 1; $i < count($this->_states); ++$i)
            if (!$this->_omitSpans[$i - 1]) {
                $class = $this->_lang->className($this->_states[$i]);
                $this->write("<span class=\"$class\">");
            }

        // Emergency break to catch faulty rules.
        $prev_pos = -1;

        while ($pos < $len) {
            // The token next to the current position, after the inner loop completes.
            // i.e. $closest_hit = array($matched_text, $position)
            $closest_hit = array('', $len);
            // The rule that found this token.
            $closest_rule = null;
            $rules = $this->_lang->rule($state);

            foreach ($rules as $name => $rule) {
                if ($rule instanceof Rule)
                    $this->matchIfCloser(
                        $rule->start(), $name, $pos, $closest_hit, $closest_rule
                    );
                else if (preg_match($rule, $code, $matches, PREG_OFFSET_CAPTURE, $pos) == 1) {
                    // Search which of the sub-patterns matched.

                    foreach ($matches as $group => $match) {
                        if (!is_string($group))
                            continue;
                        if ($match[1] !== -1) {
                            $closest_hit = $match;
                            $closest_rule = str_replace('_', ' ', $group);
                            break;
                        }
                    }
                }
            } // foreach ($rules)

            // If we're currently inside a rule, check whether we've come to the
            // end of it, or the end of any other rule we're nested in.

            if (count($this->_states) > 1) {
                $n = count($this->_states) - 1;
                do {
                    /** @var Rule $rule */
                    $rule = $this->_lang->rule($this->_states[$n - 1]);
                    $rule = $rule[$this->_states[$n]];
                    --$n;
                    if ($n < 0)
                        throw new NoMatchingRuleException($this->_states, $pos, $code);
                } while ($rule->end() === null);

                $this->matchIfCloser($rule->end(), $n + 1, $pos, $closest_hit, $closest_rule);
            }

            // We take the closest hit:

            if ($closest_hit[1] > $pos)
                $this->emit(substr($code, $pos, $closest_hit[1] - $pos));

            $prev_pos = $pos;
            $pos = $closest_hit[1] + strlen($closest_hit[0]);

            if ($prev_pos === $pos and is_string($closest_rule))
                if (array_key_exists($closest_rule, $this->_lang->rule($state))) {
                    array_push($this->_states, $closest_rule);
                    $state = $closest_rule;
                    $this->emitPartial('', $closest_rule);
                }

            if ($closest_hit[1] === $len)
                break;
            else if (!is_string($closest_rule)) {
                // Pop state.
                if (count($this->_states) <= $closest_rule)
                    throw new NoMatchingRuleException($this->_states, $pos, $code);

                while (count($this->_states) > $closest_rule + 1) {
                    $lastState = array_pop($this->_states);
                    $this->emitPop('', $lastState);
                }
                $lastState = array_pop($this->_states);
                $state = array_peek($this->_states);
                $this->emitPop($closest_hit[0], $lastState);
            }
            else if (array_key_exists($closest_rule, $this->_lang->rule($state))) {
                // Push state.
                array_push($this->_states, $closest_rule);
                $state = $closest_rule;
                $this->emitPartial($closest_hit[0], $closest_rule);
            }
            else
                $this->emit($closest_hit[0], $closest_rule);
        } // while ($pos < $len)

        // Close any tags that are still open (can happen in incomplete code
        // fragments that don't necessarily signify an error (consider PHP
        // embedded in HTML, or a C++ preprocessor code not ending on newline).

        $omitSpansBackup = $this->_omitSpans;
        for ($i = count($this->_states); $i > 1; --$i)
            $this->emitPop();
        $this->_omitSpans = $omitSpansBackup;

        return $this->_result;
    }

    private function matchIfCloser($expr, $next, $pos, &$closest_hit, &$closest_rule) {
        $matches = array();
        if (preg_match($expr, $this->_code, $matches, PREG_OFFSET_CAPTURE, $pos) == 1) {
            if (
                (
                    // Two hits at same position -- compare length
                    // For equal lengths: first come, first serve.
                    $matches[0][1] == $closest_hit[1] and
                    strlen($matches[0][0]) > strlen($closest_hit[0])
                ) or
                $matches[0][1] < $closest_hit[1]
            ) {
                $closest_hit = $matches[0];
                $closest_rule = $next;
            }
        }
    }

    private function processToken($token) {
        if ($token === '')
            return '';
        $nest_lang = array_peek($this->_states);
        if (array_key_exists($nest_lang, $this->_postProcessors))
            return $this->_postProcessors[$nest_lang]->render($token);
        else
            //return self::htmlentities($token);
            return htmlspecialchars($token, ENT_NOQUOTES);
    }

    private function emit($token, $class = '') {
        $token = $this->processToken($token);
        if ($token === '')
            return;
        $class = $this->_lang->className($class);
        if ($class === '')
            $this->write($token);
        else
            $this->write("<span class=\"$class\">$token</span>");
    }

    private function emitPartial($token, $class) {
        $token = $this->processToken($token);
        $class = $this->_lang->className($class);
        if ($class === '') {
            if ($token !== '')
                $this->write($token);
            array_push($this->_omitSpans, true);
        }
        else {
            $this->write("<span class=\"$class\">$token");
            array_push($this->_omitSpans, false);
        }
    }

    private function emitPop($token = '', $class = '') {
        $token = $this->processToken($token);
        if (array_pop($this->_omitSpans))
            $this->write($token);
        else
            $this->write("$token</span>");
    }

    private function write($text) {
        $this->_result .= $text;
    }


    /**
     * <var>echo</var>s a highlighted code.
     *
     * For example, the following
     * <code>
     * $hl = new HyperLight();
     * $hl->highlight('<?php echo \'Hello, world\'; ?>', 'php');
     * </code>
     * results in:
     * <code>
     * <pre class="source-code php">...</pre>
     * </code>
     *
     * @param string $code The code.
     * @param string $lang The language of the code.
     * @param string $tag The surrounding tag to use. Optional.
     * @param array $attributes Attributes to decorate {@link $tag} with.
     *          If no tag is given, this argument can be passed in its place. This
     *          behaviour will be assumed if the third argument is an array.
     *          Attributes must be given as a hash of key value pairs.
     */
    public function highlight($code, $lang, $tag = 'pre', array $attributes = array()) {
        $this->reset();
        
        if ($code == '')
            die("`hyperlight` needs a code to work on!");
        if ($lang == '')
            die("`hyperlight` needs to know the code's language!");
        if (is_array($tag) and !empty($attributes))
            die("Can't pass array arguments for \$tag *and* \$attributes to `hyperlight`!");
        if ($tag == '')
            $tag = 'pre';
        if (is_array($tag)) {
            $attributes = $tag;
            $tag = 'pre';
        }
        $lang = htmlspecialchars(strtolower($lang));
        $class = "source-code $lang";

        $attr = array();
        foreach ($attributes as $key => $value) {
            if ($key == 'class')
                $class .= ' ' . htmlspecialchars($value);
            else
                $attr[] = htmlspecialchars($key) . '="' .
                    htmlspecialchars($value) . '"';
        }

        $attr = empty($attr) ? '' : ' ' . implode(' ', $attr);

        $this->setLanguage($lang);
        echo "<$tag class=\"$class\"$attr>";
        $this->renderAndPrint(trim($code) . "\n"); // add line feed to end of code
        echo "</$tag>";
    }

    /**
     * Is the same as:
     * <code>
     * $hl = new HyperLight();
     * $hl->highlight(file_get_contents($filename), $lang, $tag, $attributes);
     * </code>
     * @see hyperlight()
     */
    public function highlightFile($filename, $lang = null, $tag = 'pre', array $attributes = array()) {
        $this->reset();
        
        if ($lang == '') {
            // Try to guess it from file extension.
            $pos = strrpos($filename, '.');
            if ($pos !== false) {
                $ext = substr($filename, $pos + 1);
                $lang = \HyperLight\HyperLanguage::nameFromExt($ext);
            }
        }
        $this->highlight(file_get_contents($filename), $lang, $tag, $attributes);
    }

} // class HyperLight