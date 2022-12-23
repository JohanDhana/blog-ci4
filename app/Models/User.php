<?php
namespace App\Models; 

use CodeIgniter\Model;
 
class User extends Model
{
	public function register($enc_password)
	{
		// User data array
		$data = array(
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'username' => $this->input->post('username'),
			'password' => $enc_password,
			'zipcode' => $this->input->post('zipcode')
		);

		// Insert user
		return $this->db->insert('users', $data);
	}

	// Log user in
	public function login($username, $password)
	{
		// Validate
		$this->db->where('username', $username);
		$this->db->where('password', $password);

		$result = $this->db->get('users');

		if ($result->num_rows() == 1) {
			return $result->row(0)->id;
		} else {
			return false;
		}
	}

	public function reset_password($username, $password)
	{
		$this->db->update('users', ['password' => $password]);
	}


	// Check username exists
	public function check_username_exists($username)
	{
		$query = $this->db->get_where('users', array('username' => $username));
		if (empty($query->row_array())) {
			return true;
		} else {
			return false;
		}
	}

	// Check email exists
	public function check_email_exists($email)
	{
		$query = $this->db->get_where('users', array('email' => $email));
		if (empty($query->row_array())) {
			return true;
		} else {
			return false;
		}
	}

	public  function check_login()
	{
		if (!$this->session->userdata('logged_in')) {
			$return_url = urlencode($this->uri->uri_string());
			redirect('users/login?return_url=' . $return_url);
		}
	}
}
