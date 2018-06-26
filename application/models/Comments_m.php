<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Comments_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{
		parent::__construct();

		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_comments($post_id)
	{
		$this->db
				->where('post_id', $post_id)
				->where('modded', 0)
				->order_by('id', 'ASC');

		$query = $this->db->get($this->_table['comments']);

		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach ($result as &$item)
			{
				$item->content = nl2br($item->content);
				$item->date = DateTime::createFromFormat('Y-m-d H:i:s', $item->date)->format('M d Y');

				if ($item->user_id)
				{
					$item->author = $this->ion_auth->get_db_display_name($item->user_id);
				}
			}
			return $result;
		}
		return [];
	}

	public function get_latest_comments($number = 10, $offset = 0)
	{
		$this->db->select('comments.id, comments.user_id, comments.author, comments.author_email, comments.content, comments.date, posts.title, posts.url_title, posts.date_posted');
		$this->db->from($this->_table['comments'] . ' comments');
		$this->db->join($this->_table['posts'] . ' posts', 'comments.post_id = posts.id');
		$this->db->order_by('comments.id', 'DESC');
		$this->db->limit($number, $offset);

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function get_comment_author($id)
	{
		$this->db->select('user_id, author');
		$this->db->where('id', $id);

		$query = $this->db->get($this->_table['comments'], 1);

		if ($query->num_rows() == 1)
		{
			$row = $query->row_array();

			if ($row['user_id'] == "")
			{
				return $row['author'];
			}
		}
	}

	public function create_comment($id)
	{
		// is the commenter logged in?
		if ( $this->ion_auth->logged_in() )
		{
			// yes
			$data = [
				'post_id' 		=> $id,
				'user_id' 		=> $this->ion_auth->get_user_id(),
				'author_ip' 	=> $_SERVER['REMOTE_ADDR'],
				'content' 		=> $this->input->post('comment'),
				'date' 			=> date('Y-m-d H:i:d'),
				'modded'		=> $this->config->item('mod_user_comments')
			];
		}
		else
		{
			$data = [
				'post_id' 		=> $id,
				'author' 		=> $this->input->post('nickname'),
				'author_email' 	=> $this->input->post('email'),
				'author_ip' 	=> $_SERVER['REMOTE_ADDR'],
				'content' 		=> $this->input->post('comment'),
				'date' 			=> date('Y-m-d H:i:d'),
				'modded'		=> $this->config->item('mod_non_user_comments')
			];
		}
		if ($this->db->insert($this->_table['comments'], $data))
		{
			$this->obcore->send_email($this->config->item('admin_email'), $this->config->item('site_name') . ' ' . lang('email_new_comment_sbj'), lang('email_new_comment_msg') . $this->input->post('comment'));
			return true;
		}
		return false;
	}
}
