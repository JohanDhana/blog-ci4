<?php
namespace App\Models; 

use CodeIgniter\Model;
 
class Category_model extends Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function count_categories()
	{
		return	$this->db->count_all_results('categories');
	}

	public function get_categories($limit = FALSE, $offset = FALSE)
	{
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('name');
		$query = $this->db->get('categories');
		return $query->result_array();
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

	public function update_category($id)
	{
		$data = array(
			'name' => $this->input->post('name'),
		);
		return $this->db->update('categories', $data, array('id' => $id));
	}

	public function get_category($id)
	{
		$query = $this->db->get_where('categories', array('id' => $id));
		return $query->row();
	}

	public function delete_category($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('categories');
		return true;
	}
}
