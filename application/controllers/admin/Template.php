<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends Admin_Controller {
  
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Template_model');
    $this->load->model('User_activity_model');
  }

  public function directory($template_type)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'templates')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/template.js');
    
    $this->menu    = 'setting';
    $this->submenu = 'setting';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'template.name';
    $sort_order = isset($sort_order) ? $sort_order : 'ASC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 30;
    $offset     = ($page - 1) * $limit;

    $filter = array('template_type' => $template_type);
    if($q)
    {
      $filter = array('q' => $q);
    }

    $data['templates'] = $this->Template_model->getTemplates(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));
    $data['total_template'] = $this->Template_model->getTotalTemplates(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'templates');
    $config['total_rows']   = $data['total_template'];
    $config['per_page']     = $limit;
    $config['num_links']    = 20;

    if($data['total_template'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_template'] < ($page*$limit)) ? $data['total_template'] : ($page*$limit)).' of '.$data['total_template'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('template_directory', "Templates -> View");

    $this->view('template_directory', $data);
  }

  public function form($template_id)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'template/edit/'.$template_id)))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/template.js');
    
    $this->menu    = 'setting';
    $this->submenu = 'setting';

    $data['template_id']      = 0;
    $data['name']             = '';
    $data['content']          = '';

    if($template_row = $this->Template_model->getTemplateData($template_id))
    {
      $data['template_id']      = $template_id;
      $data['name']             = $template_row['name'];
      $data['content']          = $template_row['content'];

      $this->User_activity_model->log('template_form', "Edit Template (ID#".$template_id.") -> Form Open");
    }
    else
    {
      $this->session->set_flashdata('error_message', 'Invalid template #ID.');
      redirect($this->admin_folder.'templates');
    }

    $this->view('template_form', $data);
  }

  public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'templates')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();
    
    if(($this->input->post('content') == '') || (strlen(trim($this->input->post('content'))) == 0))
    {
      $_json['error']['name'] = 'Please enter template content.';
    }

    if(empty($_json['error']))
    {
      $save_template = array();
      $save_template['template_id']     = $this->input->post('template_id');
      // $save_template['name']            = $this->input->post('name');
      $save_template['content']         = $this->input->post('content');

      if($this->input->post('template_id') > 0)
      {
        $save_template['modified'] = date('Y-m-d H:i:s');
        $_json['message'] = 'Template has been updated successfully.';
      }
      else
      {
        $save_template['modified'] = date('Y-m-d H:i:s');
        $save_template['created'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Template has been saved successfully.';
      }

      $template_id = $this->Template_model->saveTemplate($save_template);

      if($save_template['template_id'] > 0)
      {
        $this->User_activity_model->log('template_save', "Template (ID#".$save_template['template_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('template_save', "Template (ID#".$template_id.") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'templates');
    }
    
    $this->json($_json);
  }
}
