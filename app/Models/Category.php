<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'categories';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $insertID         = 0;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	protected $allowedFields    =  ['name', 'slug', 'parent_id', 'featured', 'content', 'image', 'seo_title', 'seo_description',];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';

	// Validation
	protected $validationRules = ['name' => 'required'];
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


	public function count_categories()
	{
		$builder  = $this->db->table('categories');
		return	$builder->countAllResults();
	}


	public function create_category()
	{
		$slug = url_title($this->input->post('name'), '-', TRUE);

		$data = array(
			'name' => $this->input->post('name'),
			'user_id' => $this->session->userdata('user_id'),
			'slug' => $slug
		);

		return $this->db->insert('categories', $data);
	}
}
