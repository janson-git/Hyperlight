Hyperlight
==========

Fork of Hyperlight. Add SQL language. Add new color schemes.


**Usage**

```php

require_once '../hyperlight.php';
$hyperlight = new \HyperLight\HyperLight();

// use as: $hyperlight->highlight($code, $lang);
// $lang - can by 'php', 'iphp', 'sql', 'cpp', 'vb', 'css', 'python', 'xml'
// 'iphp' - use for code blocks of pure PHP (without start tag '<?php')
// 'php' - use for mix of PHP code and HTML (or PHP with start tag '<?php')

$hyperlight->highlight(
'function preg_strip($expression) {
$regex = \'/^(.)(.*)\\\\1([imsxeADSUXJu]*)$/s\';
if (preg_match($regex, $expression, $matches) !== 1)
return false;

$delim = $matches[1];
$sub_expr = $matches[2];
if ($delim !== \'/\') {
// Replace occurrences by the escaped delimiter by its unescaped
// version and escape new delimiter.
$sub_expr = str_replace("\\\\$delim", $delim, $sub_expr);
$sub_expr = str_replace(\'/\', \'\\\\/\', $sub_expr);
}
$modifiers = $matches[3] === \'\' ?
array() : str_split(trim($matches[3]));

return array($sub_expr, $modifiers);
}
', 'iphp');


```
