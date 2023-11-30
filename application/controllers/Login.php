<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends User_Controller {

	public function __construct() {
		parent::__construct();

		$this->layout_scripts[] = 'login.js';
		$this->data['page_name'] = 'Login';
	}

	private function is_logged_in() {
		if ($this->user_session_data) {
			redirect('/dashboard');
		}
	}

	public function logout()
	{
		$this->auth->logout_user();
    redirect('/login');
		exit();
	}

	public function login_form() {
		$this->is_logged_in();

		$data = $this->data;

		$this->view('login/form', $data);
	}

	public function login_request()
  {
    $_json = array();

    $post = $this->input->post();

    if($this->auth->is_user_logged_in())
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url('/dashboard');
      $this->json($_json);
    }

    if(!isset($post['email']) || empty($post['email']) || (strlen(trim($post['email'])) == 0))
    {
      $_json['error'] = true;
      $_json['message'] = 'Please enter email.';
    }

    if(!$_json && (!isset($post['password']) || empty($post['password']) || (strlen(trim($post['password'])) == 0)))
    {
      $_json['error'] = true;
      $_json['message'] = 'Please enter password.';
    }

    if(!$_json)
    {
      $authData = $this->auth->login_user(trim($post['email']), trim($post['password']));
      if(isset($authData['success']))
      {
        $_json['success'] = true;
        $_json['redirect'] = site_url('/forum');
      }
      else
      {
        $_json['error'] = true;
        $_json['message'] = 'The email and or password entered is incorrect. Please try again.';
      }
    }

    $this->json($_json);
  }

	public function forgot_password() {
		$this->is_logged_in();

		$data = $this->data;

		$this->view('login/forgot_password', $data);
	}
}
