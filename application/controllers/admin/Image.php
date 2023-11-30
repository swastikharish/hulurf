<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends Admin_Controller {
  
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('Image_model');
    $this->load->model('User_activity_model');
  }

  public function directory()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'images')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/image.js');
    
    $this->menu    = 'setting';
    $this->submenu = 'setting';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'image.name';
    $sort_order = isset($sort_order) ? $sort_order : 'ASC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter = array('q' => $q);
    }

    $data['images'] = $this->Image_model->getImages(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));
    $data['total_image'] = $this->Image_model->getTotalImages(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'images');
    $config['total_rows']   = $data['total_image'];
    $config['per_page']     = $limit;
    $config['num_links']    = 10;

    if($data['total_image'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_image'] < ($page*$limit)) ? $data['total_image'] : ($page*$limit)).' of '.$data['total_image'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('image_directory', "Images -> View");

    $this->view('image_directory', $data);
  }

  public function form($image_id = 0)
  {
    if($image_id > 0)
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'image/edit/'.$image_id)))
      {
        redirect($this->admin_folder.'login');
      }
    }
    else
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'image/add')))
      {
        redirect($this->admin_folder.'login');
      }
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/image.js');
    
    $this->menu    = 'setting';
    $this->submenu = 'setting';

    $data['image_id']        = 0;
    $data['name']             = 'Image';
    $data['description']      = '';
    $data['start_date']       = '';
    $data['end_date']         = '';
    $data['is_active']        = 1;
    $data['image']            = site_url('assets/admin/img/no-preview-available.png');
    $data['url']              = '';
    $data['ext_image']        = false;

    if($image_row = $this->Image_model->getImageData($image_id))
    {
      $data['image_id']          = $image_id;
      $data['name']               = $image_row['name'];
      $data['description']        = $image_row['description'];
      $data['url']                = $image_row['url'];

      if(!empty($image_row['start_date']) && !empty($image_row['end_date']))
      {
        $sdDateTime = new DateTime($image_row['start_date']);
        $data['start_date']       = $sdDateTime->format('m/d/Y');

        $edDateTime = new DateTime($image_row['end_date']);
        $data['end_date']         = $edDateTime->format('m/d/Y');
      }

      $data['is_active']        = $image_row['is_active'];

      if(!empty($image_row['image']))
      {
        $data['ext_image'] = true;
        $data['image'] = site_url('assets/images/'.$image_row['image']);
      }

      $this->User_activity_model->log('image_form', "Edit Image (ID#".$image_id.") -> Form Open");
    }
    else
    {
      $this->User_activity_model->log('image_form', "Add New Image -> Form Open");
    }

    $this->view('image_form', $data);
  }

  public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'images')))
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

    // if(($this->input->post('start_date') == '') || (strlen(trim($this->input->post('start_date'))) == 0))
    // {
    //   $_json['error']['start_date'] = 'Please select start date.';
    // }

    // if(($this->input->post('end_date') == '') || (strlen(trim($this->input->post('end_date'))) == 0))
    // {
    //   $_json['error']['end_date'] = 'Please select end date.';
    // }

    $image = '';

    $config['upload_path']          = 'assets/images/';
    $config['allowed_types']        = 'gif|jpg|png|jpeg';
    $config['encrypt_name']         = TRUE;

    $this->load->library('upload', $config);
    $this->load->library('vkimage');

    $this->load->library('upload', $config);

    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0)
    {
      $this->upload->initialize($config);

      if(!$this->upload->do_upload('image'))
      {
        $_json['error']['iamge'] = $this->upload->display_errors('<p><strong>Image Image:</strong> ');
        $_json['message'] = $this->upload->display_errors('<p><strong>Image Image:</strong> ');
      }
      else
      {
        $upload_details = $this->upload->data();

        $this->vkimage->correctImageOrientation('assets/images/'.$upload_details["file_name"]);

        $image = $upload_details['file_name'];
      }
    }

    if(((int)$this->input->post('image_id') == 0) && empty($image))
    {
      $_json['error']['iamge'] = 'Please upload image image.';
      $_json['message'] = 'Please upload image image.';
    }

    if(empty($_json['error']))
    {
      $save_image = array();
      $save_image['image_id']           = $this->input->post('image_id');
      $save_image['name']                = $this->input->post('name');
      $save_image['description']         = (($description = $this->input->post('description')) && !empty($description)) ? $description : NULL;

      $save_image['is_active']           = (int)$this->input->post('is_active');
      // $sdDateTime = new DateTime($this->input->post('start_date'));
      // $save_image['start_date']          = $sdDateTime->format('Y-m-d');

      // $edDateTime = new DateTime($this->input->post('end_date'));
      // $save_image['end_date']            = $edDateTime->format('Y-m-d');

      if(!empty($image))
      {
        $save_image['image'] = $image;
      }

      if($this->input->post('image_id') > 0)
      {
        $save_image['modified'] = date('Y-m-d H:i:s');
        $_json['message'] = 'Image has been updated successfully.';
      }
      else
      {
        $save_image['modified'] = date('Y-m-d H:i:s');
        $save_image['created'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Image has been saved successfully.';
      }

      $image_id = $this->Image_model->saveImage($save_image);

      if($save_image['image_id'] > 0)
      {
        $this->User_activity_model->log('image_save', "Image (ID#".$save_image['image_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('image_save', "Image (ID#".$image_id.") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'images');
    }
    
    $this->json($_json);
  }

  public function delete($image_id)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'images')))
    {
      redirect($this->admin_folder.'login');
    }

    if($image_id == 0)
    {
      $this->session->set_flashdata('error_message', 'Invalid image ID!');
      if($_SERVER['QUERY_STRING'] != '')
      {
        redirect($this->admin_folder.'images?'.$_SERVER['QUERY_STRING']);
      }
      else
      {
        redirect($this->admin_folder.'images');
      }
    }
    else
    {
      if($image_row = $this->Image_model->getImageData($image_id))
      {
        $this->Image_model->deleteImage($image_id);
        $this->session->set_flashdata('success_message', 'Image has been removed successfully.');
        $this->User_activity_model->log('image_delete', "Image (ID#".$image_id.") -> Removed");
      }

      if($_SERVER['QUERY_STRING'] != '')
      {
        redirect($this->admin_folder.'images?'.$_SERVER['QUERY_STRING']);
      }
      else
      {
        redirect($this->admin_folder.'images');
      }
    }
  }

  public function image_remove($id)
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'images')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($id == 0) || (!$meal_data = $this->Image_model->getImageData($id)))
    {
      $_json['error']['id'] = 'Invalid Image ID.';
      $_json['message'] = 'Invalid Image ID.';
    }

    if(empty($_json['error']))
    {
      @unlink('assets/images/'.$meal_data['image']);

      $save = array();

      $save['image'] = null;
      $save['image_id'] = $id;
      $save['modified'] = date('Y-m-d H:i:s');

      $image_id = $this->Image_model->saveImage($save);

      $this->User_activity_model->log('image_image_remove', "Image Image (ID#".$image_id.") -> Removed");

      $_json['message'] = 'Image removed successfully.';
      $_json['success'] = true;
    }

    $this->json($_json);
  }
}
