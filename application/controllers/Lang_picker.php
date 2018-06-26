<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lang_picker extends OB_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		exit('No direct script access allowed');
	}

     public function set($lang)
     {
          $lang = $this->db->where('language', $lang)->limit(1)->get('languages')->row();

          if ($lang && $lang->is_avail == 1)
          {
               $this->session->set_userdata('language', $lang->language);
               $this->session->set_userdata('language_abbr', $lang->abbreviation);

               $this->session->set_flashdata('success', lang('language_changed_successfully'));
               redirect($this->agent->referrer());
          }
          else
          {
               $this->session->set_flashdata('error', lang('language_not_available'));
               redirect($this->agent->referrer());
          }
     }
}
