<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_lang extends OB_AdminController {

	public function __construct()
	{
		parent::__construct();

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');
		$this->template->append_js('ie10-viewport-bug-workaround.js');

		$this->load->model('Admin_lang_m');

		$this->load->helper('form');

		$this->load->library('form_validation');

		$this->load->language('ion_auth', $this->session->language);

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

		if ( ! $this->ion_auth->has_permission('lang'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->set('active_link', 'lang');
	}

	public function index()
	{
		$data['langs'] = $this->Admin_lang_m->get_links();

		$this->template->build('admin/lang/index', $data);
	}

	public function disable($id)
	{
		if ($this->Admin_lang_m->toggle_is_avail($id, '0'))
		{
			$this->session->set_flashdata('success', lang('languages_disable_success_resp'));
			redirect('admin_lang');
		}
		$this->session->set_flashdata('error', lang('languages_disable_fail_resp'));
		redirect('admin_lang');
	}

	public function enable($id)
	{
		if ($this->Admin_lang_m->toggle_is_avail($id, '1'))
		{
			//it worked
			$this->session->set_flashdata('success', lang('languages_enable_success_resp'));
			redirect('admin_lang');
		}
		// failed to remove
		$this->session->set_flashdata('error', lang('languages_enable_fail_resp'));
		redirect('admin_lang');
	}

	public function make_default($id)
	{
		if ($this->Admin_lang_m->make_default($id))
		{
			$lang = $this->Admin_lang_m->get_language($id);

			$this->session->set_userdata('language', $lang->language);
            $this->session->set_userdata('language_abbr', $lang->abbreviation);

			$this->session->set_flashdata('success', lang('languages_default_success_resp'));
			redirect('admin_lang');
		}
		$this->session->set_flashdata('error', lang('languages_default_fail_resp'));
		redirect('admin_lang');
	}


}
