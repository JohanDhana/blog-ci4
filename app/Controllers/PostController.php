<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;

class PostController extends BaseController
{

	public function __construct()
	{
		helper('utils');
	}

	public function index()
	{
		$data['tags'] = [];
		$post = new Post();
		$data['title'] = 'Latest Posts';
		$data['seo_title'] = 'Home';
		$data['seo_desc'] = '';
		$data['posts'] =  $post->get_posts_nested(10, 0);
		$data['title'] = '';

		return view('templates/header', $data) .
			view('pages/home', $data) .
			view('templates/footer');
	}

	public function view($slug)
	{
		$post = new Post();

		$data['post'] = $post->get_posts_by_slug($slug);
		$data['posts'] = $post->get_posts_nested();
		$data['tags'] = explode(',', $data['post']['tags'] || '');

		if (empty($data['post'])) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data['title'] = '';
		$data['seo_title'] = $data['post']['seo_title'];
		$data['seo_desc'] = $data['post']['seo_description'];

		return	view('templates/header', $data) .
			view('posts/view', $data) .
			view('templates/footer');
	}

	public function list()
	{
		$this->user_model->check_login();
		$config["base_url"] = base_url() . "posts/list";
		$config["total_rows"] = $this->post_model->count_posts();
		$this->pagination->initialize($config);
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * $config["per_page"]) : 0;
		$data['limit'] = $config['per_page'];
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->request->getVar('page');
		$data["links"] = $this->pagination->create_links();
		$data['pages'] = $this->post_model->get_posts($config["per_page"], $offset);

		$footer_data['script'] = null;
		$data['title'] = 'Post List';

		view('templates/admin_header');
		view('admin/list_posts', $data);
		view('templates/admin_footer',	$footer_data);
	}


	public function posts_by_category($category_slug)
	{
		$data['title'] = 'Latest Posts';
		$data['seo_title'] = 'Home';
		$data['seo_desc'] = '';
		$data['tags'] = [];
		$data['posts'] =  $this->post_model->get_posts_nested(10, 0);
		$config["base_url"] = base_url() . "posts/list";
		$config["total_rows"] = $this->post_model->count_posts_by_category($category_slug);
		$this->pagination->initialize($config);
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * $config["per_page"]) : 0;
		$data['limit'] = $config['per_page'];
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->request->getVar('page');
		$data["links"] = $this->pagination->create_links();
		$data['posts_view'] = $this->post_model->posts_by_category($category_slug, $config["per_page"], $offset);

		view('templates/header', $data);
		view('posts/list-view', $data);
		view('templates/footer');
	}

	public function public_post_list()
	{
		$post = new Post();

		$data['title'] = 'Latest Posts';
		$data['seo_title'] = 'Home';
		$data['seo_desc'] = '';
		$data['tags'] = [];
		$data['posts'] =  $post->get_posts_nested(10, 0);
		$config["per_page"] = 20;
		$config["total_rows"] = $post->count_posts();
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * $config["per_page"]) : 0;
		$data['page_nr'] = 	$this->request->getVar('page');
		$data['posts_view'] = $post->select()->paginate($config['per_page']);
		$data['title'] = '';

		return view('templates/header', $data) .
			view('posts/list-view', $data) .
			view('templates/footer');
	}


	public function public_post_search()
	{
		$data['title'] = 'Latest Posts';
		$data['seo_title'] = 'Home';
		$data['seo_desc'] = '';
		$data['tags'] = [];
		$data['posts'] =  $this->post_model->get_posts_nested(10, 0);
		$config["base_url"] = base_url() . "posts";
		$config["total_rows"] = $this->post_model->count_posts_searched();
		$this->pagination->initialize($config);
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * $config["per_page"]) : 0;
		$data['limit'] = $config['per_page'];
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->request->getVar('page');
		$data["links"] = $this->pagination->create_links();
		$data['posts_view'] = $this->post_model->search_posts($config["per_page"], $offset);
		$data['title'] = '';
		$r = $this->db->last_query();
		view('templates/header', $data);
		view('posts/list-view', $data);
		view('templates/footer');
	}

	public function create()
	{
		$this->user_model->check_login();

		$data['title'] = 'Create Post';
		$data["posts"] = $this->post_model->get_posts();

		$data['categories'] = $this->post_model->get_categories();

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('body', 'Body', 'required');

		if ($this->form_validation->run() === FALSE) {
			$footer_data['script'] = null;
			view('templates/admin_header');
			view('admin/create_post', $data);
			view('templates/admin_footer', $footer_data);
		} else {
			// Upload Image
			$post_icon = $this->upload_images('post_icon');
			$post_image = $this->upload_images('post_image');
			$categories = $this->input->post('categories');
			$this->post_model->create_post($post_icon, $post_image,	$categories);
			// Set message
			$this->session->set_flashdata('post_created', 'Your post has been created');

			redirect('posts/list');
		}
	}

	public function delete($id)
	{
		$this->user_model->check_login();

		$this->post_model->delete_post($id);

		// Set message
		$this->session->set_flashdata('post_deleted', 'Your post has been deleted');

		redirect('posts/list');
	}

	public function update($id)
	{
		// Check login
		$this->user_model->check_login();

		$data['title'] = 'Update Post';
		$data["posts"] = $this->post_model->get_posts();
		$data['posts'] = array_filter($data['posts'], fn ($el) => $el['id'] !== $id);
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('body', 'Body', 'required');

		if ($this->form_validation->run() === FALSE) {
			$data['page'] = $this->post_model->get_posts_by_id($id);
			$data['page']['is_parent'] = $this->post_model->get_posts_count_with_parent($id) > 0;

			if (empty($data['page'])) {
				show_404();
			}
			$categories = $this->post_model->get_categories();
			$categories_of_post = $this->post_model->get_posts_by_id_with_categories($id);
			$data['categories'] = [];
			foreach ($categories as $category) {
				$cat_temp = array('post_id' => $id, 'category_name' => $category['name'], 'category_id' => $category['id']);
				$selected = in_array($cat_temp, $categories_of_post) ? true : false;
				$category['selected'] = $selected;
				array_push($data['categories'], $category);
			}
			$footer_data['script'] = null;
			view('templates/admin_header');
			view('admin/update_post', $data);
			view('templates/admin_footer',	$footer_data);
		} else {

			// Upload Image

			$post_icon = $this->upload_images('post_icon');
			$post_image = $this->upload_images('post_image');
			$categories = $this->input->post('categories');

			$this->post_model->update_post($id, $post_icon, $post_image, $categories);
			$this->session->set_flashdata('success', 'Your post has been updated');

			header("Refresh:0");
		}
	}

	static function fill_post_categories($category)
	{
	}

	function upload_images($image)
	{
		if ($_FILES[$image]['type']) {
			$file = explode("/", $_FILES[$image]['type']);
			$ext = explode('+', '.' . end($file))[0];
			$config = get_file_upload_config('post-img' . time() . $ext);

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload($image)) {
				$errors = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('bad_request', 'Your image was not uploaded!');
				return null;
			} else {
				$data =  $this->upload->data();
				return  $data['file_name'];
			}
		} else {
			return null;
		}
	}
}
