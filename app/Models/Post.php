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
	protected $allowedFields    = ['title', 'slug', 'post_image', 'post_icon', 'body', 'seo_title', 'seo_description', 'on_homepage', 'parent'];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';

	// Validation
	protected $validationRules      =    ['title' => 'required', 'body ' => 'required',];
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
		return $query->getResultArray();
	}

	function check_unique_slug($id, $slug)
	{
		$builder  = $this->db->table('posts');

		$builder->where('slug', $slug);

		if ($id) {
			$builder->whereNotIn('id', $id);
		}
		return $builder->get();
	}


	public function  get_posts_count_with_parent($parent_id)
	{
		$builder  = $this->db->table('posts');
		$builder->where('parent', $parent_id);
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
