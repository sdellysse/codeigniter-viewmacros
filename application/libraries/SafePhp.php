<?php defined('BASEPATH') or exit('No direct script access allowed');

if(!class_exists('SafePhp')) {
  class SafePhp {
    public function __construct() {
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
      $regex = strtr($regex, array(
        'OPENTAG'   => preg_quote($this->_tag[$type][0], '/'),
        'CLOSETAG'  => preg_quote($this->_tag[$type][1], '/'),
      ));
      $this->pairs[$regex] = $replacement;
    }

    public function regexes() {
      return array_keys($this->pairs);
    }

    public function replacement() {
      return array_values($this->pairs);
    }

    public function to_php($safephp_string) {
      return preg_replace($this->regexes(), $this->replacements(), $safephp_string);
    }
  }
}

