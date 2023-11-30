<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends User_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Page_model');
	}

	public function page($id) {
		
		$data = $this->data;

		if($page = $this->Page_model->getPage($id))
		{
			$data['page'] = $page;
			$this->view('cms/page', $data);
		}
		else
		{
			$data['heading'] = "404: Error Not Found";
			$data['message'] = "Requested page not found.";
			$this->view('errors/html/error_404', $data);
		}
	}

}