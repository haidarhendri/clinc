<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_m extends CI_Model
{
	protected $_table;

	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];

	}
	public function get_home_page()
	{
		$this->load->library('markdown');
		$this->db->where('is_home', 1)
					->where('status', 'active')
					->limit(1);
		$query = $this->db->get($this->_table['pages']);

		if ($query->num_rows() == 1)
		{
			$result = $query->row_array();
			$result['content'] = $this->markdown->parse($result['content']);

			return $result;
		}
		return false;
	}

	public function get_page_by_url($url_title)
	{
		if ($redirected = $this->obcore->has_redirect($url_title))
		{
			header("Location: " . site_url('pages/' . $redirected->new_slug), TRUE, $redirected->code);
		}

		$this->load->library('markdown');

		$this->db->where('url_title', $url_title)
					->where('status', 'active')
					->limit(1);

		$query = $this->db->get($this->_table['pages']);

		if ($query->num_rows() == 1)
		{
			$page = $query->row_array();
			$page['content'] = $this->markdown->parse($page['content']);

			return $page;
		}
		return false;
	}
}
