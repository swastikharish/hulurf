<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Admin_Controller {

	public function index()
	{
		if($this->auth->is_admin_logged_in())
    {
      redirect($this->admin_folder.'dashboard');
    }

		$data = $this->data;
		$data['page_title'] = 'Login';
    
    $data['redirect'] = site_url($this->admin_folder.'dashboard');
    if($this->session->flashdata('redirect') != '')
    {
      $data['redirect'] = $this->session->flashdata('redirect');
    }

		$this->partial('login', $data);
	}

	public function logout()
	{
		$this->auth->logout_admin();
		
		redirect($this->admin_folder.'login');
	}
}
