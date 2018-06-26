<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_social extends OB_AdminController {

	public function __construct()
	{
		parent::__construct();

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');
		$this->template->append_js('ie10-viewport-bug-workaround.js');

		$this->load->model('Admin_social_m');

		$this->load->helper('form');

		$this->load->library('form_validation');

		$this->load->language('ion_auth');

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

		if ( ! $this->ion_auth->has_permission('social'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->set('active_link', 'social');
	}

	public function index()
	{
		$data['social'] = $this->Admin_social_m->get_social_links();
		$this->template->build('admin/social/index', $data);
	}

	public function add()
	{
		if ($this->input->post())
		{
			$this->form_validation->set_rules('name', lang('social_form_name'), 'required');
			$this->form_validation->set_rules('url', lang('social_form_url'), 'required');
			$this->form_validation->set_rules('enabled', lang('social_form_active'), 'required');
		}

		if ($this->form_validation->run() == TRUE)
        {
        	if ($this->Admin_social_m->add_social($this->input->post()))
        	{
        		$this->session->set_flashdata('success', lang('social_added_success_resp'));
				redirect('admin_social');
        	}
        	$data['message'] = lang('social_added_fail_resp');
			$this->template->build('admin/social/add');
        }
		$this->template->build('admin/social/add');
	}

	public function edit($id)
	{
		$data['social'] = $this->Admin_social_m->get_social_link($id);

		if ($this->input->post())
		{
			$this->form_validation->set_rules('name', lang('social_form_name'), 'required');
			$this->form_validation->set_rules('url', lang('social_form_url'), 'required');
			$this->form_validation->set_rules('enabled', lang('social_form_active'), 'required');
		}

		if ($this->form_validation->run() == TRUE)
        {
        	if ($this->Admin_social_m->update_social($id, $this->input->post()))
        	{
        		$this->session->set_flashdata('success', lang('social_update_success_resp'));
				redirect('admin_social');
        	}
        	$data['message'] = lang('social_update_fail_resp');
			$this->template->build('admin/social/edit', $data);
        }
        $this->template->build('admin/social/edit', $data);
	}

	public function remove($id)
	{
		if ($this->Admin_social_m->remove($id))
		{
			$this->session->set_flashdata('success', lang('social_removed_success_resp'));
			redirect('admin_social');
		}
		$this->session->set_flashdata('error', lang('social_removed_fail_resp'));
		redirect('admin_social');
	}
}
