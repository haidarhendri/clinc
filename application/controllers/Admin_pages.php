<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_pages extends OB_AdminController {


	public function __construct()
	{
		parent::__construct();

		if ( ! $this->ion_auth->has_permission('pages'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');


		$this->template->append_js('ie10-viewport-bug-workaround.js');

		$this->load->model('Admin_pages_m');

		$this->template->set('active_link', 'pages');

		$this->load->helper('form');

		$this->load->library('form_validation');

		$this->load->language('auth', $this->session->language);
		$this->load->language('ion_auth', $this->session->language);

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');


	}

	public function index()
	{
		// get all pages
		$data['pages'] = $this->Admin_pages_m->get_pages();

		$this->template->build('admin/pages/index', $data);
	}

	public function add_page()
	{
		$this->template->append_css('markdown.min.css');
		$this->template->append_js('markdown.min.js');

		if ($this->input->post())
		{

			$this->form_validation->set_rules('title', lang('page_form_title_text'), 'required');
			$this->form_validation->set_rules('status', lang('page_form_status_text'), 'required|in_list[active,inactive]');
			$this->form_validation->set_rules('content', lang('page_form_content_text'), 'required');

			$build_slug = true;

			if ($this->input->post('url_title'))
			{
				$this->form_validation->set_rules('url_title', lang('page_form_title_text'), 'required|alpha_dash|is_unique[pages.url_title]');
				$build_slug = false;
			}
		}

		if ($this->form_validation->run() == TRUE)
        {
        	$post_data = $this->input->post();

        	if ($build_slug)
        	{
        		$config = [
				    'field' => 'url_title',
				    'title' => $post_data['title'],
				    'table' => 'pages'
				];

        		$this->load->library('slug', $config);

        		$post_data['url_title'] = $this->slug->create_uri($post_data['title']);

        	}

        	$post_data['is_home'] = 0;
        	if ($this->input->post('is_home'))
        	{
        		$post_data['is_home'] = 1;
        	}

        	$post_data['author'] 	= $this->ion_auth->get_user_id();

        	$post_data['date']		= date('Y-m-d');

        	if ($this->Admin_pages_m->add_page($post_data))
        	{
        		$this->session->set_flashdata('success', lang('page_added_success_resp'));
				redirect('admin_pages');
        	}
        	$data['message'] = lang('page_added_fail_resp');
			$this->template->build('admin/pages/add_page');
        }

        $this->template->build('admin/pages/add_page');
	}


	public function edit_page($id)
	{
		$this->template->append_css('markdown.min.css');
		$this->template->append_js('markdown.min.js');

		$data['page'] = $this->Admin_pages_m->get_page($id);

		if ($this->input->post())
		{
			$new_slug = false;

			$this->form_validation->set_rules('title', lang('page_form_title_text'), 'required');
			$this->form_validation->set_rules('status', lang('page_form_status_text'), 'required|in_list[active,inactive]');
			$this->form_validation->set_rules('content', lang('page_form_content_text'), 'required');

			if ($this->input->post('url_title') != $data['page']['url_title'])
			{
				$new_slug = true;
				$this->form_validation->set_rules('url_title', lang('page_form_title_text'), 'required|alpha_dash|is_unique[pages.url_title]');
				$this->form_validation->set_rules('redirection', lang('page_form_redirect_text'), 'required|in_list[none,301,302]');
			}
		}

		if ($this->form_validation->run() == TRUE)
        {
        	$post_data = $this->input->post();

        	$redirect_val = $this->input->post('redirection');
        	unset($post_data['redirection']);

        	$post_data['is_home'] = 0;
        	if ($this->input->post('is_home'))
        	{
        		$post_data['is_home'] = 1;
        	}

        	if ($new_slug)
        	{
        		switch ($redirect_val) {
        			case 'none':
        				break;
        			case '301' || '302':
        				$this->obcore->set_redirect($data['page']['url_title'], $post_data['url_title'], 'pages', $redirect_val);
        				break;
        			default:
        				$this->obcore->set_redirect($data['page']['url_title'], $post_data['url_title'], 'pages', '301');
        				break;
        		}
        	}

        	if ($this->Admin_pages_m->update_page($id, $post_data))
        	{
        		$this->session->set_flashdata('success', lang('page_update_success_resp'));
				redirect('admin_pages');
        	}
        	$data['message'] = lang('page_update_fail_resp');
			$this->template->build('admin/pages/edit_page', $data);
        }
        $this->template->build('admin/pages/edit_page', $data);

	}

	public function remove_page($id)
	{
		if ($this->Admin_pages_m->remove_page($id))
		{
			$this->session->set_flashdata('success', lang('page_removed_success_resp'));
			redirect('admin_pages');
		}
		$this->session->set_flashdata('error', lang('page_removed_fail_resp'));
		redirect('admin_pages');
	}

}
