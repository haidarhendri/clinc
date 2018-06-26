<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_social_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{

		parent::__construct();
		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_social_links()
	{
		return $this->db->get($this->_table['social'])->result_array();
	}


	public function get_social_link($id)
	{
		return $this->db->where('id', $id)->limit(1)->get($this->_table['social'])->row_array();
	}

	public function add_social($data)
	{
		return $this->db->insert($this->_table['social'], $data);
	}

	public function update_social($id, $data)
	{
		return $this->db->where('id', $id)->update($this->_table['social'], $data);
	}

	public function remove($id)
	{
		return $this->db->delete($this->_table['social'], ['id' => $id]);
	}
}
