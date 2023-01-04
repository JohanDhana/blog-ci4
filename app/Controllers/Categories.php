<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;

class Categories extends BaseController
{
	public function __construct()
	{
		helper("form");
	}

	public function index()
	{
		$data['title'] = 'Categories';
		$data['seo_title'] = 'Categories';
		$data['seo_desc'] = 'Categories';
		$data['tags'] = [];
		$post_model = new Post();
		$category = new Category();
		$data['posts'] =  $post_model->get_posts_nested(10, 0);
		$data['categories'] = $category->paginate(25);

		return view('templates/header', $data)
			. view('categories', $data)
			. view('templates/footer');
	}

	public function createView()
	{
		$data['title'] = 'Create Category';

		$footer_data['script'] = null;
		return	view('templates/admin_header')
			. view('admin/categories/create', $data)
			. view('templates/admin_footer',	$footer_data);
	}

	public function create()
	{
		$category = new Category();
		$slug = url_title($this->request->getVar('name'), '-', TRUE);

		$data = array(
			'name' => $this->request->getVar('name'),
			'user_id' => session()->user_id,
			'slug' => $slug
		);
		$category->insert($data);
		return redirect()->route('categoryList');
	}

	public function list()
	{
		$category = new Category();
		$config["base_url"] = base_url() . "categories/list";
		$config["total_rows"] = $category->count_categories();
		$offset = ($this->request->getVar('page')) ? (($this->request->getVar('page') - 1) * 20) : 0;
		$data['limit'] = 20;
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->request->getVar('page');
		$data['categories'] = $category->paginate(20);
		$data["pager"] = $category->pager;

		$footer_data['script'] = null;
		$data['title'] = 'Categories List';

		return	view('templates/admin_header') .
			view('admin/categories/list', $data) .
			view('templates/admin_footer',	$footer_data);
	}

	public function updateView($id)
	{
		$category = new Category();

		$data['title'] = 'Update Category';
		$data['category'] = $category->find($id);
		if (empty($data['category'])) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		$footer_data['script'] = null;
		return	view('templates/admin_header') .
			view('admin/categories/update', $data) .
			view('templates/admin_footer',	$footer_data);
	}

	public function update($id)
	{
		$category = new Category();
		$data = [
			'name' => $this->request->getVar('name'),
		];
		$category->update($id, $data);

		// Set message
		session()->setFlashdata('success', 'Your category has been created');

		return redirect()->route('categoryUpdateView', [$id]);
	}

	public function delete($id)
	{
		$category = new Category();
		$category->delete($id);
		// Set message
		session()->setFlashdata('category_deleted', 'Your category has been deleted');

		return	redirect('categoryList');
	}
}
