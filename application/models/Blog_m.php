<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Categories_m');
		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}

	public function get_posts($offset = 0)
	{
		$current_date = date('Y-m-d');
		$this->db->select( $this->db->dbprefix($this->_table['posts']. '.*,') . $this->db->dbprefix($this->_table['users'] . '.first_name, ') . $this->db->dbprefix($this->_table['users']. '.last_name') )
					->join( $this->db->dbprefix($this->_table['users']), $this->db->dbprefix($this->_table['posts']. '.author') . ' = '  . $this->db->dbprefix($this->_table['users'] . '.id') )
					->where($this->db->dbprefix($this->_table['posts'] . '.status'), 'published')
					->where($this->db->dbprefix($this->_table['posts'] . '.date_posted') . ' <= ', $current_date)
					->order_by($this->db->dbprefix($this->_table['posts'] . '.sticky'), 'DESC')
					->order_by($this->db->dbprefix($this->_table['posts'] . '.id'), 'DESC')
					->limit($this->config->item('posts_per_page'), $offset);

		$query = $this->db->get($this->_table['posts']);

		if ($query->num_rows() > 0)
		{
			$result['posts'] = $query->result_array();

			foreach ($result['posts'] as &$item)
			{
				$item['url'] = post_url($item['url_title'], $item['date_posted']);
				$item['display_name'] = $this->concat_display_name($item['first_name'], $item['last_name']);
				$item['categories'] = $this->Categories_m->get_categories_by_ids($this->get_post_categories($item['id']));
				$item['comment_count'] = $this->db->where('post_id', $item['id'])->where('modded', '0')->from($this->_table['comments'])->count_all_results();
				$item['date_posted'] = DateTime::createFromFormat('Y-m-d', $item['date_posted'])->format('D M d Y');
			}

			$result['post_count'] = $query->num_rows();

			return json_decode(json_encode($result));
		}
		return array();
	}

	public function get_links()
	{
		$query = $this->db->where('visible', 'yes')->order_by('position', 'ASC')->get('links');

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return false;
	}

	public function get_archive()
	{
		$this->db->select('COUNT(' . $this->db->dbprefix($this->_table['posts'] . '.id') . ') AS posts_count, ' . $this->db->dbprefix($this->_table['posts'] .'.date_posted') . ' FROM ' . $this->db->dbprefix($this->_table['posts']) . ' WHERE ' . $this->db->dbprefix($this->_table['posts'] .'.status') . ' = \'published\' GROUP BY SUBSTRING(' . $this->db->dbprefix($this->_table['posts'] .'.date_posted') . ', 1, 7)', FALSE)
					->order_by($this->db->dbprefix($this->_table['posts'] . '.date_posted'), 'DESC')
					->limit($this->config->item('months_per_archive'));

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();

			foreach ($result as &$item)
			{
				$item['url']  = date('Y', strtotime($item['date_posted'])) . '/' . date('m', strtotime($item['date_posted'])) . '/';
				$item['date_posted']  = strftime('%B %Y', strtotime($item['date_posted']));
			}
			return $result;
		}
		return false;
	}

	public function get_categories()
	{
		return $this->Categories_m->get_categories();
	}

	public function concat_display_name($fname = 'empty', $lname='empty')
	{
		return $fname . ' ' . $lname;
	}

	public function get_post_by_url($url_title)
	{
		$this->load->library('markdown');

		$this->db->select('posts.*, users.first_name, users.last_name')
					->join($this->_table['users'] . ' users', 'posts.author = users.id')
					->where('posts.status', 'published')
					->where('posts.url_title', $url_title);


		$this->db->limit(1);

		$query = $this->db->get($this->_table['posts']);

		if ($query->num_rows() == 1)
		{
			$result = $query->row_array();
			$result['content'] = $this->markdown->parse($result['content']);
			$result['url'] = post_url($result['url_title']);
			$result['display_name'] = $this->concat_display_name($result['first_name'], $result['last_name']);
			$result['categories'] = $this->Categories_m->get_categories_by_ids($this->get_post_categories($result['id']));
			$result['comment_count'] = $this->db->where('post_id', $result['id'])->where('modded', '0')->from($this->_table['comments'])->count_all_results();

			return $result;
		}
		return false;
	}

	public function get_posts_by_date($year, $month)
	{
		$result = new stdClass();
		$date = $year . '-' . $month;
		$current_date = date('Y-m-d');

		$this->db->select('posts.*, users.first_name, users.last_name')
					->join($this->_table['users'] . ' users', 'posts.author = users.id')
					->where('posts.status', 'published')
					->where('posts.date_posted <=', $current_date)
					->like('posts.date_posted', $date)
					->order_by('posts.sticky', 'DESC')
					->order_by('posts.id', 'DESC');

		$query = $this->db->get($this->_table['posts']);

		if ($query->num_rows() > 0)
		{
			$result->posts = $query->result();

			foreach ($result->posts as &$item)
			{
				$item->url = post_url($item->url_title, $item->date_posted);
				$item->display_name = $this->concat_display_name($item->first_name, $item->last_name);
				$item->categories = $this->Categories_m->get_categories_by_ids($this->get_post_categories($item->id));
				$item->comment_count = $this->db->where('post_id', $item->id)->where('modded', '0')->from($this->_table['comments'])->count_all_results();
			}

			$result->post_count = $query->num_rows();

			return $result;
		}
		return [];
	}

	public function get_posts_by_category($url_name)
	{
		$result = new stdClass();
		$current_date = date('Y-m-d');

		$this->db->select('posts.*, users.first_name, users.last_name')
					->join($this->_table['posts_to_categories'] . ' posts_to_categories', 'posts.id = posts_to_categories.post_id')
					->join($this->_table['categories'] . ' categories', 'posts_to_categories.category_id = categories.id')
					->join($this->_table['users'] . ' users', 'posts.author = users.id')
					->where('posts.status', 'published')
					->where('posts.date_posted <=', $current_date)
					->where('categories.url_name', $url_name)
					->order_by('posts.sticky', 'DESC')
					->order_by('posts.id', 'DESC');

		$query = $this->db->get($this->_table['posts']);

		if ($query->num_rows() > 0)
		{
			$result->posts = $query->result();

			foreach ($result->posts as &$item)
			{
				$item->url = post_url($item->url_title, $item->date_posted);
				$item->display_name = $this->concat_display_name($item->first_name, $item->last_name);
				$item->categories = $this->Categories_m->get_categories_by_ids($this->get_post_categories($item->id));
				$item->comment_count = $this->db->where('post_id', $item->id)->where('modded', '0')->from($this->_table['comments'])->count_all_results();
			}

			$result->post_count = $query->num_rows();

			return $result;
		}
		return [];
	}


