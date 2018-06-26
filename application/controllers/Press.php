<?php
class Press extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
      $this->load->view('Press');
    }
}
?>
