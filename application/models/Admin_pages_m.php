<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_pages_m extends CI_Model
{
	protected $_table;

	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_pages()
	{
		return $this->db->get($this->_table['pages'])->result();
	}

	public function get_page($id)
	{
		return $this->db->where('id', $id)->limit(1)->get($this->_table['pages'])->row_array();
	}

	public function add_page($data)
	{
		return $this->db->insert($this->_table['pages'], $data);
	}

	public function update_page($id, $data)
	{
		if ($data['is_home'] == 1)
		{
			if ( ! $this->db->set('is_home', '0')->update($this->_table['pages']))
			{
				return false;
			}
		}
		return $this->db->where('id', $id)->update($this->_table['pages'], $data);
	}

	public function remove_page($id)
	{
		$page = $this->db->where('id', $id)->limit(1)->get('pages')->row();
		$this->obcore->remove_redirects($page->url_title);		
		return $this->db->delete($this->_table['pages'], ['id' => $id]);
	}
}
