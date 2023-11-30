<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends User_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		
		$data = $this->data;
		$data['heading'] = "404: Error Not Found";
		$data['message'] = "Requested page not found.";
		$this->view('errors/html/error_404', $data);
	}

}