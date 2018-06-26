<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Comments
 *
 * Admin Comments Controller Class
 *
 * @access  public
 * @author  Enliven Appications
 * @version 3.0
 *
*/
class Admin_comments extends OB_AdminController
{
	public function __construct()
	{
		parent::__construct();

		if ( ! $this->ion_auth->has_permission('comments'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');
		$this->template->append_js('ie10-viewport-bug-workaround.js');
		$this->template->set('active_link', 'comments');

		$this->load->model('Admin_comments_m');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->language('auth', $this->session->language);
		$this->load->language('ion_auth', $this->session->language);

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');


	}

	public function index()
	{
		$data['active_comments'] = $this->Admin_comments_m->get_comments();

		$data['modded_comments'] = $this->Admin_comments_m->get_comments(1);

		$this->template->build('admin/comments/index', $data);
	}

	public function edit_comment($id)
	{
		$data['comment'] = $this->Admin_comments_m->get_comment($id);

		if ($this->input->post())
		{
			$this->form_validation->set_rules('content', lang('comment_form_field_content'), 'required');
		}

		if ($this->form_validation->run() == TRUE)
        {
        	if ($this->Admin_comments_m->update_comment($id, $this->input->post()))
        	{
        		$this->session->set_flashdata('success', lang('comment_update_success_resp'));
				redirect('admin_comments');
        	}
        	$data['message'] = lang('comment_update_fail_resp');
			$this->template->build('admin/comments/edit_comment', $data);
        }
        $this->template->build('admin/comments/edit_comment', $data);

	}

	public function remove_comment($id)
	{
		if ($this->Admin_comments_m->remove_comment($id))
		{
			$this->session->set_flashdata('success', lang('comment_removed_success_resp'));
			redirect('admin_comments');
		}
		$this->session->set_flashdata('error', lang('comment_removed_fail_resp'));
		redirect('admin_comments');
	}

	public function accept_comment($id)
	{
		if ($this->Admin_comments_m->accept_comment($id))
		{
			$this->session->set_flashdata('success', lang('comment_accept_success_resp'));
			redirect('admin_comments');
		}
		$this->session->set_flashdata('error', lang('comment_accept_fail_resp'));
		redirect('admin_comments');
	}


	public function hide_comment($id)
	{
		if ($this->Admin_comments_m->hide_comment($id))
		{
			$this->session->set_flashdata('success', lang('comment_hide_success_resp'));
			redirect('admin_comments');
		}
		$this->session->set_flashdata('error', lang('comment_hide_fail_resp'));
		redirect('admin_comments');
	}

}
