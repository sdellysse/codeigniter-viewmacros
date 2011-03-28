<?php
class Safephptest extends CI_Controller {
  public function index() {
    $this->load->library('SafePhp');
    $this->load->view('safephptest.php');
  }
}
