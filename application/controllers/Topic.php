<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends User_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->data['page_name'] = 'Forums';
		$this->layout_scripts[] = 'topic.js';

		$this->load->model('Setting_model');
		$this->load->model('User_model');
    $this->load->model('Category_model');
    $this->load->model('Image_model');
    $this->load->model('Video_model');
    $this->load->model('Forum_model');
    $this->load->model('Forum_orm_model');
    $this->load->model('Topic_model');
    $this->load->model('Topic_orm_model');
    $this->load->model('Conversation_model');
    $this->load->model('Route_model');
	}

	private function is_not_logged_in()
	{
		if (!$this->user_session_data) {
			redirect('/login');
		}
	}

	public function success()
	{
		$this->is_not_logged_in();

		$data = $this->data;

		$this->view('shop/success', $data);
	}

	public function post($id)
	{
		// $this->is_not_logged_in();

		$data = $this->data;

		if($topic = $this->Topic_model->getTopic($id))
    {
      $category_id = $topic['forum_category_id'];
      $category = $this->Category_model->getCategory($category_id);

      if($this->user_session_data)
      {
        $subscriptions = $this->User_model->getUserSubscriptions(array('user_id' => $this->user_session_data['id']));

        $subscribe_category = array_filter($subscriptions, function($subscription) use($category_id) {
          return $subscription['category_id'] == $category_id;
        });
      }
      else
      {
        $subscribe_category = array();
      }

      if(($category['price'] == 0) || (count($subscribe_category) > 0))
      {
      	$page = $this->input->get('page');

        $filter = array();
        $filter['topic_id'] = $id;

        $page  = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
        $limit = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 5;
        $offset = ($page - 1) * $limit;

        $data['topic'] = $topic;
        $data['images'] = $this->Topic_orm_model->getTopicImages($topic['topic_id']);

        $data['count_conversations'] = $this->Topic_orm_model->countForumConversations($topic['topic_id']);
        $data['conversations'] = $this->Conversation_model->getConversations(['filter' => ['topic_id' => $topic['topic_id']], 'limit' => $limit, 'offset' => $offset, 'order_by' => 'conversation.conversation_id', 'sort_order' => 'DESC']);

        $this->load->library('pagination', ['bootstrap_version' => 5]);
        
        $config['base_url']     = site_url('topic/'.$topic['slug']);
        $config['total_rows']   = $data['count_conversations'];
        $config['per_page']     = $limit;
        $config['attributes']		= ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $this->view('topic/post', $data);
      }
      else
      {
        $this->view('forum/no_access', $data);
      }
    }
    else
    {
      $data['heading'] = "404: Error Not Found";
      $data['message'] = "Requested page not found.";
      $this->view('errors/html/error_404', $data);
    }
	}

  public function conversation_save()
  {
    $_json = array();

    if(!$_json)
    {
      $save_conversation = array();
      $save_conversation['conversation_id'] = 0;
      $save_conversation['forum_id'] = $this->input->post('forum_id');
      $save_conversation['topic_id'] = $this->input->post('topic_id');
      $save_conversation['user_id'] = $this->user_session_data['id'];
      $save_conversation['content'] = $this->input->post('comment');
      $save_conversation['is_initial'] = 1;

      $save_conversation['modified'] = date('Y-m-d H:i:s');
      $save_conversation['created'] = date('Y-m-d H:i:s');
      $conversation_id = $this->Conversation_model->saveConversation($save_conversation);

      $_json['success'] = true;
      $_json['message'] = 'Comment has been saved successfully.';

      $conversation_data = $this->Conversation_model->getConversation($conversation_id);

      $_json['conversation'] = $this->partial('topic/conversation', $conversation_data, true);
      $_json['count_conversations'] = $this->Topic_orm_model->countForumConversations($this->input->post('topic_id'));
    }
    
    $this->json($_json);
    exit;
  }
}