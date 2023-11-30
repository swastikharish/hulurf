<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends Admin_Controller {
  
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('Video_model');
    $this->load->model('User_activity_model');
  }

  public function directory()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'dashboard')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/video.js');
    
    $this->menu    = 'video';
    $this->submenu = 'video';

    $order_by = $this->input->get('order_by');
    $sort_order = $this->input->get('sort_order');

    $data['order_by'] = $order_by;
    $data['sort_order'] = $sort_order;

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'video.video_id';
    $sort_order = isset($sort_order) ? $sort_order : 'ASC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }

    $c = $this->input->get('c');
    if($c > 0)
    {
      $filter['c'] = $c;
    }

    $data['page_method'] = 'directory';

    $this->session->set_userdata(array('video_refer' => array('method' => 'directory', 'page' => $page, 'q' => $q)));

    $data['videos'] = $this->Video_model->getVideos(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));

    $data['total_video'] = $this->Video_model->getTotalVideos(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'videos');
    $config['total_rows']   = $data['total_video'];
    $config['per_page']     = $limit;
    $config['num_links']    = 10;

    if($data['total_video'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_video'] < ($page*$limit)) ? $data['total_video'] : ($page*$limit)).' of '.$data['total_video'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('video_directory', "Videos -> View");

    $this->view('video_directory', $data);
  }

  public function form($video_id = 0)
  {
    if($video_id > 0)
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'video/edit/'.$video_id)))
      {
        redirect($this->admin_folder.'login');
      }
    }
    else
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'video/add')))
      {
        redirect($this->admin_folder.'login');
      }
    }

    $data = $this->data;

    $data['plp_styles'] = array($data['app_path'].'assets/admin/libs/jquery/bootstrap-tagsinput/dist/bootstrap-tagsinput.css', $data['app_path'].'assets/admin/libs/jquery/typeaheadjs/typeaheadjs.css');
    $data['scripts'] = array($data['app_path'].'assets/admin/libs/jquery/typeaheadjs/typeahead.bundle.js', $data['app_path'].'assets/admin/libs/jquery/bootstrap-tagsinput/dist/bootstrap-tagsinput.js', $data['app_path'].'assets/admin/js/video.js');
    
    $this->menu    = 'video';
    $this->submenu = 'video';

    $data['video_id']            = 0;
    $data['type']                = '';
    $data['name']                = '';
    $data['description']         = '';
    $data['url']                 = '';
    $data['mp4']                 = '';
    $data['mp4_url']             = '';
    $data['is_active']           = 1;
    $data['category_id']         = '';

    $data['image']             = site_url('assets/admin/img/no-preview-available.png');
    $data['ext_image']         = false;
    $data['filename_image']    = '';

    if($video_row = $this->Video_model->getVideoData($video_id))
    {
      $data['video_id']           = $video_id;
      $data['type']               = $video_row['type'];
      $data['name']               = $video_row['name'];
      $data['description']        = $video_row['description'];
      $data['url']                = $video_row['youtube_video'];
      $data['mp4']                = $video_row['mp4_video'];
      $data['is_active']          = $video_row['is_active'];
      $data['category_id']        = $video_row['category_id'];

      if($video_row['type'] == 'mp4')
      {
        $data['mp4_url']          = site_url('assets/videos/'.$video_row['mp4_video']);
      }
      else
      {
        $data['mp4_url']          = '';
      }

      if(!empty($video_row['image']))
      {
        $data['ext_image'] = true;
        $data['filename_image'] = $video_row['image'];
        $data['image'] = site_url('assets/images/'.$video_row['image']);
      }

      $this->User_activity_model->log('video_form', "Edit Video (".$video_row['name'].", ID#".$video_id.") -> Form Open");
    }
    else
    {
      $this->User_activity_model->log('video_form', "Add New Video -> Form Open");
    }

    $this->view('video_form', $data);
  }

  public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'dashboard')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    $mp4 = '';

    $config['upload_path']          = 'assets/videos/';
    $config['allowed_types']        = 'mp4';
    $config['encrypt_name']         = TRUE;

    $this->load->library('upload', $config);

    if(isset($_FILES['file']) && ($_FILES['file']['size'] > 0))
    {
      $this->upload->initialize($config);

      if(!$this->upload->do_upload('file'))
      {
        $_json['error']['file'] = $this->upload->display_errors('<p><strong>Error:</strong> ');
        $_json['message'] = $this->upload->display_errors('<p><strong>Error:</strong> ');
      }
      else
      {
        $upload_details = $this->upload->data();
        $mp4 = $upload_details['file_name'];
      }
    }

    $image = '';

    $config['upload_path']          = 'assets/images/';
    $config['allowed_types']        = 'gif|jpg|png|jpeg';
    $config['encrypt_name']         = TRUE;

    $this->load->library('upload', $config);

    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0)
    {
      $this->upload->initialize($config);

      if(!$this->upload->do_upload('image'))
      {
        $_json['error']['iamge'] = $this->upload->display_errors();
      }
      else
      {
        $upload_details = $this->upload->data();

        $this->load->library('vkimage');

        $this->vkimage->correctImageOrientation('assets/images/'.$upload_details["file_name"]);

        $image_data = getimagesize('assets/images/'.$upload_details["file_name"]);
        $image_size = filesize('assets/images/'.$upload_details["file_name"]);

        if (($image_data[0] > 1024)) {
          if (round($image_size / 1024) > 1000) {
            $resize_width = 1024 - floor((1024 * 0.3));
          } else {
            $resize_width = 1024;
          }

          $config['image_library'] = 'gd2';  
          $config['source_image'] = 'assets/images/'.$upload_details["file_name"];
          $config['create_thumb'] = FALSE;  
          $config['maintain_ratio'] = FALSE;
          $config['width'] = $resize_width;

          if ($image_data['mime'] == 'image/png') {
            $config['quality'] = 5;
          } else {
            $config['quality'] = 70;
          }

          $config['width'] = $resize_width;
          $config['height'] = floor(($resize_width/$image_data[0] )*$image_data[1]);

          $config['new_image'] = 'assets/images/'.$upload_details["file_name"];
          $this->load->library('image_lib');
          $this->image_lib->clear();
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        } else if (round($image_size / 1024) > 500) {
          if (round($image_size / 1024) > 1000) {
            $resize_width = $image_data[0] - floor(($image_data[0] * 0.3));

            $config['image_library'] = 'gd2';  
            $config['source_image'] = 'assets/images/'.$upload_details["file_name"];
            $config['create_thumb'] = FALSE;  
            $config['maintain_ratio'] = FALSE;
            $config['width'] = $resize_width;

            if ($image_data['mime'] == 'image/png') {
              $config['quality'] = 7;
            } else {
              $config['quality'] = 70;
            }

            $config['width'] = $resize_width;
            $config['height'] = floor(($resize_width/$image_data[0] )*$image_data[1]); 

            $config['new_image'] = 'assets/images/'.$upload_details["file_name"];
            $this->load->library('image_lib');
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
          } else {
            $source_image = 'assets/images/'.$upload_details["file_name"];
            $image_destination = 'assets/images/'.$upload_details["file_name"];
            $compress_images = $this->vkimage->compressImage($source_image, $image_destination);
          }
        }

        $image = $upload_details['file_name'];
      }
    }

    if(!$_json && (($this->input->post('name') == '') || (strlen(trim($this->input->post('name'))) == 0)))
    {
      $_json['error']['name'] = 'Please enter name.';
    }

    if(!$_json && (($this->input->post('description') == '') || (strlen(trim($this->input->post('description'))) == 0)))
    {
      $_json['error']['description'] = 'Please enter description.';
    }


    if(!$_json && ((($this->input->post('url') == '') || (strlen(trim($this->input->post('url'))) == 0)) && empty($mp4)))
    {
      $_json['error']['video'] = 'Please upload a valid mp4 file or enter a valid youtube url.';
      $_json['message'] = 'Please upload a valid mp4 file or enter a valid youtube url.';
    }

    if(empty($_json['error']))
    {
      $save_video = array();
      $save_video['video_id']            = $this->input->post('video_id');
      $save_video['name']                = $this->input->post('name');
      $save_video['category_id']         = $this->input->post('category_id');
      $save_video['description']         = $this->input->post('description');

      if(!empty($image))
      {
        $save_video['image'] = $image;
      }
      
      if(!empty($mp4))
      {
        $save_video['type']              = 'mp4';
        $save_video['mp4_video']         = $mp4;
        $save_video['youtube_video']     = null;
      }
      elseif(!empty($this->input->post('url')))
      {
        $save_video['type']              = 'youtube';
        $save_video['youtube_video']     = $this->input->post('url');
        $save_video['mp4_video']         = null;
      }

      $save_video['is_active']           = (int)$this->input->post('is_active');

      if($this->input->post('video_id') > 0)
      {
        $save_video['modified'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Video has been updated successfully.';
      }
      else
      {
        $save_video['modified'] = date('Y-m-d H:i:s');
        $save_video['created'] = date('Y-m-d H:i:s');

        $_json['message'] = 'Video has been saved successfully.';
      }

      $video_id = $this->Video_model->saveVideo($save_video);

      if($save_video['video_id'] > 0)
      {
        $this->User_activity_model->log('video_save', "Video (".$save_video['name'].", ID#".$save_video['video_id'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('video_save', "Video (".$save_video['name'].", ID#".$video_id.") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);

      $video_refer = $this->session->userdata('video_refer');
      if($video_refer)
      {
        if($video_refer['method'] == 'library')
        {
          $_json['redirect'] = site_url($this->admin_folder.'videos/library?page='.$video_refer['page'].'&q='.$video_refer['q'].'&c='.$video_refer['c']);
        }
        else
        {
          $_json['redirect'] = site_url($this->admin_folder.'videos?page='.$video_refer['page'].'&q='.$video_refer['q']);
        }
      }
      else
      {
        $_json['redirect'] = site_url($this->admin_folder.'videos');
      }      
    }
    
    $this->json($_json);
  }

  public function delete($video_id)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'dashboard')))
    {
      redirect($this->admin_folder.'login');
    }

    if($video_id == 0)
    {
      $this->session->set_flashdata('error_message', 'Invalid video ID!');
      
      $video_refer = $this->session->userdata('video_refer');
      if($video_refer)
      {
        redirect($this->admin_folder.'videos?page='.$video_refer['page'].'&q='.$video_refer['q']);
      }
      else
      {
        redirect($this->admin_folder.'videos');
      }
    }
    else
    {
      if($video_row = $this->Video_model->getVideoData($video_id))
      {
        $this->Video_model->deleteVideo($video_id);
        $this->session->set_flashdata('success_message', 'Video has been removed successfully.');

        $this->User_activity_model->log('video_delete', "Video (".$video_row['name'].", ID#".$video_id.") -> Removed");
      }

      $video_refer = $this->session->userdata('video_refer');
      if($video_refer)
      {
        if($video_refer['method'] == 'library')
        {
          redirect($this->admin_folder.'videos/library?page='.$video_refer['page'].'&q='.$video_refer['q'].'&c='.$video_refer['c']);
        }
        else
        {
          redirect($this->admin_folder.'videos?page='.$video_refer['page'].'&q='.$video_refer['q']);
        }
      }
      else
      {
        redirect($this->admin_folder.'videos');
      }
    }
  }

  public function image_remove($id)
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'videos/library')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(($id == 0) || (!$product_data = $this->Video_model->getVideoData($id)))
    {
      $_json['error']['id'] = 'Invalid Video ID.';
      $_json['message'] = 'Invalid Video ID.';
    }

    if(empty($_json['error']))
    {
      @unlink('assets/images/'.$product_data['image']);

      $save = array();

      $save['image'] = null;
      $save['video_id'] = $id;
      $save['modified'] = date('Y-m-d H:i:s');

      $video_id = $this->Video_model->saveVideo($save);

      $this->User_activity_model->log('video_image_remove', "Video Image (ID#".$id.") -> Removed");

      $_json['message'] = 'Image removed successfully.';
      $_json['success'] = true;
    }

    $this->json($_json);
  }
}
