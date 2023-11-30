<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'dashboard')))
		{
			redirect($this->admin_folder.'login');
		}

		$data = $this->data;
		
		$this->menu 	 = 'dashboard';
		$this->submenu = 'dashboard';
		
		$this->view('dashboard', $data);
	}

	public function pwd()
	{
		echo password_hash('Global123', PASSWORD_DEFAULT);
	}
}
