<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;

class PostController extends BaseController
{

	public function __construct()
	{
		helper(['utils', 'form']);
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

		$data['post'] = $post->where('slug', $slug)->first();
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
		$post = new Post();

		$total_rows = $post->countAllResults();
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * 20) : 0;
		$data['limit'] = 20;
		$data['total'] = $total_rows;
		$data['page_nr'] = 	$this->request->getVar('page');
		$data['pages'] = $post->paginate(10);
		$data['pager'] = $post->pager;

		$footer_data['script'] = null;
		$data['title'] = 'Post List';

		return	view('templates/admin_header') .
			view('admin/list_posts', $data) .
			view('templates/admin_footer',	$footer_data);
	}


	public function posts_by_category($category_slug)
	{
		$post = new Post();

		$data['title'] = 'Latest Posts';
		$data['seo_title'] = 'Home';
		$data['seo_desc'] = '';
		$data['tags'] = [];
		$data['posts'] = $post->get_posts_nested(10, 0);
		$config["base_url"] = base_url() . "posts/list";
		$config["total_rows"] = $post->count_posts_by_category($category_slug);
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * 20) : 0;
		$data['limit'] = 20;
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->request->getVar('page');
		$data['posts_view'] = $post->posts_by_category($category_slug, 20, $offset);

		return	view('templates/header', $data) .
			view('posts/list-view', $data) .
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
		$config["total_rows"] = $post->countAllResults();
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * 20) : 0;
		$data['page_nr'] = 	$this->request->getVar('page');
		$data['posts_view'] = $post->select('*')->paginate();
		$data['title'] = '';

		return view('templates/header', $data) .
			view('posts/list-view', $data) .
			view('templates/footer');
	}


	public function public_post_search()
	{
		$post = new Post();
		$query = $this->request->getVar('search_query');

		$data['title'] = 'Latest Posts';
		$data['seo_title'] = 'Home';
		$data['seo_desc'] = '';
		$data['tags'] = [];
		$data['posts'] = $post->get_posts_nested(10, 0);
		$config["base_url"] = base_url() . "posts";
		$config["total_rows"] = $post->count_posts_searched($query);
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * 20) : 0;
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->request->getVar('page');
		$data['posts_view'] = $post->search_posts($query, 20, $offset);
		$data['title'] = '';
		return	view('templates/header', $data) .
			view('posts/list-view', $data) . view('templates/footer');
	}

	public function createView()
	{
		$category = new Category();
		$post = new Post();
		$data['categories'] = $category->findAll();
		$data["posts"] = $post->findAll();
		$data['title'] = 'Create Post';

		$footer_data['script'] = null;
		return		view('templates/admin_header') .
			view('admin/create_post', $data) .
			view('templates/admin_footer', $footer_data);
	}

	public function create()
	{
		$post = new Post();
		// Upload Image
		$post_icon = $this->upload_images('post_icon');
		$post_image = $this->upload_images('post_image');
		$categories = $this->request->getVar('categories');

		$slug = url_title($this->request->getVar('title'), '-', TRUE);
		$on_homepage = $this->request->getVar('on_homepage') === 'on' ? 1 : 0;
		$slug_results =	$post->check_unique_slug(null, $slug);
		$parent = empty($this->request->getVar('parent_id')) ? null : $this->request->getVar('parent_id');
		if (count($slug_results->getResult()) === 0) {
			$data = array(
				'title' => $this->request->getVar('title'),
				'slug' => $slug,
				'on_homepage' => $on_homepage,
				'tags' => $this->request->getVar('tags'),
				'post_icon' => $post_icon,
				'post_image' => $post_image,
				'seo_title' => $this->request->getVar('seo_title'),
				'seo_description' => $this->request->getVar('seo_description'),
				'parent' => $parent,
				'body' => $this->request->getVar('body'),

				// 'user_id' => $this->session->userdata('user_id'),
			);
			if ($this->request->getVar('seo_schema')) {
				$data['seo_schema'] = $this->request->getVar('seo_schema');
			}

			$insrt = $post->insert($data);
			$post_category = array();
			foreach ($categories as $value) {
				array_push($post_category, ['post_id' => $insrt, 'category_id' => $value]);
			}
			$db = db_connect();
			$builderPC  = $db->table('post_categories');

			$builderPC->insertBatch($post_category);
		} else {
			session()->setFlashdata('bad_request', 'A page with this title already exist');
			return redirect('posts/create');
		}		// Set message
		session()->setFlashdata('post_created', 'Your post has been created');

		return redirect('postList');
	}

	public function delete($id)
	{
		$post = new Post();
		$image_file_name = $post->select('post_image')->getWhere(array('id' => $id))->getRow()->post_image;
		$icon_file_name = $post->select('post_icon')->getWhere(array('id' => $id))->getRow()->post_icon;
		$cwd = getcwd(); // save the current working directory
		$image_file_path = $cwd . "\\assets\\images\\posts\\";
		chdir($image_file_path);
		unlink($image_file_name);
		unlink($icon_file_name);
		chdir($cwd); // Restore the previous working directory
		$post->delete($id);
		// Set message
		session()->setFlashdata('post_deleted', 'Your post has been deleted');

		return redirect('postList');
	}


	public function updateView(int $id)
	{
		$post = new Post();
		$category = new Category();

		$data['title'] = 'Update Post';
		$data["posts"] = $post->findAll();
		$data['posts'] = array_filter($data['posts'], fn ($el) => $el['id'] !== $id);
		$data['page'] = $post->find($id);
		$data['page']['is_parent'] = $post->get_posts_count_with_parent($id) > 0;

		if (empty($data['page'])) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		$categories = $category->findAll();
		$categories_of_post = $post->get_posts_by_id_with_categories($id);
		$data['categories'] = [];
		foreach ($categories as $category) {
			$cat_temp = array('post_id' => $id, 'category_name' => $category['name'], 'category_id' => $category['id']);
			$category['selected'] = in_array($cat_temp, $categories_of_post);
			array_push($data['categories'], $category);
		}
		$footer_data['script'] = null;
		return view('templates/admin_header') .
			view('admin/update_post', $data) .
			view('templates/admin_footer',	$footer_data);
	}

	public function update($id)
	{
		$post = new Post();
		// Upload Image

		$post_icon = $this->upload_images('post_icon');
		$post_image = $this->upload_images('post_image');
		$categories = $this->request->getVar('categories');
		$on_homepage = $this->request->getVar('on_homepage') === 'on' ? 1 : 0;
		$parent = empty($this->request->getVar('parent_id')) ? null : $this->request->getVar('parent_id');

		$data = array(
			'title' => $this->request->getVar('title'),
			'body' => $this->request->getVar('body'),
			'seo_title' => $this->request->getVar('seo_title'),
			'seo_description' => $this->request->getVar('seo_description'),
			'parent' => $parent,
			'tags' => $this->request->getVar('tags'),
			'on_homepage' => $on_homepage,
		);

		if ($this->request->getVar('seo_schema')) {
			$data['seo_schema'] = $this->request->getVar('seo_schema');
		}

		if ($post_icon) {
			$data['post_icon'] = $post_icon;
		}
		if ($post_image) {
			$data['post_image'] = $post_image;
		}

		$post->update($id, $data);

		$db = db_connect();

		$builderPC  = $db->table('post_categories');

		$builderPC->delete(['post_id' => $id]);
		if ($categories) {
			$post_category = array();
			foreach ($categories as $category_id) {
				array_push($post_category, array('post_id' => $id, 'category_id' => $category_id));
			}
			$builderPC->insertBatch($post_category);
		}

		session()->setFlashdata('success', 'Your post has been updated');

		return	header("Refresh:0");
	}



	function upload_images($image)
	{
		$img = $this->request->getFile($image);
		if ($img->isValid()) {
			if (!$img->hasMoved()) {
				$img->move(ROOTPATH . 'public/assets/uploads/posts');
				$imgData = [
					'name' =>  $img->getName(),
					'type'  => $img->getClientMimeType()
				];
				return $imgData['name'];
			} else {
				$imgData = ['errors' => 'The file has already been moved.'];
				return	header("Refresh:0");
			}
		}
	}
}
