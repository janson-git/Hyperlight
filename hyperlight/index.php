<?php

require 'hyperlight.php';

?><!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Hyperlight – Code Highlighting for PHP</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
    </head>

    <body>
        <div id="head">
            <div class="text">
                <h1>Hyperlight –<br/>Code Highlighting for PHP</h1>
            </div>
        </div>
        <div id="content">
            <div class="text">
                <h2>Why use Hyperlight?</h2>
                <ul>
                    <li>
                    <p><strong>Easy to use.</strong> There’s no configuration. The following code will highlight your source code. Nothing more needs to be said or done.</p>
                    <pre class="code"><span class="comment">// Create a new hyperlight instance and print the highlighted code.</span>
<span class="varname">$<span class="identifier">highlighter</span></span> = <span class="keyword operator">new</span> <span class="identifier">HyperLight</span>(<span class="string">'cpp'</span>, <span class="varname">$<span class="identifier">code</span></span>);
<span class="varname">$<span class="identifier">highlighter</span>-&gt;<span class="identifier">theResult</span>();</pre>
                    </li>
                    <p><strong>Easy to extend.</strong> The syntax definitions are written in PHP but only very basic language grasp is needed. Syntax definitions are concise and for most tasks, existing templates can be used and it’s enough to customize a basic set of features.</p>
                    </li>
                    <li>
                    <p><strong>Powerful.</strong> The syntax definitions use regular expressions but they support stateful parsing through a very simple mechanism. This makes implementing conext free grammars effortless.</p>
                    </li>
                    <li>
                    <p><strong>Full CSS support.</strong> One single CSS file can be used for all languages to give a consistent look &amp; feel. Elements may be nested for refinements (e.g. highlighting “TODO” items in comments):</p>
                    <pre class="code"><?php hyperlight(".comment { color: gray; }
.comment .todo { font-weight: bold; }", 'css'); ?></pre>
                    <p>Further refinements are possible in order to differentiate similar elements. Consider the different classes of keywords:</p>
                    <pre class="code"><?php hyperlight(".keyword { color: #008; }
.keyword.type { color: #088; }
.keyword.operator { font-weight: bold; }", 'css'); ?></pre>
                    </li>
                </ul>

                <h2>Why not use something else?</h2>
                <p>Sure, there are alternatives. Unfortunately, they are surprisingly few for PHP:

                <h3>Geshi</h3>
                <p>If you’re forced to work with PHP version &lt; 5.0, sure, use Geshi. But be prepared that each syntax brings its own (ugly) style, lacking conventions make the use of one CSS for all languages impossible (because they use the same CSS class names for completely different things), a lot of badly-documented configuration is necessary to get the desired result, HTML garbage is produced and the CSS class names are gibberish.</p>
                <p>Furthermore, many of the syntax definitions are badly realized and/or have bugs. Creating an own highlighting isn't trivial because the API is quite complicated, not very powerful and lacks documentation.</p>
                <p>If that doesn't matter to you, Geshi is perhaps not such a bad choice.</p>

                <h3>Pear_TextHighlighter</h3>
                <p>Syntax definitions must be given as cumbersome XML files. Need I say more?</p>
            </div>
        </div>
    </body>
</html>