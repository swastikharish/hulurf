<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('Category_model');
    $this->load->model('Image_model');
    $this->load->model('Video_model');
    $this->load->model('Forum_model');
    $this->load->model('Forum_orm_model');
    $this->load->model('Topic_model');
    $this->load->model('Topic_orm_model');
    $this->load->model('Conversation_model');
    $this->load->model('User_activity_model');

    $this->load->helper('civk');
	}

  public function forum($forum_slug)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forum/'.$forum_slug)))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $this->menu    = 'forum';
    $this->submenu = 'forum';

    if($forum = $this->Forum_model->getForumBySlug($forum_slug))
    {
      $page = $this->input->get('page');

      $page  = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
      $limit = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 5;
      $offset = ($page - 1) * $limit;

      $data['forum'] = $forum;
      $data['count_topics'] = $this->Forum_orm_model->countForumTopics($forum['forum_id']);
      $data['topics'] = $this->Topic_model->getTopics(['filter' => ['forum_id' => $forum['forum_id']], 'limit' => $limit, 'offset' => $offset, 'order_by' => 'topic.topic_id', 'sort_order' => 'DESC']);

      $data['images'] = $this->Forum_orm_model->getForumImages($forum['forum_id']);
      $data['videos'] = $this->Forum_orm_model->getForumVideos($forum['forum_id']);

      if($data['count_topics'] > 0)
      {
        $this->load->library('pagination');
        
        $config['base_url']     = site_url($this->admin_folder.'forum/'.$forum_slug);
        $config['total_rows']   = $data['count_topics'];
        $config['per_page']     = $limit;

        $this->pagination->initialize($config);
      }

      $this->view('forum_page', $data);
    }
    else
    {
      $this->view('access_error', $data);
    }
  }

  public function forum_approved($forum_slug)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forum/'.$forum_slug)))
    {
      redirect($this->admin_folder.'login');
    }

    if($forum = $this->Forum_model->getForumBySlug($forum_slug))
    {
      $this->Forum_model->saveForum(['forum_id' => $forum['forum_id'], 'is_active' => 1, 'is_approved' => 1, 'approved' => date("Y-m-d H:i:s")]);

      $this->session->set_flashdata('success_message', 'Approved!');      
      redirect($this->admin_folder.'forum/'.$forum_slug);
    }
    else
    {
      $this->session->set_flashdata('success_message', 'Invalid forum ID.');
      redirect($this->admin_folder.'forums');
    }
  }

  public function topic($topic_slug)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/'.$topic_slug)))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/topic.js');

    $this->menu    = 'forum';
    $this->submenu = 'topic';

    if($topic = $this->Topic_model->getTopicBySlug($topic_slug))
    {
      $page = $this->input->get('page');

      $page  = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
      $limit = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
      $offset = ($page - 1) * $limit;

      $data['topic'] = $topic;
      $data['images'] = $this->Topic_orm_model->getTopicImages($topic['topic_id']);

      $data['count_conversations'] = $this->Topic_orm_model->countForumConversations($topic['topic_id']);
      $data['conversations'] = $this->Conversation_model->getConversations(['filter' => ['topic_id' => $topic['topic_id']], 'limit' => $limit, 'offset' => $offset, 'order_by' => 'conversation.conversation_id', 'sort_order' => 'DESC']);

      if($data['count_conversations'] > 0)
      {
        $this->load->library('pagination');
        
        $config['base_url']     = site_url($this->admin_folder.'topic/'.$topic_slug);
        $config['total_rows']   = $data['count_conversations'];
        $config['per_page']     = $limit;

        $this->pagination->initialize($config);
      }

      $this->view('topic_page', $data);
    }
    else
    {
      $this->view('access_error', $data);
    }
  }

  public function topic_approved($topic_slug)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'topic/'.$topic_slug)))
    {
      redirect($this->admin_folder.'login');
    }

    if($topic = $this->Topic_model->getTopicBySlug($topic_slug))
    {
      $this->Topic_model->saveTopic(['topic_id' => $topic['topic_id'], 'is_active' => 1, 'is_approved' => 1, 'approved' => date("Y-m-d H:i:s")]);

      $this->load->library('emailsender');
      $username = $topic['user_name'];
      $to = $topic['email'];
      $subject = 'Forum New Topic Request Approved!';
      $body = '<p>Hi '.$username.',<br>Your forum new topic request has been approved.<br>Please <a href="'.site_url('login').'"><b>Login</b></a> and check forum.<br><br>Thanks,<br>'.$this->data['app_name'].'</p>';
      $this->emailsender->send($to, $subject, $body);

      $this->session->set_flashdata('success_message', 'Approved!');      
      redirect($this->admin_folder.'topic/'.$topic_slug);
    }
    else
    {
      $this->session->set_flashdata('success_message', 'Invalid topic ID.');
      redirect($this->admin_folder.'topics');
    }
  }

  public function conversation_save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'forums')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();

    if(count($_json['error']) == 0)
    {
      $save_conversation = array();
      $save_conversation['conversation_id'] = 0;
      $save_conversation['forum_id'] = $this->input->post('forum_id');
      $save_conversation['topic_id'] = $this->input->post('topic_id');
      $save_conversation['user_id'] = $this->admin_session_data['id'];
      $save_conversation['content'] = $this->input->post('comment');
      $save_conversation['is_initial'] = 1;
      $save_conversation['is_approved'] = 1;

      $save_conversation['modified'] = date('Y-m-d H:i:s');
      $save_conversation['created'] = date('Y-m-d H:i:s');
      $conversation_id = $this->Conversation_model->saveConversation($save_conversation);

      $this->User_activity_model->log('conversation_save', "Conversation (Topic ID#".$this->input->post('topic_id').", ID#".$conversation_id.") -> Added");

      $_json['success'] = true;
      $_json['message'] = 'Comment has been saved successfully.';

      $conversation_data = $this->Conversation_model->getConversation($conversation_id);

      $_json['conversation'] = $this->partial('conversation', $conversation_data, true);
      $_json['count_conversations'] = $this->Topic_orm_model->countForumConversations($this->input->post('topic_id'));
    }
    
    $this->json($_json);
  }

  public function conversations($topic_id)
  {
    $_json = array();

    $page = $this->input->get('page');

    $page  = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 10;
    $offset = ($page - 1) * $limit;

    $data['count_conversations'] = $this->Topic_orm_model->countForumConversations($topic_id);
    $data['conversations'] = $this->Conversation_model->getConversations(['filter' => ['topic_id' => $topic_id], 'limit' => $limit, 'offset' => $offset, 'order_by' => 'topic.topic_id', 'sort_order' => 'DESC']);

    if($data['count_conversations'] > 0)
    {
      $this->load->library('pagination');
      
      $config['base_url']     = site_url($this->admin_folder.'topic/'.$forum_slug);
      $config['total_rows']   = $data['count_conversations'];
      $config['per_page']     = $limit;

      $this->pagination->initialize($config);
    }

    $this->view('topic_conversation', $data);
  }
}
