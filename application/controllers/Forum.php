<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends User_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->data['page_name'] = 'Forums';

		$this->load->model('Setting_model');
		$this->load->model('User_model');
    $this->load->model('Category_model');
    $this->load->model('Image_model');
    $this->load->model('Video_model');
    $this->load->model('Forum_model');
    $this->load->model('Forum_orm_model');
    $this->load->model('Topic_model');
    $this->load->model('Topic_orm_model');
    $this->load->model('Route_model');
	}

	private function is_not_logged_in()
	{
		if (!$this->user_session_data) {
			redirect('/login');
		}
	}

	public function index()
	{
		$data = $this->data;

		$categories = $this->Category_model->getCategories();

		foreach($categories as $key => $category)
		{
			$categories[$key]['forums'] = $this->Forum_model->getForums(['filter' => ['category_id' => $category['category_id']]]);
		}

		$data['categories'] = $categories;

		$this->view('forum/category', $data);
	}

	public function post($id)
	{
		// $this->is_not_logged_in();

		$data = $this->data;

		if($forum = $this->Forum_model->getForum($id))
    {
    	$category_id = $forum['category_id'];

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

			$data['forum'] = $forum;
			$data['images'] = $this->Forum_orm_model->getForumImages($forum['forum_id']);
			$data['videos'] = $this->Forum_orm_model->getForumVideos($forum['forum_id']);

			if(($forum['category_price'] == 0) || (count($subscribe_category) > 0))
			{
				$data['category_access'] = true;
				$q = $this->input->get('q');
			  $page = $this->input->get('page');

			  $filter = array();
			  $filter['forum_id'] = $id;
			  if($q)
			  {
			    $filter['q'] = $q;
			  }

			  $page  = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
			  $limit = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 5;
			  $offset = ($page - 1) * $limit;

			  $filter['is_approved'] = 1;
			  $filter['is_active'] = 1;

			  
			  $data['count_topics'] = $this->Topic_model->getTotalTopics(['filter' => $filter]);
			  $data['topics'] = $this->Topic_model->getTopics(['filter' => $filter, 'limit' => $limit, 'offset' => $offset, 'order_by' => 'topic.topic_id', 'sort_order' => 'DESC']);

			  $this->load->library('pagination', ['bootstrap_version' => 5]);
			  
			  $config['base_url']     = site_url('forum/'.$forum['slug']);
			  $config['total_rows']   = $data['count_topics'];
			  $config['per_page']     = $limit;
			  $config['attributes']		= ['class' => 'page-link'];

			  $this->pagination->initialize($config);
			}
			else
			{
				$data['category_access'] = false;

				$filter = array();
				$filter['forum_id'] = $id;
				$filter['is_approved'] = 1;
				$filter['is_active'] = 1;
								
				$data['count_topics'] = $this->Topic_model->getTotalTopics(['filter' => $filter]);
			}

      $this->view('forum/post', $data);
    }
    else
    {
      $data['heading'] = "404: Error Not Found";
			$data['message'] = "Requested page not found.";
			$this->view('errors/html/error_404', $data);
    }
	}
}