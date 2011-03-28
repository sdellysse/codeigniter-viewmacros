<?php defined('BASEPATH') or exit('No direct script access allowed');
if(!class_exists('SafePhpFilter')) {
  get_instance()->load->library('AbstractStreamFilter');
  get_instance()->load->library('SafePhp');
  class SafePhpFilter extends AbstractStreamFilter {
    function filter_contents($contents) {
      return safephp_string_to_php_string($contents);
    }
  }
  stream_wrapper_register("safephp", "SafePhpFilter");
}

