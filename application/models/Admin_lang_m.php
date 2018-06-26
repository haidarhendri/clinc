<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_lang_m extends CI_Model
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
		return $this->db->get($this->_table['languages'])->result_array();
	}

	public function toggle_is_avail($id, $toggle)
	{
		return $this->db->where('id', $id)->update($this->_table['languages'], ['is_avail' => $toggle]);
	}

     public function make_default($id)
     {
          if ($this->db->set('is_default', '0')->update($this->_table['languages']) )
          {
               return $this->db->where('id', $id)->update($this->_table['languages'], ['is_default' => '1', 'is_avail' => '1']);
          }
          return false;
     }

     public function get_language($id)
     {
          return $this->db->where('id', $id)->limit(1)->get('languages')->row();
     }

}
