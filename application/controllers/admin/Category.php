<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Setting_model');
    $this->load->model('User_model');
    $this->load->model('Category_model');
    $this->load->model('User_activity_model');
	}

	public function directory()
	{
		if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'categories')))
    {
      redirect($this->admin_folder.'login');
    }

    $query_string = $this->input->get();

		$data = $this->data;

    $this->menu 	 = 'forum';
		$this->submenu = 'category';
		
		$data['scripts'] = array($data['app_path'].'assets/admin/js/category.js');

		$page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;
		
    $categories 			 	 = $this->Category_model->getCategories(array('limit' => $limit, 'offset' => $offset));
		$total_categories 	 = $this->Category_model->getTotalCategories();
		$data['categories'] = $categories;

		$this->load->library('pagination');
		
		$config['base_url']			= site_url($this->admin_folder.'categories');
		$config['total_rows']		= $total_categories;
		$config['per_page']			= $limit;

    if($total_categories > 0)
    {
		  $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($total_categories < ($page*$limit)) ? $total_categories : ($page*$limit)).' of '.$total_categories.' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

		$this->pagination->initialize($config);

    $this->User_activity_model->log('category_directory', "Categories Directory -> View");

		$this->view('category_directory', $data);
	}

	public function form($category_id = 0)
	{
		if($category_id > 0)
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'category/edit/'.$category_id)))
			{
				redirect($this->admin_folder.'login');
			}
		}
		else
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'category/add')))
			{
				redirect($this->admin_folder.'login');
			}
		}

    $this->menu    = 'forum';
    $this->submenu = 'category';

		$data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/category.js');

		$data['category_id'] 			 = $category_id;
    $data['name']              = '';
    $data['price']             = '';
    
		if($category_row = $this->Category_model->getCategoryData($category_id))
		{
      $data['category_id']     = $category_id;  
			$data['name']			       = $category_row['name'];
      $data['price']           = $category_row['price'];

      $this->User_activity_model->log('category_form', "Edit Category (".$category_row['name'].", ID#".$category_id.") -> Form Open");
		}
    else
    {
      $this->User_activity_model->log('category_form', "Add New Category -> Form Open");
    }

		$this->view('category_form', $data);
	}

	public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'categories')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();
    
    if(($this->input->post('name') == '') || (strlen(trim($this->input->post('name'))) == 0))
    {
      $_json['error']['name'] = 'Please enter name.';
    }
    elseif($this->Category_model->getCategoryByName(trim($this->input->post('name')), (int)$this->input->post('category_id')) > 0)
    {
      $_json['error']['name'] = 'Sorry this name has already been used.';
      $_json['message'] = 'Sorry this name has already been used.';
    }    

    if(count($_json['error']) == 0)
    {
      $save_category = array();
      $save_category['category_id']	  = $this->input->post('category_id');
      $save_category['name']          = $this->input->post('name');
      $save_category['price']         = $this->input->post('price');

      if($this->input->post('category_id') > 0)
      {
        $save_category['modified'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Category has been updated successfully.';
      }
      else
      {
        $save_category['modified'] = date('Y-m-d H:i:s');
        $save_category['created'] = date('Y-m-d H:i:s');
        $_json['message'] = 'Category has been saved successfully.';
      }

      $category_id = $this->Category_model->saveCategory($save_category);

      if($save_category['category_id'] > 0)
      {
        $this->User_activity_model->log('category_save', "Category (".$save_category['name'].", ID#".$save_category['category_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('category_save', "Category (".$save_category['name'].", ID#".$category_id.") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'categories');
    }
    
    $this->json($_json);
  }

  public function delete($category_id)
  {
  	if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'categories')))
  	{
			redirect($this->admin_folder.'login');
  	}

  	if($category_id == 0)
  	{
  		$this->session->set_flashdata('error_message', 'Invalid category ID!');
  		if($_SERVER['QUERY_STRING'] != '')
  		{
  			redirect($this->admin_folder.'categories?'.$_SERVER['QUERY_STRING']);
  		}
  		else
  		{
  			redirect($this->admin_folder.'categories');
  		}
  	}
  	else
  	{
      if($category_row = $this->Category_model->getCategoryData($category_id))
      {
  		  $this->Category_model->deleteCategory($category_id);
  		  $this->session->set_flashdata('success_message', 'Category has been removed successfully.');
        $this->User_activity_model->log('category_delete', "Category (".$category_row['name'].", ID#".$category_id.") -> Removed");
      }

  		if($_SERVER['QUERY_STRING'] != '')
  		{
  			redirect($this->admin_folder.'categories?'.$_SERVER['QUERY_STRING']);
  		}
  		else
  		{
  			redirect($this->admin_folder.'categories');
  		}
  	}
  }
}
