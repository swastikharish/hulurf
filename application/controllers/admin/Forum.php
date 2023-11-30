<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('Category_model');
    $this->load->model('Image_model');
    $this->load->model('Video_model');
    $this->load->model('Forum_model');
    $this->load->model('Forum_orm_model');
    $this->load->model('Route_model');
    $this->load->model('User_activity_model');
	}

	public function directory()
	{
		if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forums')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/forum.js');
    
    $this->menu    = 'forum';
    $this->submenu = 'forum';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'forum.title';
    $sort_order = isset($sort_order) ? $sort_order : 'ASC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter = array('q' => $q);
    }

    $data['forums'] = $this->Forum_model->getForums(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));
    $data['total_forum'] = $this->Forum_model->getTotalForums(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'forums');
    $config['total_rows']   = $data['total_forum'];
    $config['per_page']     = $limit;

    if($data['total_forum'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_forum'] < ($page*$limit)) ? $data['total_forum'] : ($page*$limit)).' of '.$data['total_forum'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('forum_directory', "Forums -> View");

    $this->view('forum_directory', $data);
	}

	public function form($forum_id = 0)
	{
		if($forum_id > 0)
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forum/edit/'.$forum_id)))
			{
				redirect($this->admin_folder.'login');
			}
		}
		else
		{
			if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forum/add')))
			{
				redirect($this->admin_folder.'login');
			}
		}

		$data = $this->data;

    $this->menu    = 'forum';
    $this->submenu = 'forum';

    $data['scripts'] = array($data['app_path'].'assets/admin/js/forum.js');

    $data['categories']  = $this->Category_model->getCategories();

    $data['forum_id']           = $forum_id;
    $data['category_id']       = '';
    $data['title']             = '';
    $data['short_description'] = '';
    $data['description']       = '';

    $data['images'] = [];
    $data['videos'] = [];

    $data['image'] = site_url('assets/admin/img/no-preview-available.png');

    if($forum_row = $this->Forum_model->getForumData($forum_id))
    {
      $data['forum_id']            = $forum_id;
      $data['category_id']        = ($forum_row['category_id'] > 0) ? $forum_row['category_id'] : '';
      $data['title']              = $forum_row['title'];
      $data['description']        = $forum_row['description'];
      $data['short_description']  = $forum_row['short_description'];

      $data['images'] = $this->Forum_orm_model->getForumImages($forum_id);
      $data['videos'] = $this->Forum_orm_model->getForumVideos($forum_id);
      
      $this->User_activity_model->log('forum_form', "Edit Forum (".$forum_row['title'].", ID#".$forum_id.") -> Form Open");
    }
    else
    {
      $this->User_activity_model->log('forum_form', "Add Forum -> Form Open");
    }

    $this->view('forum_form', $data);
	}

	public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forums')))
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

    if(($this->input->post('short_description') == '') || (strlen(trim($this->input->post('short_description'))) == 0))
    {
      $_json['error']['short_description'] = 'Please enter short description.';
    }

    if(($this->input->post('description') == '') || (strlen(trim($this->input->post('description'))) == 0))
    {
      $_json['error']['description'] = 'Please enter description.';
    }

    $image = '';

    $iconfig['upload_path']          = 'assets/images/';
    $iconfig['allowed_types']        = 'gif|jpg|png|jpeg';
    $iconfig['encrypt_name']         = TRUE;

    $this->load->library('upload', $iconfig);
    $this->load->library('vkimage');

    $this->load->library('upload', $iconfig);

    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0)
    {
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

    $mp4_video_1 = '';
    $mp4_video_2 = '';

    $vconfig['upload_path']          = 'assets/videos/';
    $vconfig['allowed_types']        = 'mp4';
    $vconfig['encrypt_name']         = TRUE;

    $this->load->library('upload', $vconfig);

    if(isset($_FILES['mp4_video_1']) && ($_FILES['mp4_video_1']['size'] > 0))
    {
      $this->upload->initialize($vconfig);

      if(!$this->upload->do_upload('mp4_video_1'))
      {
        $_json['error']['mp4_video_1'] = $this->upload->display_errors('<p><strong>Error:</strong> ');
        $_json['message'] = $this->upload->display_errors('<p><strong>Error:</strong> ');
      }
      else
      {
        $upload_details = $this->upload->data();
        $mp4_video_1 = $upload_details['file_name'];
      }
    }

    if(isset($_FILES['mp4_video_2']) && ($_FILES['mp4_video_2']['size'] > 0))
    {
      $this->upload->initialize($vconfig);

      if(!$this->upload->do_upload('mp4_video_2'))
      {
        $_json['error']['mp4_video_2'] = $this->upload->display_errors('<p><strong>Error:</strong> ');
        $_json['message'] = $this->upload->display_errors('<p><strong>Error:</strong> ');
      }
      else
      {
        $upload_details = $this->upload->data();
        $mp4_video_2 = $upload_details['file_name'];
      }
    }

    if(empty($_json['error']))
    {
      $save_forum = array();
      $save_forum['forum_id']		        = $this->input->post('forum_id');
      $save_forum['category_id']        = $this->input->post('category_id');
      $save_forum['title']              = $this->input->post('title');
      $save_forum['short_description']  = trim($this->input->post('short_description'));
      $save_forum['description']        = trim($this->input->post('description'));
      
      if($this->input->post('forum_id') > 0)
      {
        $save_forum['modified'] = date('Y-m-d H:i:s');

        if(($category_name = $this->input->post('category_name')) && !empty($category_name))
        {
          $save_category = array();
          $save_category['category_id']  = 0;
          $save_category['name'] = $category_name;
          $save_category['modified'] = date('Y-m-d H:i:s');
          $save_category['created'] = date('Y-m-d H:i:s');

          $category_id = $this->Category_model->saveCategory($save_category);
          $save_forum['category_id'] = $category_id;
        }

        $_json['message'] = 'Forum has been updated successfully.';
      }
      else
      {
        $save_forum['user_id'] = $this->admin_session_data['id'];
        $save_forum['modified'] = date('Y-m-d H:i:s');
        $save_forum['created'] = date('Y-m-d H:i:s');
        $save_forum['is_approved'] = 1;
        $save_forum['approved'] = date('Y-m-d H:i:s');

        if(($category_name = $this->input->post('category_name')) && !empty($category_name))
        {
          $save_category = array();
          $save_category['category_id']  = 0;
          $save_category['name'] = $category_name;
          $save_category['modified'] = date('Y-m-d H:i:s');
          $save_category['created'] = date('Y-m-d H:i:s');

          $category_id = $this->Category_model->saveCategory($save_category);
          $save_forum['category_id'] = $category_id;
        }

        $_json['message'] = 'Forum has been saved successfully.';
      }

      if($save_forum['forum_id'] == 0)
      {
        $forum_slug = convert_accented_characters($save_forum['title']);
        $forum_slug = url_title($forum_slug, 'dash', true);
        $forum_slug = $this->Forum_model->createForumSlug($forum_slug);
        $forum_slug = $this->Route_model->validateSlug($forum_slug);
        
        $save_forum['slug'] = $forum_slug;
        $route = array();
        $route['slug'] = $forum_slug;
        $route_id = $this->Route_model->saveRoute($route);
      }
      elseif($save_forum['forum_id'] > 0)
      {
        $forum_data = $this->Forum_model->getForumData($save_forum['forum_id']);

        $forum_slug = convert_accented_characters($save_forum['title']);
        $forum_slug = url_title($forum_slug, 'dash', true);
        $forum_slug = $this->Forum_model->createForumSlug($forum_slug, $save_forum['forum_id']);
        
        if($forum_data['route_id'] == 0)
        {
          $forum_slug = $this->Route_model->validateSlug($forum_slug);
          $save_forum['slug'] = $forum_slug;
          $route = array();      
          $route['slug'] = $forum_slug;
          $route_id = $this->Route_model->saveRoute($route);
        }
        else
        {
          $forum_slug = $this->Route_model->validateSlug($forum_slug, $forum_data['route_id']);
          $save_forum['slug'] = $forum_slug;
          $route_id = $forum_data['route_id'];
        }        
      }

      $save_forum['route_id'] = $route_id;

      $forum_id = $this->Forum_model->saveForum($save_forum);

      // Image
      if(!empty($image))
      {
        $save_image = array();
        $save_image['image_id']           = 0;
        $save_image['name']                = $save_forum['title'];
        $save_image['is_active']           = 1;
        $save_image['image']               = $image;
        $save_image['url']                 = ($this->input->post('image_url') != '') ? $this->input->post('image_url') : null;

        $save_image['modified'] = date('Y-m-d H:i:s');
        $save_image['created'] = date('Y-m-d H:i:s');

        $image_id = $this->Image_model->saveImage($save_image);
        $this->Forum_orm_model->deleteForumImages($forum_id);
        $this->Forum_orm_model->saveForumImages([['forum_id' => $forum_id, 'image_id' => $image_id]]);
      }
      elseif($this->input->post('image_id') > 0)
      {
        $save_image = array();
        $save_image['image_id']           = $this->input->post('image_id');
        $save_image['url']                 = ($this->input->post('image_url') != '') ? $this->input->post('image_url') : null;

        $save_image['created'] = date('Y-m-d H:i:s');

        $this->Image_model->saveImage($save_image);
      }

      // Video
      if(($this->input->post('mp4_video_id_1') > 0) && !empty($mp4_video_1))
      {
        $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('mp4_video_id_1'));
      }
      if(($this->input->post('youtube_url_id_1') > 0) && ($this->input->post('youtube_url_old_1') != $this->input->post('youtube_url_1')))
      {
        $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('youtube_url_id_1'));
      }
      if(($this->input->post('mp4_video_id_2') > 0) && !empty($mp4_video_2))
      {
        $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('mp4_video_id_2'));
      }
      if(($this->input->post('youtube_url_id_2') > 0) && ($this->input->post('youtube_url_old_2') != $this->input->post('youtube_url_2')))
      {
        $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('youtube_url_id_2'));
      }

      if(!empty($mp4_video_1))
      {
        $save_video = array();
        $save_video['video_id']          = 0;
        $save_video['name']              = $save_forum['title'];
        
        $save_video['type']              = 'mp4';
        $save_video['mp4_video']         = $mp4_video_1;
        $save_video['youtube_video']     = null;

        $save_video['is_active']         = 1;

        $save_video['modified'] = date('Y-m-d H:i:s');
        $save_video['created'] = date('Y-m-d H:i:s');

        $video_id = $this->Video_model->saveVideo($save_video);
        $this->Forum_orm_model->saveForumVideos([['forum_id' => $forum_id, 'video_id' => $video_id]]);

        if($this->input->post('youtube_url_id_1') > 0)
        {
          $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('youtube_url_id_1'));
        }
      }
      elseif($this->input->post('youtube_url_old_1') != $this->input->post('youtube_url_1'))
      {
        $save_video = array();
        $save_video['video_id']          = 0;
        $save_video['name']              = $save_forum['title'];
        
        $save_video['type']              = 'youtube';
        $save_video['youtube_video']     = $this->input->post('youtube_url_1');
        $save_video['mp4_video']         = null;

        $save_video['is_active']         = 1;

        $save_video['modified'] = date('Y-m-d H:i:s');
        $save_video['created'] = date('Y-m-d H:i:s');

        $video_id = $this->Video_model->saveVideo($save_video);
        $this->Forum_orm_model->saveForumVideos([['forum_id' => $forum_id, 'video_id' => $video_id]]);

        if($this->input->post('mp4_video_id_1') > 0)
        {
          $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('mp4_video_id_1'));
        }
      }

      if(!empty($mp4_video_2))
      {
        $save_video = array();
        $save_video['video_id']          = 0;
        $save_video['name']              = $save_forum['title'];
        
        $save_video['type']              = 'mp4';
        $save_video['mp4_video']         = $mp4_video_2;
        $save_video['youtube_video']     = null;

        $save_video['is_active']         = 1;

        $save_video['modified'] = date('Y-m-d H:i:s');
        $save_video['created'] = date('Y-m-d H:i:s');

        $video_id = $this->Video_model->saveVideo($save_video);
        $this->Forum_orm_model->saveForumVideos([['forum_id' => $forum_id, 'video_id' => $video_id]]);

        if($this->input->post('youtube_url_id_2') > 0)
        {
          $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('youtube_url_id_2'));
        }
      }
      elseif($this->input->post('youtube_url_old_2') != $this->input->post('youtube_url_2'))
      {
        $save_video = array();
        $save_video['video_id']          = 0;
        $save_video['name']              = $save_forum['title'];
        
        $save_video['type']              = 'youtube';
        $save_video['youtube_video']     = $this->input->post('youtube_url_2');
        $save_video['mp4_video']         = null;

        $save_video['is_active']         = 1;

        $save_video['modified'] = date('Y-m-d H:i:s');
        $save_video['created'] = date('Y-m-d H:i:s');

        $video_id = $this->Video_model->saveVideo($save_video);
        $this->Forum_orm_model->saveForumVideos([['forum_id' => $forum_id, 'video_id' => $video_id]]);

        if($this->input->post('mp4_video_id_2') > 0)
        {
          $this->Forum_orm_model->deleteForumVideos($forum_id, $this->input->post('mp4_video_id_2'));
        }
      }

      //save the route
      $route['route_id'] = $route_id;
      $route['slug']     = $save_forum['slug'];
      $route['route']    = 'forum/post/'.$forum_id;      
      $this->Route_model->saveRoute($route);

      if($save_forum['forum_id'] > 0)
      {
        $this->User_activity_model->log('forum_save', "Forum (".$save_forum['title'].", ID#".$save_forum['forum_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('forum_save', "Forum (".$save_forum['title'].", ID#".$forum_id.") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'forums');
    }
    
    $this->json($_json);
  }

  public function delete($forum_id)
  {
  	if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forums')))
  	{
			redirect($this->admin_folder.'login');
  	}

  	if($forum_id == 0)
  	{
  		$this->session->set_flashdata('error_message', 'Invalid forum!');
  		if($_SERVER['QUERY_STRING'] != '')
  		{
  			redirect($this->admin_folder.'forums?'.$_SERVER['QUERY_STRING']);
  		}
  		else
  		{
  			redirect($this->admin_folder.'forums');
  		}
  	}
  	else
  	{
      if($forum_row = $this->Forum_model->getForumData($forum_id))
      {
    		$this->Forum_model->deleteForum($forum_id);
    		$this->session->set_flashdata('success_message', 'Forum has been removed successfully.');

        $this->User_activity_model->log('forum_delete', "Forum (".$forum_row['title'].", ID#".$forum_id.") -> Removed");
      }

  		if($_SERVER['QUERY_STRING'] != '')
  		{
  			redirect($this->admin_folder.'forums?'.$_SERVER['QUERY_STRING']);
  		}
  		else
  		{
  			redirect($this->admin_folder.'forums');
  		}
  	}
  }

  public function image_remove($forum_id, $image_id)
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forum/edit/'.$forum_id)))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($forum_id == 0) || (!$forum_data = $this->Forum_model->getForumData($forum_id)))
    {
      $_json['error']['forum_id'] = 'Invalid Forum ID.';
      $_json['message'] = 'Invalid Forum ID.';
    }

    if(empty($_json['error']))
    {
      $this->db->where('forum_id', $forum_id);
      $this->db->where('image_id', $image_id);
      $this->db->delete('forum_image');

      $this->User_activity_model->log('forum_image_remove', "Forum Image Image (ID#".$forum_id.") -> Removed");

      $_json['message'] = 'Image removed successfully.';
      $_json['success'] = true;
    }

    $this->json($_json);
  }

  public function video_remove($forum_id, $video_id)
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forum/edit/'.$forum_id)))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($forum_id == 0) || (!$forum_data = $this->Forum_model->getForumData($forum_id)))
    {
      $_json['error']['forum_id'] = 'Invalid Forum ID.';
      $_json['message'] = 'Invalid Forum ID.';
    }

    if(empty($_json['error']))
    {
      $this->db->where('forum_id', $forum_id);
      $this->db->where('video_id', $video_id);
      $this->db->delete('forum_video');

      $this->User_activity_model->log('forum_video_remove', "Forum Video (ID#".$forum_id.") -> Removed");

      $_json['message'] = 'Video removed successfully.';
      $_json['success'] = true;
    }

    $this->json($_json);
  }
}
