<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}
	public function get_categories()
	{
		$this->db->select('id, name, url_name')
		 			->select('(SELECT COUNT(' . $this->db->dbprefix($this->_table['posts_to_categories'] . '.id') . ' ) FROM ' . $this->db->dbprefix($this->_table['posts_to_categories']) . ' WHERE ' . $this->db->dbprefix($this->_table['posts_to_categories'] . '.category_id') . ' = ' . $this->db->dbprefix($this->_table['categories'] . '.id') . ') AS posts_count', FALSE)
					->order_by('id', 'ASC')
					->limit($this->config->item('category_list_limit'));

		$query = $this->db->get($this->_table['categories']);

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function get_categories_by_ids($category_ids)
	{
		$this->db->where_in('id', $category_ids);

		$query = $this->db->get($this->_table['categories']);

		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
	public function get_categories_by_post($post_id)
	{
		$this->db->select('categories.name');
		$this->db->join($this->_table['categories'] . ' categories', 'posts_to_categories.category_id = categories.id');
		$this->db->where('post_id', $post_id);

		$query = $this->db->get($this->_table['posts_to_categories'] . ' posts_to_categories');

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
}
