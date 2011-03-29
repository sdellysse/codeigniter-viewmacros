<?php defined('BASEPATH') or exit('No direct script access allowed');

if(!class_exists('SafePhp')) {
  class SafePhp {
    public function __construct($config = array()) {
      $this->set_config('rewrite_enabled', FALSE);
      $this->set_config('control_tag', array('<{','}>'));
      $this->set_config('escaped_output_tag', array('<[',']>'));
      $this->set_config('unescaped_output_tag', array('<[[', ']]>'));

      foreach($config as $k => $v) {
        $this->set_config($k, $v);
      }
      log_message('debug', 'SafePhp class initialized');

      $this->patterns = array();
      $this->replacements = array();

      $this->add(
        'escaped_output',
        '/OPENTAG((?:(?!CLOSETAG).)*)CLOSETAG/',
        '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>'
      );
      $this->add(
        'unescaped_output',
        '/OPENTAG((?:(?!CLOSETAG).)*)CLOSETAG/',
        '<?php echo $1 ?>'
      );
      $this->add(
        'control',
        '/OPENTAG((?:(?!CLOSETAG).)*)CLOSETAG/',
        '<?php $1 ?>'
      );
    }

    public function add($type, $regex, $replacement) {
      $tag = $this->get_config("{$type}_tag");
      $regex = strtr($regex, array(
        'OPENTAG'   => preg_quote($tag[0], '/'),
        'CLOSETAG'  => preg_quote($tag[1], '/'),
      ));
      array_unshift($this->patterns, $regex);
      array_unshift($this->replacements, $replacement);
    }

    public function get_config($key) {
      if(strpos($key, '_') !== 0) {
        return $this->get_config('_' . $key);
      }
      return $this->$key;
    }

    public function set_config($key, $value) {
      if(strpos($key, '_') !== 0) {
        return $this->set_config('_' . $key, $value);
      }
      return $this->$key = $value;
    }

    public function to_php($safephp_string) {
      $retval = preg_replace($this->patterns, $this->replacements, $safephp_string);
      log_message('debug', print_r($this->patterns, true) . print_r($this->replacements, true) . $retval);
      return $retval;
    }
  }
}

if(!class_exists('SafePhpFilter')) {
  get_instance()->load->helper('abstract_stream_filter');
  class SafePhpFilter extends AbstractStreamFilter {
    function filter_contents($contents) {
      return get_instance()->safephp->to_php($contents);
    }
  }
  stream_wrapper_register("safephp", "SafePhpFilter");
}
