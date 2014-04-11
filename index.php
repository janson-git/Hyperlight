<?php require 'hyperlight.php'; 
$hl = new \HyperLight\HyperLight();
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>‹? Hyperlight ?› Code Highlighting for PHP</title>
        <link rel="stylesheet" type="text/css" href="public/style.css"/>
        <link rel="stylesheet" type="text/css" href="public/colors/symfony2.css" id="theme"/>
    </head>

    <body>
        <div id="head">
            <div class="text">
                <h1>Code Highlighting for PHP</h1>
            </div>
        </div>
        <div id="content">
            <div id="swoosh"></div>
            <div class="text">
                <h2>Why use Hyperlight?</h2>
                <ul>
                    <li>
                    <p><strong>Easy to use.</strong> There’s no configuration. The following code will highlight your source code. Nothing more needs to be said or done.</p>
                    <?php 
                    $hl->highlight('// Create a new hyperlight instance and print the highlighted code.
$highlighter = new HyperLight();
$highlighter->highlight($code, \'iphp\');', 'iphp'); 
                    ?>
                    <p>This code creates a <code>&lt;pre&gt;</code> container around the code. This can be controlled with a third argument to the function.</p>
                    </li>
                    <li>
                    <p><strong>Easy to extend.</strong> The syntax definitions are written in PHP but only very basic language grasp is needed. Syntax definitions are concise and for most tasks, existing templates can be used and it’s enough to customize a basic set of features.</p>
                        <p>Supported languages: C++, C#, Css, PHP, Python, SQL, VB, XML</p>
                    </li>
                    <li>
                    <p><strong>Powerful.</strong> The syntax definitions use regular expressions but they support stateful parsing through a very simple mechanism. This makes implementing context free grammars effortless.</p>
                    </li>
                    <li>
                    <p><strong>Full <acronym>CSS</acronym> support.</strong> One single <acronym>CSS</acronym> file can be used for all languages to give a consistent look &amp; feel. Elements may be nested for refinements (e.g. highlighting “TODO” items in comments):</p>
                    <?php $hl->highlight(".comment { color: gray; }
.comment .todo { font-weight: bold; }", 'css'); ?>
                    <p>Further refinements are possible in order to differentiate similar elements. Consider the different classes of keywords:</p>
                    <?php $hl->highlight(".keyword { color: #008; }
.keyword.type { color: #088; }
.keyword.operator { font-weight: bold; }", 'css'); ?>
                    </li>
                    <li>
                    <p><strong>Colour schemes!</strong> – This is basically the same as “full <acronym>CSS</acronym> support” but it sounds <em>waaay</em> cooler. Since <acronym>CSS</acronym> support is naturally included in Hyperlight and syntax files can define appropriate mappings for their lexemes, usage and creation of professional colour schemes is effortless.</p>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>
<!-- vim:ft=html
-->