public function get_posts_count()
	{
		$this->db->select('id');
		$this->db->where('status', 'published');

		$query = $this->db->count_all_results($this->_table['posts']);

		return $query;
	}

	public function get_post_categories($post_id)
	{
		$this->db->select('category_id');
		$this->db->where('post_id', $post_id);

		$query = $this->db->get($this->_table['posts_to_categories']);

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();

			foreach ($result as $category)
			{
				$categories[] = $category['category_id'];
			}

			return $categories;
		}
	}

	public function get_post_by_id($post_id)
	{
		$this->db->select('posts.id, posts.author, posts.date_posted, posts.title, posts.url_title, posts.excerpt, posts.content, posts.allow_comments, posts.sticky, posts.status, posts.author, users.display_name');
		$this->db->from($this->_table['posts'] . ' posts');
		$this->db->join($this->_table['users'] . ' users', 'posts.author = users.id');
		$this->db->where('posts.status', 'published');
		$this->db->where('posts.id', $post_id);
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();

			foreach (array_keys($result) as $key)
			{
				$result[$key]['categories'] = $this->categories->get_categories_by_ids($this->get_post_categories($result[$key]['id']));
				$result['comment_count'] = $this->db->where('post_id', $result['id'])->from($this->_table['comments'])->count_all_results();
			}

			return $result;
		}
	}

	public function get_posts_by_tags($tag_name)
	{
		$current_date = date('Y-m-d');

		$this->db->select('posts.id, posts.author, posts.date_posted, posts.title, posts.url_title, posts.excerpt, posts.content, posts.allow_comments, posts.sticky, posts.status, posts.author, users.display_name');
		$this->db->from($this->_table['posts'] . ' posts');
		$this->db->join($this->_table['users'] . ' users', 'posts.author = users.id');
		$this->db->join($this->_table['tags_to_posts'] . ' tags_to_posts', 'posts.id = tags_to_posts.post_id');
		$this->db->join($this->_table['tags'] . ' tags', 'tags_to_posts.tag_id = tags.id');
		$this->db->where('posts.status', 'published');
		$this->db->where('posts.date_posted <=', $current_date);
		$this->db->where('tags.name', $tag_name);
		$this->db->order_by('posts.sticky', 'DESC');
		$this->db->order_by('posts.id', 'DESC');

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();

			foreach (array_keys($result) as $key)
			{
				$result[$key]['categories'] = $this->categories->get_categories_by_ids($this->get_post_categories($result[$key]['id']));
				$result[$key]['comment_count'] = $this->db->where('post_id', $result[$key]['id'])->from($this->_table['comments'])->count_all_results();
			}

			return $result;
		}
	}

	public function get_posts_by_term($term)
	{
		$current_date = date('Y-m-d');

		$this->db->select('posts.id, posts.author, posts.date_posted, posts.title, posts.url_title, posts.excerpt, posts.content, posts.allow_comments, posts.sticky, posts.status, posts.author, users.display_name');
		$this->db->from($this->_table['posts'] . ' posts');
		$this->db->join($this->_table['users'] . ' users', 'posts.author = users.id');
		$this->db->where('posts.status', 'published');
		$this->db->where('posts.date_posted <=', $current_date);
		$this->db->like('posts.title', $term);
		$this->db->orlike('posts.excerpt', $term);
		$this->db->orlike('posts.content', $term);
		$this->db->order_by('posts.sticky', 'DESC');
		$this->db->order_by('posts.id', 'DESC');

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();

			foreach (array_keys($result) as $key)
			{
				$result[$key]['categories'] = $this->categories->get_categories_by_ids($this->get_post_categories($result[$key]['id']));
				$result[$key]['comment_count'] = $this->db->where('post_id', $result[$key]['id'])->from($this->_table['comments'])->count_all_results();
			}

			return $result;
		}
	}
}
