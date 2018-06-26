<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_navs_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{
		parent::__construct();
		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_navs()
	{
		 return $this->db->get($this->_table['navigation'])->result();

	}

	public function get_nav($id)
	{
		return $this->db->where('id', $id)->limit(1)->get($this->_table['navigation'])->row_array();
	}

	public function add_nav($data)
	{
		$data['url'] = (isset($data['url'])) ? $data['url'] : '';

		if (! empty($data['post']))
		{
			$data['url'] = $data['post'];
		}
		elseif (! empty($data['page']))
		{
			$data['url'] = 'page/' . $data['page'];
		}
		unset($data['post']);
		unset($data['page']);

		$data['external'] = '0';
		$data['position'] = $this->get_next_nav_position();

		return $this->db->insert($this->_table['navigation'], $data);
	}

	public function update_nav($id, $data)
	{
		$current = $this->get_nav($id);
		$new_slug = false;
    	$redirect_val = $data['redirection'];
    	unset($data['redirection']);

    	if (isset($data['url']) && $data['url'] != $current['url'])
    	{
    		$new_slug = true;
    	}
    	elseif (! empty($data['post']) && $data['post'] != $current['url'])
    	{
    		$new_slug = true;
    		$data['url'] = $data['post'];
    	}
    	elseif (! empty($data['page']) && $data['page'] != $current['url'])
    	{
    		$new_slug = true;
    		$data['url'] = 'page/' . $data['page'];
    	}

    	if ($new_slug)
    	{
    		switch ($redirect_val) {
    			case 'none':
    				break;
    			case '301' || '302':
    				$this->obcore->set_redirect($current['url'], $data['url'], $data['type'], $redirect_val);
    				break;
    			default:
    				$this->obcore->set_redirect($current['url'], $data['url'], $data['type'], '301');
    				break;
    		}
    	}

		unset($data['type']);
		unset($data['post']);
		unset($data['page']);

		return $this->db->where('id', $id)->update($this->_table['navigation'], $data);
	}

	public function remove_nav($id)
	{
		return $this->db->delete($this->_table['navigation'], ['id' => $id]);
	}

	public function get_page_slugs()
	{
		$options = $this->db->select('title, url_title')->get($this->_table['pages'])->result();

		$return[null] = lang('nav_form_choose_page');
		$return['pages/'] = lang('pages_index_controller_label');

		foreach ($options as $opt)
		{
			$return[$opt->url_title] = $opt->title;

		}
		return $return;
	}

	public function get_post_slugs()
	{
		$options = $this->db->select('date_posted, title, url_title')->get($this->_table['posts'])->result();
		$return[null] = lang('nav_form_choose_post');

		foreach ($options as $opt)
		{
			$return[$opt->url_title] = $opt->title;

		}

		return $return;
	}

	public function get_next_nav_position()
	{
		$row = $this->db->order_by('position', 'DESC')->limit(1)->get($this->_table['navigation'])->row();

		return $row->position + 1;
	}

	public function update_nav_order($post_data)
	{
		$i = 0;

		foreach ($post_data['item'] as $value) {

			if ( ! $this->db->where('id', $value)->update($this->_table['navigation'], ['position' => $i]))
			{
				return false;
			}
    		$i++;
		}

		return true;
	}

	public function get_redirects()
	{
		return $this->db->get($this->_table['redirects'])->result();
	}

	public function get_redirect($id)
	{
		return $this->db->where('id', $id)->limit(1)->get($this->_table['redirects'])->row_array();
	}

	public function update_redirect($id, $data)
	{
		return $this->db->where('id', $id)->update($this->_table['redirects'], $data);
	}

	public function remove_redirect($id)
	{
		return $this->db->delete($this->_table['redirects'], ['id' => $id]);
	}

}
