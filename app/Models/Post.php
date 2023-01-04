<?php

namespace App\Models;

use CodeIgniter\Database\Query;
use CodeIgniter\Model;

class Post extends Model
{

	protected $DBGroup          = 'default';
	protected $table            = 'posts';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $insertID         = 0;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	protected $allowedFields    = ['title', 'slug', 'image', 'content', 'seo_title', 'seo_description',];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';

	// Validation
	protected $validationRules      =    ['title' => 'required', 'content' => 'required',];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	protected $beforeInsert   = [];
	protected $afterInsert    = [];
	protected $beforeUpdate   = [];
	protected $afterUpdate    = [];
	protected $beforeFind     = [];
	protected $afterFind      = [];
	protected $beforeDelete   = [];
	protected $afterDelete    = [];


	public function count_posts()
	{
		$builder  = $this->db->table('posts');
		return	$builder->countAllResults();
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
		return $query->getRow()->count;
	}

	public function count_posts_searched($query)
	{
		$builder  = $this->db->table('posts');
		$builder->orLike('title', $query);
		$builder->orLike('body', $query);
		return	$builder->countAllResults();
	}

	public function search_posts($query, $limit = 20, $offset = 0)
	{
		$postModel = new Post();
		return $postModel->select('*')->orLike('title', $query)->orLike('body', $query)->paginate(20, 'group1');
	}

	public function create_post($icon, $image,	$categories)
	{
		$builder  = $this->db->table('posts');

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

			$insrt = $builder->insert($data);
			$post_id = $this->get_posts_by_slug($slug)['id'];
			$post_category = array();
			foreach ($categories as $value) {
				array_push($post_category, ['post_id' => $post_id, 'category_id' => $value]);
			}
			$builderPC  = $this->db->table('post_categories');

			$builderPC->insertBatch($post_category);
			return $insrt;
		} else {
			$this->session->set_flashdata('bad_request', 'A page with this title already exist');
		}
	}

	public function delete_post($id)
	{
		$builder  = $this->db->table('posts');

		$image_file_name = $builder->select('post_image')->getWhere(array('id' => $id))->getRow()->post_image;
		$icon_file_name = $builder->select('post_icon')->getWhere(array('id' => $id))->getRow()->post_icon;
		$cwd = getcwd(); // save the current working directory
		$image_file_path = $cwd . "\\assets\\images\\posts\\";
		chdir($image_file_path);
		unlink($image_file_name);
		unlink($icon_file_name);
		chdir($cwd); // Restore the previous working directory
		$builder->where('id', $id);
		$builder->delete('posts');
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

		$builder  = $this->db->table('posts');
		$update = $builder->update($data, ['id' => $id]);
		$builderPC  = $this->db->table('post_categories');

		$builderPC->delete(['post_id' => $id]);
		if ($categories) {
			$post_category = array();
			foreach ($categories as $category_id) {
				array_push($post_category, array('post_id' => $id, 'category_id' => $category_id));
			}
			$builderPC->insertBatch($post_category);
		}

		return $update;
	}

	public function get_categories()
	{
		$builder  = $this->db->table('posts');
		$builder->orderBy('name');
		$query = $builder->get('categories');
		return $query->resultArray;
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
		return $query->resultArray;
	}


	public function get_posts_by_id_with_categories($id)
	{

		$query = $this->db->query('SELECT pc.post_id AS `post_id`, c.name AS `category_name`,c.id as `category_id` 
		FROM post_categories AS pc
		INNER JOIN categories  AS c ON pc.category_id = c.id where pc.post_id=' . $id);
		return $query->resultArray;
	}


	public function get_posts_by_slug($slug)
	{
		$builder  = $this->db->table('posts');
		$query = $builder->getWhere(array('slug' => $slug));
		return $query->getRowArray();
	}

	function check_unique_slug($id, $slug)
	{
		$builder  = $this->db->table('posts');

		$builder->where('slug', $slug);

		if ($id) {
			$builder->whereNotIn('id', $id);
		}
		return $builder->get('posts');
	}


	public function  get_posts_count_with_parent($parent_id)
	{
		$builder  = $this->db->table('posts');
		$builder->where('parent', $parent_id);
		$builder->from('posts');
		return $builder->countAllResults();
	}

	public function get_posts_nested($limit = FALSE, $offset = FALSE)
	{
		$builder  = $this->db->table('posts');
		$builder->select('*');
		$builder->where('parent is  NULL', NULL, FALSE);
		$builder->where('on_homepage', 1);

		if ($limit) {
			$builder->limit($limit, $offset);
		}
		$parent = $builder->get();

		$posts = $parent->getResult();
		$i = 0;
		foreach ($posts as $main_post) {

			$posts[$i]->sub = $this->sub_post($main_post->id);
			$i++;
		}
		return $posts;
	}

	public function sub_post($id)
	{
		$builder  = $this->db->table('posts');

		$builder->select('*');
		$builder->where('parent', $id);

		$child = $builder->get();
		$categories = $child->getResult();
		$i = 0;
		foreach ($categories as $sub_post) {

			$categories[$i]->sub = $this->sub_post($sub_post->id);
			$i++;
		}
		return $categories;
	}
}
