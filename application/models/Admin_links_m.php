<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_links_m extends CI_Model
{
	protected $_table;

	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_links()
	{
		return $this->db->get($this->_table['links'])->result();
	}

	public function get_link($id)
	{
		return $this->db->where('id', $id)->limit(1)->get($this->_table['links'])->row_array();
	}

	public function add_link($data)
	{
		return $this->db->insert($this->_table['links'], $data);
	}

	public function update_link($id, $data)
	{
		return $this->db->where('id', $id)->update($this->_table['links'], $data);
	}

	public function remove_link($id)
	{
		return $this->db->delete($this->_table['links'], ['id' => $id]);
	}

}
