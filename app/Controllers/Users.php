<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
	use ResponseTrait;

	private User $user;
	public $staticPages;
	private $session;
	/**
	 * constructor
	 */
	public function __construct()
	{
		helper(['form', 'url', 'session']);
		$this->user = new User();
		$this->session = session();
	}

	/**
	 * register
	 */
	public function register($validators = [])
	{
		if ($this->session->get('loggedIn')) return redirect('postList');
		$data['staticPages'] = $this->staticPages;
		$data['validators'] = $validators;
		return view('templates/header')
			. view('register', $data)
			. view('templates/footer');
	}

	/**
	 * register
	 */
	public function create()
	{
		$this->enforcePost();

		$inputs = $this->validate([
			'name' => 'required|min_length[5]',
			'email' => 'required|valid_email|is_unique[users.email]',
			'password' => 'required|min_length[5]'
		]);

		if (!$inputs) {
			return $this->register([
				'validation' => $this->validator
			]);
		}

		$this->user->save([
			'name' => $this->request->getVar('name'),
			'email'  => $this->request->getVar('email'),
			'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
		]);
		session()->setFlashdata('success', 'Success! registration completed.');
		return redirect()->to(site_url('/register'));
	}

	/**
	 * login form
	 */
	public function login($validators = [])
	{
		if ($this->session->get('loggedIn')) return redirect('postList');
		$data['returnUrl'] = $this->request->getVar('return_url');
		$data['seo_title'] = 'Login';
		$data['validators'] = $validators;
		return view('users/login', $data)
			. view('templates/footer');
	}

	/**
	 * login validate
	 */
	public function loginValidate()
	{
		$this->enforcePost();

		$inputs = $this->validate([
			'username' => 'required',
			'password' => 'required|min_length[4]'
		]);

		if (!$inputs) {
			return $this->login([
				'validation' => $this->validator
			]);
		}

		$username = $this->request->getVar('username');
		$password = $this->request->getVar('password');

		$user = $this->user->where('username', $username)->first();
		$returnUrl = $this->request->getVar('return_url');
		if ($user) {

			$pass = $user['password'];
			$authPassword = password_verify($password, $pass);

			if ($authPassword) {
				$sessionData = [
					'id' => $user['id'],
					'username' => $user['username'],
					'loggedIn' => true,
				];

				$this->session->set($sessionData);
				if ($returnUrl)
					return redirect()->to($returnUrl);

				return redirect('postList');
			}

			session()->setFlashdata('failed', 'Failed! incorrect password');
			return redirect()->to(site_url('/login'));
		}

		session()->setFlashdata('failed', 'Failed! incorrect email');
		return redirect()->to(site_url('/login'));
	}

	/**
	 * User logout
	 * @param NA
	 */
	public function logout()
	{
		session()->destroy();
		return redirect()->to('login');
	}

	private function enforcePost()
	{
		if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
			return $this->response->setStatusCode(405)->setBody('Method Not Allowed');
		}
	}
}
