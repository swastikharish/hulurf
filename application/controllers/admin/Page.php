<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('Page_model');
    $this->load->model('Route_model');
    $this->load->model('User_activity_model');
	}

	public function directory()
	{
		if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'pages')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/page.js');
      
    $this->menu    = 'page';
    $this->submenu = 'page';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'page.title';
    $sort_order = isset($sort_order) ? $sort_order : 'ASC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter = array('q' => $q);
    }

    $data['pages'] = $this->Page_model->getPages(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));
    $data['total_page'] = $this->Page_model->getTotalPages(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'pages');
    $config['total_rows']   = $data['total_page'];
    $config['per_page']     = $limit;

    if($data['total_page'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_page'] < ($page*$limit)) ? $data['total_page'] : ($page*$limit)).' of '.$data['total_page'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('page_directory', "Pages Directory -> View");

    $this->view('page_directory', $data);
	}

	public function form($page_id = 0)
	{
		if($page_id > 0)
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'page/edit/'.$page_id)))
			{
				redirect($this->admin_folder.'login');
			}
		}
		else
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'page/add')))
			{
				redirect($this->admin_folder.'login');
			}
		}

		$data = $this->data;

    $this->menu    = 'page';
    $this->submenu = 'page';

    $data['scripts'] = array($data['app_path'].'assets/admin/js/page.js');

    $data['page_id']           = $page_id;
    $data['title']             = '';
    $data['description']       = '';

    if($page_row = $this->Page_model->getPageData($page_id))
    {
      $data['page_id']            = $page_id;
      $data['title']              = $page_row['title'];
      $data['description']        = $page_row['description'];

      $this->User_activity_model->log('page_form', "Edit Page (".$page_row['title'].", ID#".$page_id.") -> Form Open");
    }
    else
    {
      $this->User_activity_model->log('page_form', "Add Page -> Form Open");
    }

    $this->view('page_form', $data);
	}

	public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'pages')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($this->input->post('title') == '') || (strlen(trim($this->input->post('title'))) == 0))
    {
      $_json['error']['title'] = 'Please enter title.';
    }

    if(($this->input->post('description') == '') || (strlen(trim($this->input->post('description'))) == 0))
    {
      $_json['error']['description'] = 'Please enter description.';
    }  

    if(empty($_json['error']))
    {
      $save_page = array();
      $save_page['page_id']		         = $this->input->post('page_id');
      $save_page['title']              = $this->input->post('title');
      $save_page['description']        = trim($this->input->post('description'));

      if($this->input->post('page_id') > 0)
      {
        $save_page['modified'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Page has been updated successfully.';
      }
      else
      {
        $save_page['modified'] = date('Y-m-d H:i:s');
        $save_page['created'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Page has been saved successfully.';
      }

      if($save_page['page_id'] == 0)
      {
        $page_slug = convert_accented_characters($save_page['title']);
        $page_slug = url_title($page_slug, 'dash', true);
        $page_slug = $this->Page_model->createPageSlug($page_slug, $save_page['page_id']);
        $page_slug = $this->Route_model->validateSlug($page_slug);
        $save_page['slug'] = $page_slug;
        $route = array();
        $route['slug'] = $page_slug;
        $route_id = $this->Route_model->saveRoute($route);
      }
      elseif($save_page['page_id'] > 0)
      {
        $page_data = $this->Page_model->getPageData($save_page['page_id']);

        $page_slug = convert_accented_characters($save_page['title']);
        $page_slug = url_title($page_slug, 'dash', true);
        $page_slug = $this->Page_model->createPageSlug($page_slug, $save_page['page_id']);

        if($page_data['route_id'] == 0)
        {
          $page_slug = $this->Route_model->validateSlug($page_slug);          
          $route['slug'] = $page_slug;
          $route_id = $this->Route_model->saveRoute($route);
        }
        else
        {
          $page_slug = $this->Route_model->validateSlug($page_slug, $page_data['route_id']);
          $route_id = $page_data['route_id'];
        }
        
        $save_page['slug'] = $page_slug;
      }

      $save_page['route_id'] = $route_id;

      $page_id = $this->Page_model->savePage($save_page);

      if($save_page['page_id'] > 0)
      {
        $this->User_activity_model->log('page_save', "Page (".$save_page['title'].", ID#".$save_page['page_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('page_save', "Page (".$save_page['title'].", ID#".$page_id.") -> Added");
      }

      //save the route
      $route['route_id'] = $route_id;
      $route['slug']     = $save_page['slug'];
      $route['route']    = 'cms/page/'.$page_id;      
      $this->Route_model->saveRoute($route);

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'pages');
    }
    
    $this->json($_json);
  }

  public function delete_page()
  {
    $_json = array();

    $page_id = $this->input->post('primary_value');

    if(($page_id == '') || ($page_id <=0))
    {
      $_json['error'] = true;
      $_json['message'] = 'Something went wrong!';
    }

    if(!$_json)
    {
      $update_page = array();
      $update_page['is_deleted'] = 1;
      $update_page['page_id']    = $page_id;


      $page_id = $this->Page_model->savePage($update_page);

      if($page_id > 0)
      {
        $this->User_activity_model->log('delete_page', "Page ID#".$update_page['page_id'].") -> Delete");

        $_json['success'] = true;
        $_json['message'] = 'Page deleted successfully';
        $this->session->set_flashdata('success_message', $_json['message']);
      }
      else
      {
        $_json['error'] = true;
        $_json['message'] = 'Refresh The Page, Try Again Later!';
      }
    }

    $this->json($_json);
  }
}
