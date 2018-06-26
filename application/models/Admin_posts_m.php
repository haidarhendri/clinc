<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_posts_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_posts()
	{
		 return $this->db->get($this->_table['posts'])->result();
	}

	public function get_post($id)
	{
		$post = $this->db->where('id', $id)->limit(1)->get($this->_table['posts'])->row_array();
		$query_cats = $this->db->select('category_id')->where('post_id', $post['id'])->get($this->_table['posts_to_categories'])->result_array();
		foreach ($query_cats as $k => $v)
		{
			$post['selected_cats'][] = $v['category_id'];
		}

		$post['cats'] = $this->get_cats_form();

		return $post;
	}

	public function add_post($data)
	{
		$cats = $data['cats'];
		unset($data['cats']);

		if ($this->db->insert($this->_table['posts'], $data))
		{
			$new_post_id = $this->db->insert_id();

			if ($this->insert_cats_to_post($new_post_id, $cats))
			{
				if ($data['status'] == 'published')
				{
					$this->load->library('markdown');
					$subs = $this->db->where('verified', 1)->get('notifications')->result();

					foreach ($subs as $sub)
					{
						$this->obcore->send_email( $sub->email_address, $data['title'] . ' - ' . $this->config->item('site_name'), lang('post_new_post_notification_msg') . $this->markdown->parse($data['content']) . lang('post_new_post_notification_msg_foot') . '[<a href="' . site_url('notices/unsub') . '">Unsubscribe</a>]');
					}
				}
				return true;
			}
			return false;
		}
		return false;
	}

	public function update_post($id, $data)
	{
		$old = $this->db->where('id', $id)->limit(1)->get($this->_table['posts'])->row();
		$cats = $data['cats'];
		unset($data['cats']);

		if ($this->db->where('id', $id)->update($this->_table['posts'], $data) && $this->update_cats_to_post($id, $cats))
		{
			if ($data['status'] == 'published' && $old->status == 'draft')
				{
					$this->load->library('markdown');
					$subs = $this->db->where('verified', 1)->get('notifications')->result();

					foreach ($subs as $sub)
					{
						$this->obcore->send_email( $sub->email_address, $data['title'] . ' - ' . $this->config->item('site_name'), lang('post_new_post_notification_msg') . $this->markdown->parse($data['content']) . lang('post_new_post_notification_msg_foot') . '[<a href="' . site_url('notices/unsub') . '">Unsubscribe</a>]');
					}

				}
			return true;
		}
		return false;
	}

	public function remove_post($id)
	{
		$post = $this->db->where('id', $id)->limit(1)->get('posts')->row();
		$this->obcore->remove_redirects($post->url_title);
		$this->remove_post_to_cats($id);
		return $this->db->delete($this->_table['posts'], ['id' => $id]);
	}
	public function remove_post_to_cats($post_id)
	{
		return $this->db->delete($this->_table['posts_to_categories'], ['post_id' => $post_id]);
	}

	public function update_cats_to_post($post_id, $cats)
	{
		if ( ! $cats || ! $post_id )
		{
			return false;
		}

		$return = true;
		$cur_cats = $this->db->where('post_id', $post_id)->get($this->_table['posts_to_categories'])->result_array();
		foreach ($cur_cats as $c_k => $c_v)
		{
			foreach ($cats as $k => $v)
			{
				if ($v == $c_v['category_id'] && $c_v['post_id'] == $post_id)
				{
					unset($cats[$k]);
					unset($cur_cats[$c_k]);
				}
			}
		}
		if ( $cur_cats )
		{
			foreach ($cur_cats as $cat)
			{
				if (! $this->db->where('id', $cat['id'])->delete($this->_table['posts_to_categories']) )
				{
					$return = false;
				}
			}
		}

		if ( $cats && $return == true)
		{
			return $this->insert_cats_to_post($post_id, $cats);
		}

		return true;
	}

	public function insert_cats_to_post($post_id, $cats)
	{
		foreach ($cats as $k => $v)
		{
			$insert[] = ['post_id' => $post_id, 'category_id' => $v];
		}

		if ($this->db->insert_batch($this->_table['posts_to_categories'], $insert))
		{
			return true;
		}
		return false;
	}

	public function get_cats_form()
	{
		$cats = $this->db->select('id, name')->get('categories')->result_array();
		$ret = [];
		foreach ($cats as $k => $v)
		{
			$ret[$v['id']] = $v['name'];
		}
		return $ret;
	}
}
