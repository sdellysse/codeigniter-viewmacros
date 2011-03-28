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

      $this->pairs = array();
      $this->add(
        'unescaped_output',
        '/OPENTAG((?:(?!CLOSETAG).)*)CLOSETAG/',
        '<?php echo $1 ?>'
      );
      $this->add(
        'escaped_output',
        '/OPENTAG((?:(?!CLOSETAG).)*)CLOSETAG/',
        '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>'
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
      $this->pairs[$regex] = $replacement;
    }

    public function get_config($key) {
      if(strpos($key, '_') !== 0) {
        return $this->get_config('_' . $key);
      }
      return $this->$key;
    }

    public function regexes() {
      return array_keys($this->pairs);
    }

    public function replacements() {
      return array_values($this->pairs);
    }

    public function set_config($key, $value) {
      if(strpos($key, '_') !== 0) {
        return $this->set_config('_' . $key, $value);
      }
      return $this->$key = $value;
    }

    public function to_php($safephp_string) {
      return preg_replace($this->regexes(), $this->replacements(), $safephp_string);
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
