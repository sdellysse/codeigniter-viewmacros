<?php defined('BASEPATH') or exit('No direct script access allowed');

if(!function_exists('safephp_string_to_php_string')) {
  //put the pairs in global score so that safephp-aware plugins can
  //provide shortcuts
  $GLOBALS['safephp_pairs']['/<%==((?:(?!%>).)*)%>/'] = '<?php echo $1 ?>';
  $GLOBALS['safephp_pairs']['/<%=((?:(?!%>).)*)%>/'] = '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>';
  $GLOBALS['safephp_pairs']['/<%((?:(?!%>).)*)%>/'] = '<?php $1 ?>';
  function safephp_string_to_php_string($safephp_string) {
    return preg_replace(array_keys($GLOBALS['safephp_pairs']), array_values($GLOBALS['safephp_pairs']), $safephp_string);
  }
}

if(!class_exists('SafePhpFilter')) {
  $GLOBALS['CI']->load->helper('abstract_stream_filter');
  class SafePhpFilter extends AbstractStreamFilter {
    function filter_contents($contents) {
      return safephp_string_to_php_string($contents);
    }
  }
  stream_wrapper_register("safephp", "SafePhpFilter");
}
