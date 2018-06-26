<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends OB_AdminController {

	public function __construct()
	{
		parent::__construct();

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');
		$this->template->append_js('ie10-viewport-bug-workaround.js');

		$this->load->model('Admin_m');

		$this->load->helper('form');

		$this->load->library('form_validation');

		$this->load->language('ion_auth');

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

	}

	public function index()
	{
		if ( ! $this->ion_auth->has_permission('dashboard'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$data = $this->Admin_m->get_dashboard();

		$this->template->set('active_link', 'dashboard');

		$this->template->build('admin/index', $data);
	}

	public function settings()
	{
		if ( ! $this->ion_auth->has_permission('settings'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->set('active_link', 'settings');

		if ($this->input->post())
		{
			foreach ($this->Admin_m->get_required_settings() as $item)
			{
				$this->form_validation->set_rules($item->name, ucfirst($item->tab) . ' Tab - ' . ucwords(humanize($item->name)), 'required');
			}

			if ($this->form_validation->run() === FALSE)
            {
				$data = $this->Admin_m->get_settings_list();
                $this->template->build('admin/settings/index', $data);
            }
            if ($this->Admin_m->update_settings())
            {
            	$this->session->set_flashdata('success', lang('settings_update_success'));
            	redirect('admin/settings');
            }
            else
            {
            	$data['message'] = lang('settings_update_failed');
				$data = $this->Admin_m->get_settings_list();
				$this->template->build('admin/settings/index', $data);
            }
		}
		else
		{
			$data = $this->Admin_m->get_settings_list();

			$this->template->build('admin/settings/index', $data);
		}
	}




}
