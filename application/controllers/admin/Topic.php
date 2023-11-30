<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('Image_model');
    $this->load->model('Forum_model');
    $this->load->model('Topic_model');
    $this->load->model('Topic_orm_model');
    $this->load->model('Route_model');
    $this->load->model('User_activity_model');
	}

	public function directory()
	{
		if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topics')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/topic.js');
    
    $this->menu    = 'forum';
    $this->submenu = 'topic';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'topic.title';
    $sort_order = isset($sort_order) ? $sort_order : 'ASC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter = array('q' => $q);
    }

    $data['topics'] = $this->Topic_model->getTopics(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));
    $data['total_topic'] = $this->Topic_model->getTotalTopics(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'topics');
    $config['total_rows']   = $data['total_topic'];
    $config['per_page']     = $limit;

    if($data['total_topic'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_topic'] < ($page*$limit)) ? $data['total_topic'] : ($page*$limit)).' of '.$data['total_topic'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('topic_directory', "Topics -> View");

    $this->view('topic_directory', $data);
	}

	public function form($forum_id = 0, $topic_id = 0)
	{
		if($topic_id > 0)
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/edit/'.$forum_id.'/'.$topic_id)))
			{
				redirect($this->admin_folder.'login');
			}
		}
		elseif($forum_id > 0)
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/add/'.$forum_id)))
			{
				redirect($this->admin_folder.'login');
			}
		}
    else
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/add')))
      {
        redirect($this->admin_folder.'login');
      }
    }

		$data = $this->data;

    $this->menu    = 'forum';
    $this->submenu = 'topic';

    $data['scripts'] = array($data['app_path'].'assets/admin/js/topic.js');

    if($forum_id == 0)
    {
      $data['forums']  = $this->Forum_model->getForums();
    }
    else
    {
      $data['forums']  = [];
    }

    $data['topic_id']          = $topic_id;
    $data['forum_id']          = $forum_id;
    $data['title']             = '';
    $data['description']       = '';
    $data['pdf']                = '';
    $data['ext_pdf']            = false;

    $data['images'] = [];

    $data['image'] = site_url('assets/admin/img/no-preview-available.png');

    if($topic_row = $this->Topic_model->getTopicData($topic_id))
    {
      $data['topic_id']           = $topic_id;
      $data['title']              = $topic_row['title'];
      $data['description']        = $topic_row['description'];

      $data['images'] = $this->Topic_orm_model->getTopicImages($topic_id);

      if(!empty($topic_row['pdf']))
      {
        $data['ext_pdf'] = true;
        $data['pdf'] = site_url('assets/documents/'.$topic_row['pdf']);
      }
      
      $this->User_activity_model->log('topic_form', "Edit Topic (".$topic_row['title'].", ID#".$topic_id.") -> Form Open");
    }
    else
    {
      $this->User_activity_model->log('topic_form', "Add Topic -> Form Open");
    }

    $this->view('topic_form', $data);
	}

	public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topics')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if($this->input->post('forum_id') == 0)
    {
      $_json['error']['forum_id'] = 'Please select forum to create topic.';
    }

    if(($this->input->post('title') == '') || (strlen(trim($this->input->post('title'))) == 0))
    {
      $_json['error']['title'] = 'Please enter title.';
    }

    if(($this->input->post('description') == '') || (strlen(trim($this->input->post('description'))) == 0))
    {
      $_json['error']['description'] = 'Please enter description.';
    }

    $image = '';

    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0)
    {
      $iconfig['upload_path']          = 'assets/images/';
      $iconfig['allowed_types']        = 'gif|jpg|png|jpeg';
      $iconfig['encrypt_name']         = TRUE;

      $this->load->library('upload', $iconfig);
      $this->load->library('vkimage');

      $this->load->library('upload', $iconfig);

      $this->upload->initialize($iconfig);

      if(!$this->upload->do_upload('image'))
      {
        $_json['error']['iamge'] = $this->upload->display_errors('<p><strong>Image Image:</strong> ');
        $_json['message'] = $this->upload->display_errors('<p><strong>Image Image:</strong> ');
      }
      else
      {
        $upload_details = $this->upload->data();

        $image = $upload_details['file_name'];
      }
    }

    $pdf = '';

    if(isset($_FILES['pdf']) && $_FILES['pdf']['size'] > 0)
    {
      $iconfig['upload_path']          = 'assets/documents/';
      $iconfig['allowed_types']        = 'pdf';
      $iconfig['encrypt_name']         = TRUE;

      $this->load->library('upload', $iconfig);
      $this->load->library('vkimage');

      $this->load->library('upload', $iconfig);
    
      $this->upload->initialize($iconfig);

      if(!$this->upload->do_upload('pdf'))
      {
        $_json['error']['pdf'] = $this->upload->display_errors();
      }
      else
      {
        $upload_details = $this->upload->data();

        $pdf = $upload_details['file_name'];
      }
    }

    if(empty($_json['error']))
    {
      $save_topic = array();
      $save_topic['topic_id']		        = $this->input->post('topic_id');
      $save_topic['forum_id']           = $this->input->post('forum_id');
      $save_topic['title']              = $this->input->post('title');
      $save_topic['description']        = $this->input->post('description');

      if(!empty($pdf))
      {
        $save_topic['pdf'] = $pdf;
      }
      
      if($this->input->post('topic_id') > 0)
      {
        $save_topic['modified'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Topic has been updated successfully.';
      }
      else
      {
        $save_topic['user_id'] = $this->admin_session_data['id'];
        $save_topic['modified'] = date('Y-m-d H:i:s');
        $save_topic['created'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Topic has been saved successfully.';
      }

      if($save_topic['topic_id'] == 0)
      {
        $topic_slug = convert_accented_characters($save_topic['title']);
        $topic_slug = url_title($topic_slug, 'dash', true);
        $topic_slug = $this->Topic_model->createTopicSlug($topic_slug, $save_topic['topic_id']);
        $topic_slug = $this->Route_model->validateSlug($topic_slug);
        $save_topic['slug'] = $topic_slug;
        $route = array();
        $route['slug'] = $topic_slug;
        $route_id = $this->Route_model->saveRoute($route);
      }
      elseif($save_topic['topic_id'] > 0)
      {
        $topic_data = $this->Topic_model->getTopicData($save_topic['topic_id']);

        $topic_slug = convert_accented_characters($save_topic['title']);
        $topic_slug = url_title($topic_slug, 'dash', true);
        $topic_slug = $this->Topic_model->createTopicSlug($topic_slug, $save_topic['topic_id']);

        if($topic_data['route_id'] == 0)
        {
          $topic_slug = $this->Route_model->validateSlug($topic_slug);          
          $route['slug'] = $topic_slug;
          $route_id = $this->Route_model->saveRoute($route);
        }
        else
        {
          $topic_slug = $this->Route_model->validateSlug($topic_slug, $topic_data['route_id']);
          $route_id = $topic_data['route_id'];
        }
        
        $save_topic['slug'] = $topic_slug;
      }

      $save_topic['route_id'] = $route_id;

      $topic_id = $this->Topic_model->saveTopic($save_topic);

      // Image
      if(!empty($image))
      {
        $save_image = array();
        $save_image['image_id']           = 0;
        $save_image['name']                = $save_topic['title'];
        $save_image['is_active']           = 1;
        $save_image['image']               = $image;
        $save_image['url']                 = ($this->input->post('image_url') != '') ? $this->input->post('image_url') : null;

        $save_image['modified'] = date('Y-m-d H:i:s');
        $save_image['created'] = date('Y-m-d H:i:s');

        $image_id = $this->Image_model->saveImage($save_image);
        $this->Topic_orm_model->deleteTopicImages($topic_id);
        $this->Topic_orm_model->saveTopicImages([['topic_id' => $topic_id, 'image_id' => $image_id]]);
      }
      elseif($this->input->post('image_id') > 0)
      {
        $save_image = array();
        $save_image['image_id']           = $this->input->post('image_id');
        $save_image['url']                 = ($this->input->post('image_url') != '') ? $this->input->post('image_url') : null;

        $save_image['created'] = date('Y-m-d H:i:s');

        $this->Image_model->saveImage($save_image);
      }

      //save the route
      $route['route_id'] = $route_id;
      $route['slug']     = $save_topic['slug'];
      $route['route']    = 'topic/post/'.$topic_id;      
      $this->Route_model->saveRoute($route);

      if($save_topic['topic_id'] > 0)
      {
        $this->User_activity_model->log('topic_save', "Topic (".$save_topic['title'].", ID#".$save_topic['topic_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('topic_save', "Topic (".$save_topic['title'].", ID#".$topic_id.") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'topics');
    }
    
    $this->json($_json);
  }

  public function delete($topic_id)
  {
  	if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topics')))
  	{
			redirect($this->admin_folder.'login');
  	}

  	if($topic_id == 0)
  	{
  		$this->session->set_flashdata('error_message', 'Invalid topic!');
  		if($_SERVER['QUERY_STRING'] != '')
  		{
  			redirect($this->admin_folder.'topics?'.$_SERVER['QUERY_STRING']);
  		}
  		else
  		{
  			redirect($this->admin_folder.'topics');
  		}
  	}
  	else
  	{
      if($topic_row = $this->Topic_model->getTopicData($topic_id))
      {
    		$this->Topic_model->deleteTopic($topic_id);
    		$this->session->set_flashdata('success_message', 'Topic has been removed successfully.');

        $this->User_activity_model->log('topic_delete', "Topic (".$topic_row['title'].", ID#".$topic_id.") -> Removed");
      }

  		if($_SERVER['QUERY_STRING'] != '')
  		{
  			redirect($this->admin_folder.'topics?'.$_SERVER['QUERY_STRING']);
  		}
  		else
  		{
  			redirect($this->admin_folder.'topics');
  		}
  	}
  }

  public function image_remove($forum_id, $topic_id, $image_id)
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/edit/'.$forum_id.'/'.$topic_id)))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($topic_id == 0) || (!$topic_data = $this->Topic_model->getTopicData($topic_id)))
    {
      $_json['error']['topic_id'] = 'Invalid Topic ID.';
      $_json['message'] = 'Invalid Topic ID.';
    }

    if(empty($_json['error']))
    {
      $this->db->where('topic_id', $topic_id);
      $this->db->where('image_id', $image_id);
      $this->db->delete('topic_image');

      $this->User_activity_model->log('topic_image_remove', "Topic Image Image (ID#".$topic_id.") -> Removed");

      $_json['message'] = 'Image removed successfully.';
      $_json['success'] = true;
    }

    $this->json($_json);
  }

  public function pdf_remove($forum_id, $topic_id)
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/edit/'.$forum_id.'/'.$topic_id)))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($topic_id == 0) || (!$topic_data = $this->Topic_model->getTopicData($topic_id)))
    {
      $_json['error']['topic_id'] = 'Invalid Topic ID.';
      $_json['message'] = 'Invalid Topic ID.';
    }

    if(empty($_json['error']))
    {
      @unlink('assets/documents/'.$topic_data['pdf']);

      $save = array();

      $save['pdf'] = null;
      $save['topic_id'] = $topic_id;
      $save['modified'] = date('Y-m-d H:i:s');

      $topic_id = $this->Topic_model->saveTopic($save);

      $this->User_activity_model->log('topic_pdf_remove', "Topic PDF (ID#".$topic_id.") -> Removed");

      $_json['message'] = 'PDF removed successfully.';
      $_json['success'] = true;
    }

    $this->json($_json);
  }
}
