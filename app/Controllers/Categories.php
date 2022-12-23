<?php
namespace App\Controllers;
class Categories extends BaseController
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('utils');
	}


	public function index()
	{
		$data['title'] = 'Categories';
		$data['seo_title'] = 'Categories';
		$data['seo_desc'] = 'Categories';
		$data['tags'] = [];
		$data['posts'] =  $this->post_model->get_posts_nested(10, 0);
		$data['categories'] = $this->category_model->get_categories();

		$this->load->view('templates/header', $data);
		$this->load->view('categories', $data);
		$this->load->view('templates/footer');
	}

	public function create()
	{
		$data['title'] = 'Create Category';

		$this->form_validation->set_rules('name', 'Name', 'required');

		if ($this->form_validation->run() === FALSE) {
			$footer_data['script'] = null;
			$this->load->view('templates/admin_header');
			$this->load->view('admin/categories/create', $data);
			$this->load->view('templates/admin_footer',	$footer_data);
		} else {
			$this->category_model->create_category();

			// Set message
			$this->session->set_flashdata('success', 'Your category has been created');

			redirect('categories/list');
		}
	}

	public function list()
	{
		$this->user_model->check_login();
		$config = get_pagination_config();
		$config["base_url"] = base_url() . "categories/list";
		$config["total_rows"] = $this->category_model->count_categories();
		$this->pagination->initialize($config);
		$offset = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config["per_page"]) : 0;
		$data['limit'] = $config['per_page'];
		$data['total'] = $config['total_rows'];
		$data['page_nr'] = 	$this->input->get('page');
		$data["links"] = $this->pagination->create_links();
		$data['categories'] = $this->category_model->get_categories($config["per_page"], $offset);

		$footer_data['script'] = null;
		$data['title'] = 'Categories List';

		$this->load->view('templates/admin_header');
		$this->load->view('admin/categories/list', $data);
		$this->load->view('templates/admin_footer',	$footer_data);
	}

	public function update($id)
	{
		$this->user_model->check_login();
		$data['title'] = 'Update Category';

		$this->form_validation->set_rules('name', 'Name', 'required');

		if ($this->form_validation->run() === FALSE) {
			$data['category'] = $this->category_model->get_category($id);
			if (empty($data['category'])) {
				show_404();
			}
			$footer_data['script'] = null;
			$this->load->view('templates/admin_header');
			$this->load->view('admin/categories/update', $data);
			$this->load->view('templates/admin_footer',	$footer_data);
		} else {
			$this->category_model->update_category($id);

			// Set message
			$this->session->set_flashdata('success', 'Your category has been created');

			redirect('categories/list');
		}
	}

	public function delete($id)
	{
		$this->user_model->check_login();
		$this->category_model->delete_category($id);

		// Set message
		$this->session->set_flashdata('category_deleted', 'Your category has been deleted');

		redirect('categories/list');
	}
}
