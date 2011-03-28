<?php defined('BASEPATH') or exit('No direct script access allowed');
if(!class_exists('SafePhpFilter')) {
  get_instance()->load->helper('abstract_stream_filter');
  get_instance()->load->library('SafePhp');
  class SafePhpFilter extends AbstractStreamFilter {
    function filter_contents($contents) {
      return get_instance()->safephp->to_php($contents);
    }
  }
  stream_wrapper_register("safephp", "SafePhpFilter");
}

