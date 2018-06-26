<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_m extends CI_Model
{
	protected $_table;

	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_dashboard()
	{
		$data = new stdClass();
		$data->post_count = $this->count_posts();
		$data->active_comments_count = $this->count_comments();
		$data->modded_comments_count = $this->count_comments(1);
		$data->notification_count = $this->count_notices();
		$data->news = $this->get_news();

		return $data;
	}

	public function get_settings_list()
	{
		$data = new stdClass();
		$tabs = $this->db->select('tab')->distinct()->get('settings')->result();

		foreach ($tabs as &$tab)
		{
			$tab->list = $this->db->where('tab', $tab->tab)->get('settings')->result();
			foreach ($tab->list as &$item)
			{
				$item->input = $this->obcore->build_form_field($item->field_type, $item->name, $item->value, $item->options);
			}
		}

		$data->settings = $tabs;

		return $data;
	}

	public function update_settings()
	{
		if (! $this->input->post())
		{
			return FALSE;
		}

		foreach ($this->input->post() as $k => $v)
		{
			if (! $this->db->where('name', $k)->update('settings', ['value' => $v]))
			{
				return false;
			}
		}

		return false;
	}

	public function get_required_settings()
	{
		return $this->db->where('required', 1)->get('settings')->result();
	}

	public function count_posts()
	{
		return $this->db->where('status', 'published')->count_all_results('posts');
	}

	public function count_comments($modded = '0')
	{
		return $this->db->where('modded', $modded)->count_all_results('comments');
	}

	public function count_notices()
	{
		return $this->db->where('verified', 1)->count_all_results('notifications');
	}

	public function get_groups_permissions($group_id)
	{
		$permissions = $this->db
						->where('groups_perms.group_id', $group_id)
						->join('groups_perms', 'groups_perms.perms_id = group_permissions.id')
						->get('group_permissions')
						->result();
		foreach ($permissions as & $perm)
		{
			$perm->name = (lang($perm->name)) ? lang($perm->name) : $perm->name;
		}

		return $permissions;
	}

	public function get_group_perms($group_id)
	{
		$permissions = $this->db
						->get('group_permissions')
						->result();

		foreach ($permissions as & $perm)
		{
			$perm->selected = $this->db->select('id')->where('perms_id', $perm->id)->where('group_id', $group_id)->limit(1)->get('groups_perms')->row();
			$perm->name = (lang($perm->name)) ? lang($perm->name) : $perm->name;
		}

		return $permissions;
	}

	public function update_group_perms($group, $data)
	{
		$current = $this->db->where('group_id', $group)->get('groups_perms')->result();
		foreach ($current as $ck => $item)
		{
			foreach ($data as $k => $v)
			{
				if ($k == $item->perms_id)
				{
					unset($current[$ck]);
					unset($data[$k]);
				}
			}
		}
		if ($current)
		{
			foreach ($current as $item)
			{
				$this->db->delete('groups_perms', ['id' => $item->id]);
			}
		}
		if ($data)
		{
			foreach ($data as $k => $v)
			{
				$this->db->insert('groups_perms', ['perms_id' => $k, 'group_id' => $group]);
			}
		}
	}

	public function get_news()
	{
		$data = new stdClass;
		$news_url = $this->config->item('ob_updates_url');
		if ($this->_isCurl())
		{
			$this->load->library('curl');
			$data = json_decode($this->curl->simple_get($news_url . '/get_news'));
		}
		return $data;
	}

	public function _isCurl()
	{
    	return function_exists('curl_init');
	}
}
