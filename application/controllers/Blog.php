<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends OB_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Blog_m');
	}


	public function index($offset=0)
	{
		$posts = $this->Blog_m->get_posts();

		$this->load->library('pagination');

		$config['base_url'] = site_url();
		$config['total_rows'] = ($posts && $posts->post_count) ? $posts->post_count : 0;
		$config['per_page'] = $this->config->item('posts_per_page');

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['posts']= ($posts && $posts->posts)? $posts->posts : '';



		$this->template->build('posts/index', $data);
	}

	public function category($url_name = null)
	{

		if ($data = $this->Blog_m->get_posts_by_category($url_name))
		{

			$this->load->library('pagination');

			$config['base_url'] = site_url();
			$config['total_rows'] = $data->post_count;
			$config['per_page'] = $this->config->item('posts_per_page');

			$this->pagination->initialize($config);

			$data->pagination = $this->pagination->create_links();


		}
		else
		{
			$data['posts'] = FALSE;
		}
		$this->template->build('posts/index', $data);
	}

	public function archive($year=null, $month=null, $offset=0)
	{
		if ($data = $this->Blog_m->get_posts_by_date($year, $month))
		{
			$this->load->library('pagination');

			$config['base_url'] = site_url();
			$config['total_rows'] = $data->post_count;
			$config['per_page'] = $this->config->item('posts_per_page');

			$this->pagination->initialize($config);

			$data->pagination = $this->pagination->create_links();

			$this->template->build('posts/index', $data);
		}
	}

	public function post($url_title = NULL)
	{
		$this->load->model('Comments_m');
		$this->load->library('form_validation');

		if ($data['post'] = $this->Blog_m->get_post_by_url($url_title))
		{
			$data['comments'] = $this->Comments_m->get_comments($data['post']['id']);

			if ( $this->config->item('allow_comments') == 1 && $this->input->post() || $data['post']['allow_comments'] == 1 && $this->input->post() )
			{
				if ($data['post']['allow_comments'] != 0)
				{
					$this->new_comment($data['post']['id'], $data['post']['url']);
				}
			}


		}

		$this->obcore->set_meta($data['post'], 'post');

		$this->template->build('posts/single_post', $data);

	}

	public function new_comment($id, $url, $parent='false')
	{
		$this->load->model('Comments_m');

		if ($this->config->item('use_recaptcha') == 1)
		{
			$this->form_validation->set_rules('g-recaptcha-response', 'lang:recaptcha', 'callback_verify_recaptcha');
		}

		if ($this->config->item('use_honeypot') == 1)
		{
			if (!empty($this->input->post('date_stamp_gotcha')))
			{
				redirect();
			}
		}

		if ($this->ion_auth->logged_in() == FALSE)
		{
			$this->form_validation->set_rules('nickname', 'lang:nickname', 'required|max_length[50]');
			$this->form_validation->set_rules('email', 'lang:email', 'required|valid_email');


		}

		$this->form_validation->set_rules('comment', 'lang:comments', 'required|max_length[400]|htmlentities');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE)
		{
			if ($this->Comments_m->create_comment($id))
			{
				$message = 'unknown error';

				if ($this->ion_auth->logged_in())
				{
					$message = ($this->config->item('mod_user_comments') == 0) ? lang('add_comment_success') : lang('add_comment_success_modded');
				}
				else
				{
					$message = ($this->config->item('mod_non_user_comments') == 0) ? lang('add_comment_success') : lang('add_comment_success_modded');
				}

				$this->session->set_flashdata('success', $message);

				redirect($url);
			}


		}
	}

	public function verify_recaptcha($str)
	{
		$this->load->library('rest');

		$config = array(
				'server' => 'https://www.google.com/recaptcha/api/',
            );

		$post = array(
				'secret'	=> $this->config->item('recaptcha_private_key'),
				'response'	=> $str,
				'remoteip'	=> $this->input->ip_address()
			);

		$this->rest->initialize($config);

		$recaptcha = $this->rest->post('siteverify', $post);

		$recaptcha = (array) $recaptcha;

		if ( isset($recaptcha['error-codes']))
		{
			$this->load->helper('inflector');
			foreach ($recaptcha['error-codes'] as $error)
			{
				$this->form_validation->set_message('verify_recaptcha', 'Recaptcha - ' . humanize($error, '-'));
			}
			return false;
		}
		return true;
	}
} 
