<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends OB_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pages_m');
		$this->load->language('pages', $this->session->language);
	}

	public function index($page=null)
	{
		$data['page'] = $this->Pages_m->get_home_page();
		$this->obcore->set_meta($data['page'], 'page', true);
		$this->template->build('pages/index', $data);
	}

	public function page($url_title)
	{
		$data['page'] = $this->Pages_m->get_page_by_url($url_title);
		$this->template->build('pages/index', $data);
	}
}
