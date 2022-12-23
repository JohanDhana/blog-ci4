<?php
namespace App\Models; 

use CodeIgniter\Model;
 
class Post_model extends Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function count_posts()
	{
		return	$this->db->count_all_results('posts');
	}

	public function count_posts_by_category($slug)
	{

		$query = $this->db->query('SELECT count(*) as count
		FROM posts p
		JOIN post_categories pc ON p.id = pc.post_id
		JOIN categories c ON pc.category_id = c.id
		WHERE p.id IN
		  (SELECT post_id
		  FROM post_categories pc 
		  JOIN categories c ON pc.category_id = c.id
		  WHERE c.slug = "' . $slug . '")  GROUP BY p.id');
		return $query->result_array()[0]['count'];
	}

	public function count_posts_searched()
	{
		$query = $this->input->get('search_query');

		$this->db->or_like('title', $query);
		$this->db->or_like('body', $query);
		return	$this->db->count_all_results('posts');
	}


	public function get_posts($limit = FALSE, $offset = FALSE)
	{
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('posts');
		return $query->result_array();
	}

	public function search_posts($limit = FALSE, $offset = FALSE)
	{
		$query = $this->input->get('search_query');
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->or_like('title', $query);
		$this->db->or_like('body', $query);
		$query = $this->db->get('posts');
		return $query->result_array();
	}

	public function create_post($icon, $image,	$categories)
	{
		$slug = url_title($this->input->post('title'), '-', TRUE);
		$on_homepage = $this->input->post('on_homepage') === 'on' ? 1 : 0;
		$slug_results =	$this->check_unique_slug(null, $slug);
		$parent = empty($this->input->post('parent_id')) ? null : $this->input->post('parent_id');
		if ($slug_results === 0) {
			$data = array(
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'on_homepage' => $on_homepage,
				'tags' => $this->input->post('tags'),
				'post_icon' => $icon,
				'post_image' => $image,
				'seo_title' => $this->input->post('seo_title'),
				'seo_description' => $this->input->post('seo_description'),
				'parent' => $parent,
				'body' => $this->input->post('body'),

				// 'user_id' => $this->session->userdata('user_id'),
			);
			if ($this->input->post('seo_schema')) {
				$data['seo_schema'] = $this->input->post('seo_schema');
			}

			$insrt = $this->db->insert('posts', $data);
			$post_id = $this->get_posts_by_slug($slug)['id'];
			$post_category = array();
			foreach ($categories as $value) {
				array_push($post_category, ['post_id' => $post_id, 'category_id' => $value]);
			}
			$this->db->insert_batch('post_categories', $post_category);
			return $insrt;
		} else {
			$this->session->set_flashdata('bad_request', 'A page with this title already exist');
		}
	}

	public function delete_post($id)
	{
		$image_file_name = $this->db->select('post_image')->get_where('posts', array('id' => $id))->row()->post_image;
		$icon_file_name = $this->db->select('post_icon')->get_where('posts', array('id' => $id))->row()->post_icon;
		$cwd = getcwd(); // save the current working directory
		$image_file_path = $cwd . "\\assets\\images\\posts\\";
		chdir($image_file_path);
		unlink($image_file_name);
		unlink($icon_file_name);
		chdir($cwd); // Restore the previous working directory
		$this->db->where('id', $id);
		$this->db->delete('posts');
		return true;
	}

	public function update_post($id, $post_icon, $image, $categories)
	{
		$on_homepage = $this->input->post('on_homepage') === 'on' ? 1 : 0;
		$parent = empty($this->input->post('parent_id')) ? null : $this->input->post('parent_id');

		$data = array(
			'title' => $this->input->post('title'),
			'body' => $this->input->post('body'),
			'seo_title' => $this->input->post('seo_title'),
			'seo_description' => $this->input->post('seo_description'),
			'parent' => $parent,
			'tags' => $this->input->post('tags'),
			'on_homepage' => $on_homepage,
		);

		if ($this->input->post('seo_schema')) {
			$data['seo_schema'] = $this->input->post('seo_schema');
		}

		if ($post_icon) {
			$data['post_icon'] = $post_icon;
		}
		if ($image) {
			$data['post_image'] = $image;
		}


		$update = $this->db->update('posts', $data, ['id' => $id]);
		$this->db->delete('post_categories', ['post_id' => $id]);
		if ($categories) {
			$post_category = array();
			foreach ($categories as $category_id) {
				array_push($post_category, array('post_id' => $id, 'category_id' => $category_id));
			}
			$this->db->insert_batch('post_categories', $post_category);
		}

		return $update;
	}

	public function get_categories()
	{
		$this->db->order_by('name');
		$query = $this->db->get('categories');
		return $query->result_array();
	}

	public function posts_by_category($category_slug)
	{

		$query = $this->db->query('SELECT p.*
		FROM posts p
		JOIN post_categories pc ON p.id = pc.post_id
		JOIN categories c ON pc.category_id = c.id
		WHERE p.id IN
		  (SELECT post_id
		  FROM post_categories pc 
		  JOIN categories c ON pc.category_id = c.id
		  WHERE c.slug = "' . $category_slug . '")  GROUP BY p.id');
		return $query->result_array();
	}

	public function get_posts_by_id($id)
	{
		$query = $this->db->get_where('posts', array('id' => $id));
		return $query->row_array();
	}

	public function get_posts_by_id_with_categories($id)
	{
		$query = $this->db->query('SELECT pc.post_id AS `post_id`, c.name AS `category_name`,c.id as `category_id` 
		FROM post_categories AS pc
		INNER JOIN categories  AS c ON pc.category_id = c.id where pc.post_id=' . $id);
		return $query->result_array();
	}


	public function get_posts_by_slug($slug)
	{
		$query = $this->db->get_where('posts', array('slug' => $slug));
		return $query->row_array();
	}

	function check_unique_slug($id = '', $slug)
	{
		$this->db->where('slug', $slug);

		if ($id) {
			$this->db->where_not_in('id', $id);
		}
		return $this->db->get('posts')->num_rows();
	}


	public function  get_posts_count_with_parent($parent_id)
	{
		$this->db->where('parent', $parent_id);
		$this->db->from('posts');
		return $this->db->count_all_results();
	}

	public function get_posts_nested($limit = FALSE, $offset = FALSE)
	{

		$this->db->select('*');
		$this->db->from('posts');
		$this->db->where('parent is  NULL', NULL, FALSE);
		$this->db->where('on_homepage', 1);

		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$parent = $this->db->get();

		$posts = $parent->result();
		$i = 0;
		foreach ($posts as $main_post) {

			$posts[$i]->sub = $this->sub_post($main_post->id);
			$i++;
		}
		return $posts;
	}

	public function sub_post($id)
	{

		$this->db->select('*');
		$this->db->from('posts');
		$this->db->where('parent', $id);

		$child = $this->db->get();
		$categories = $child->result();
		$i = 0;
		foreach ($categories as $sub_post) {

			$categories[$i]->sub = $this->sub_post($sub_post->id);
			$i++;
		}
		return $categories;
	}
}
