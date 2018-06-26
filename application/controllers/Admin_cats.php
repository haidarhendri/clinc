<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_cats extends OB_AdminController
{
	public function __construct()
	{
		parent::__construct();

		if ( ! $this->ion_auth->has_permission('posts'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');
		$this->template->append_js('ie10-viewport-bug-workaround.js');
		$this->template->set('active_link', 'cats');

		$this->load->model('Admin_cats_m');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->language('auth', $this->session->language);
		$this->load->language('ion_auth', $this->session->language);

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
	}

	public function index()
	{
		$data['cats'] = $this->Admin_cats_m->get_cats();

		$this->template->build('admin/cats/index', $data);
	}

	public function add_cat()
	{
		if ($this->input->post())
		{
			$this->form_validation->set_rules('name', lang('cat_form_name'), 'required');
			$this->form_validation->set_rules('url_name', lang('cat_form_url'), 'required');
			$this->form_validation->set_rules('description', lang('cat_form_desc'), 'required');
		}

		if ($this->form_validation->run() == TRUE)
        {
        	if ($this->Admin_cats_m->add_cat($this->input->post()))
        	{
        		$this->session->set_flashdata('success', lang('cat_added_success_resp'));
				redirect('admin_cats');
        	}
        	$data['message'] = lang('cat_added_fail_resp');
			$this->template->build('admin/cats/add_cat');
        }
        $this->template->build('admin/cats/add_cat');
	}

	public function edit_cat($id)
	{
		$data['cat'] = $this->Admin_cats_m->get_cat($id);

		if ($this->input->post())
		{
			$this->form_validation->set_rules('name', lang('cat_form_name'), 'required');
			$this->form_validation->set_rules('url_name', lang('cat_form_url'), 'required');
			$this->form_validation->set_rules('description', lang('cat_form_desc'), 'required');
		}

		if ($this->form_validation->run() == TRUE)
        {
        	if ($this->Admin_cats_m->update_cat($id, $this->input->post()))
        	{
        		$this->session->set_flashdata('success', lang('cat_update_success_resp'));
				redirect('admin_cats');
        	}
        	$data['message'] = lang('cat_update_fail_resp');
			$this->template->build('admin/cats/edit_cat', $data);
        }
        $this->template->build('admin/cats/edit_cat', $data);

	}
	public function remove_cat($id)
	{
		if ($this->Admin_cats_m->remove_cat($id))
		{
			$this->session->set_flashdata('success', lang('cat_removed_success_resp'));
			redirect('admin_cats');
		}
		$this->session->set_flashdata('error', lang('cat_removed_fail_resp'));
		redirect('admin_cats');
	}
}
