<?php defined('BASEPATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------
| Enable/Disable SafePhp
|--------------------------------------------------------------------
|
| By default, parsing of SafePhp tags is disabled. Note that if you
| have short tage rewriting turned on, that will supercede SafePhp
| parsing.
|
*/
$config['rewrite_enabled'] = TRUE;

/*
|--------------------------------------------------------------------
| Opening and closing tags for control structures
|--------------------------------------------------------------------
|
| The default opening and closing tags for control structures is
| <{ and }>. A "control structure" is a Php statement that doesn't
| output anything.
| Examples of control structures:
|
|   <{if(some_condition):}>
|     <{foreach($variables as $variable):}>
|     <{endforeach}>
|   <{endif}>
|
*/
$config['control_tag'] = array('<{', '}>');

/*
|--------------------------------------------------------------------
| Opening and closing tags for escaped output
|--------------------------------------------------------------------
|
| The default opening and closing tags for escaped output is <[ and
| ]>. The expression inside the tags is evaluated and run through
| a function to make any output html-friendly.
| Examples of escaped output:
|
|   Assuming $text = '<h1>XSS Attempt</h1>'
|   <[$text]> -> outputs &lt;h1&gt;XSS Attempt&lt;/h1&gt;
|
*/
$config['escaped_output_tag'] = array('<[', ']>');

/*
|--------------------------------------------------------------------
| Opening and closing tags for unescaped output
|--------------------------------------------------------------------
|
| The default opening and closing tags for unescaped output is <[[
| and ]]>. The fact that these tags are more characters than escaped
| output is by design; unescaped output should be the exception, not
| the rule.
| Examples of unescaped output:
|
|   Assuming $text = '<h1>XSS Attempt</h1>'
|   <[$text]> -> outputs <h1>XSS Attempt</h1>
|
*/
$config['unescaped_output_tag'] = array('<[[', ']]>');
