<?php
namespace App\Controllers;
class Users extends BaseController
{
	// Register user
	public function register()
	{
		$data['title'] = 'Sign Up';

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
		$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email_exists');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');

		if ($this->form_validation->run() === FALSE) {
			// $this->load->view('templates/header');
			$this->load->view('users/register', $data);
			// $this->load->view('templates/footer');
		} else {
			// Encrypt password
			$enc_password =  hash('sha256', $this->input->post('password'));

			$this->user_model->register($enc_password);

			// Set message
			$this->session->set_flashdata('user_registered', 'You are now registered and can log in');

			redirect('posts');
		}
	}

	// Log in user
	public function login()
	{
		$data['title'] = 'Sign In';
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$data['old_url'] = urldecode($this->input->get('return_url', TRUE));

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('users/login', $data);
		} else {

			// Get username
			$username = $this->input->post('username');
			// Get and encrypt the password
			$password =  hash('sha256', $this->input->post('password'));


			// Login user
			$user_id = $this->user_model->login($username, $password);

			if ($user_id) {
				// Create session
				$user_data = array(
					'user_id' => $user_id,
					'username' => $username,
					'logged_in' => true
				);

				$this->session->set_userdata($user_data);

				// Set message
				$this->session->set_flashdata('success', 'You are now logged in');
				if ($this->input->post('redirect_url')) {
					redirect($this->input->post('redirect_url'));
				} else {
					redirect('posts/list');
				}
			} else {
				// Set message
				$this->session->set_flashdata('bad_request', 'Login is invalid');

				redirect('users/login');
			}
		}
	}

	// Log user out
	public function logout()
	{
		// Unset user data
		session_destroy();


		// Set message
		$this->session->set_flashdata('success', 'You are now logged out');

		redirect('users/login');
	}

	function reset_password()
	{
		if (!$this->session->userdata('logged_in')) {
			redirect('users/login');
		}
		$data['title'] = 'Reset Password';
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('new_password', 'New Password', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('users/reset_password', $data);
		} else {
			// Get username
			$username = $this->session->userdata('username');

			// Get and encrypt the password
			$password = hash('sha256', $this->input->post('password'));

			$new_password = hash('sha256', $this->input->post('new_password'));

			// Login user
			$user = $this->user_model->login($username, $password);
			$user_id = $user['id'];
			$this->user_model->reset_password($username, $new_password);
			if ($user_id) {
				$this->session->set_flashdata('success', 'Password reset');
				redirect('posts/list');
			} else {
				// Set message
				$this->session->set_flashdata('bad_request', 'Credentials are invalid');
				redirect('users/reset-password');
			}
		}
	}


	// Check if username exists
	public function check_username_exists($username)
	{
		$this->form_validation->set_message('bad_request', 'That username is taken. Please choose a different one');
		if ($this->user_model->check_username_exists($username)) {
			return true;
		} else {
			return false;
		}
	}

	// Check if email exists
	public function check_email_exists($email)
	{
		$this->form_validation->set_message('bad_request', 'That email is taken. Please choose a different one');
		if ($this->user_model->check_email_exists($email)) {
			return true;
		} else {
			return false;
		}
	}
}
