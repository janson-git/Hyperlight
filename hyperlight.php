<?php

require_once 'autoloader.php';

/*
 * Copyright 2008 Konrad Rudolph
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/*
 * TODO list
 * =========
 *
 * - FIXME Nested syntax elements create redundant nested tags under certain
 *   circumstances. This can be reproduced by the following PHP snippet:
 *
 *      <pre class="<?php echo; ? >">
 *
 *   (Remove space between `?` and `>`).
 *   Although this no longer occurs, it is fixed by checking for `$token === ''`
 *   in the `emit*` methods. This should never happen anyway. Probably something
 *   to do with the zero-width lookahead in the PHP syntax definition.
 *
 * - `hyperlight_calculate_fold_marks`: refactor, write proper handler
 *
 * - Line numbers (on client-side?)
 *
 */

/**
 * HyperLight source code highlighter for PHP.
 * @package hyperlight
 */

/** @ignore */
require_once('preg_helper.php');

if (!function_exists('array_peek')) {
    /**
     * @internal
     * This does exactly what you think it does. */
    function array_peek(array &$array) {
        $cnt = count($array);
        return $cnt === 0 ? null : $array[$cnt - 1];
    }
}

/**
 * @internal
 * For internal debugging purposes.
 */
function dump($obj, $descr = null) {
    if ($descr !== null)
        echo "<h3>$descr</h3>";
    ob_start();
    var_dump($obj);
    $dump = ob_get_clean();
    ?><pre><?php echo htmlspecialchars($dump); ?></pre><?php
    return true;
}




if (defined('HYPERLIGHT_SHORTCUT')) {
    function hy() {
        $args = func_get_args();
        call_user_func_array('hyperlight', $args);
    }
    function hyf() {
        $args = func_get_args();
        call_user_func_array('hyperlight_file', $args);
    }
}

