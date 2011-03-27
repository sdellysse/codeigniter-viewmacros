<?php defined('BASEPATH') or exit('No direct script access allowed');

if(!class_exists('AbstractStreamFilter')) {
  abstract class AbstractStreamFilter {
    var $position;
    var $content;

    function stream_open($path, $mode, $options, &$opened_path) {
      $filename = preg_replace('#^([A-Za-z0-9-\.])+://#', '', $path);
      $unparsed_content = file_get_contents($filename);
      $this->content = $this->filter_contents($unparsed_content);
      $this->position = 0;

      return true;
    }

    function stream_read($count) {
      $ret = substr($this->content, $this->position, $count);
      $this->position += strlen($ret);
      return $ret;
    }

    function stream_write($data) {
      $left = substr($this->content, 0, $this->position);
      $right = substr($this->content, $this->position + strlen($data));
      $this->content = $left . $data . $right;
      $this->position += strlen($data);
      return strlen($data);
    }

    function stream_stat(){
      return array();
    }

    function stream_tell() {
      return $this->position;
    }

    function stream_eof() {
      return $this->position >= strlen($this->content);
    }

    function stream_seek($offset, $whence) {
      switch ($whence) {
      case SEEK_SET:
        if ($offset < strlen($this->content) && $offset >= 0) {
          $this->position = $offset;
          return true;
        } else {
          return false;
        }
        break;

      case SEEK_CUR:
        if ($offset >= 0) {
          $this->position += $offset;
          return true;
        } else {
          return false;
        }
        break;

      case SEEK_END:
        if (strlen($this->content) + $offset >= 0) {
          $this->position = strlen($this->content) + $offset;
          return true;
        } else {
          return false;
        }
        break;

      default:
        return false;
      }
    }
  }
}
