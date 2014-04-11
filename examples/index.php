<?php require_once '../hyperlight.php'; ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>‹? Hyperlight ?› Examples</title>
        <link rel="stylesheet" type="text/css" href="../style.css"/>
        <script type="text/javascript" src="../jquery-1.2.6.min.js"></script>
        <script type="text/javascript" src="theme_switcher.js"></script>
        <link rel="stylesheet" type="text/css" href="../colors/zenburn.css" id="theme"/>
    </head>

    <body>
        <div id="head">
            <div class="text">
                <h1>Examples</h1>
            </div>
        </div>
        <div id="content">
            <div id="swoosh"></div>
            <div class="text">
                <ul id="switch-buttons">
                    <li><a href="" class="active" id="theme-zenburn">Zenburn</a></li>
                    <li><a href="" id="theme-vibrant-ink">Vibrant Ink</a></li>
                    <li><a href="" id="symfony2">Symfony2</a></li>
                </ul>

                <?php hyperlight(
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


hyperlight('CREATE TABLE stat."group"
(
  id bigserial NOT NULL,
  flag_valid boolean NOT NULL DEFAULT true,
  group_id bigint NOT NULL,
  "date" date NOT NULL,
  member_list bigint[] NOT NULL, -- comment here
  CONSTRAINT group_pkey PRIMARY KEY (id)
  USING INDEX TABLESPACE pg_default,
  CONSTRAINT group_group_id_date_key UNIQUE (group_id, date)
  USING INDEX TABLESPACE pg_default
  /* some comment here */
)', 'sql');


hyperlight('
#include<iostream.h>
#include<conio.h>
void main()                         //Start of main
{
    clrscr();
    int i=1, u=1, sum=0;
    while(i<=500) {                 // start of first loop.
        while(u<=500) {             //start of second loop.
            if(u<i) {
                if(i%u==0 ) {
                    sum=sum+u;
                }
            }                       //End of if statement
            u++;
        }                           //End of second loop
    
        if(sum==i) {
            cout<<i<<" is a perfect number."<<"\n";
        }
    
        i++;
        u=1;  sum=0;
    }                               //End of First loop
    getch();
}                                   //End of main

', 'cpp');

 ?>
            </div>
        </div>
    </body>
</html>
<!-- vim:ft=html
-->
