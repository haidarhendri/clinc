<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notices extends OB_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Notices_m');
	}

	public function index()
	{
		exit('No direct script access allowed');
	}

	public function add()
	{
		if ($this->input->post())
		{
               $this->load->library('form_validation');
               $this->form_validation->set_rules('email_address', lang('email_address'), 'required|valid_email');
               if ($this->form_validation->run() == TRUE)
               {
                    if ($this->Notices_m->insert_email($this->input->post('email_address')))
                    {
                         $this->session->set_flashdata('success', lang('notices_add_success'));
                         redirect();
                    }
               }
          $this->session->set_flashdata('error', lang('notices_no_post_data'));
          redirect();
		}

		$this->session->set_flashdata('error', lang('notices_no_post_data'));
		redirect();
	}

     public function verify($verify_code=false)
     {
          if ( ! $verify_code )
          {
               $this->session->set_flashdata('error', lang('notices_verify_failed'));
               redirect();
          }

          if ($this->Notices_m->verify_email($verify_code))
          {
               $this->session->set_flashdata('success', lang('notices_verify_success'));
               redirect();
          }
          $this->session->set_flashdata('error', lang('notices_verify_failed'));
          redirect();
     }

     public function unsub()
     {
          if ($this->input->post())
          {
               if ($this->Notices_m->unsub($this->input->post('email_address')))
               {
                    $this->session->set_flashdata('success', lang('notify_unsub_success'));
                    redirect();

               }
               $this->session->set_flashdata('error', lang('notices_email_not_exists'));
               redirect('notices/unsub');
          }
          $this->template->build('notices/unsub');
     }
}
