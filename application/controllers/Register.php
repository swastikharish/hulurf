<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends User_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->layout_scripts[] = 'register.js';
		$this->data['page_name'] = 'Register';

		$this->load->model(['User_model','Location_model']);
	}

	private function is_logged_in()
	{
		if ($this->user_session_data) {
			redirect('/dashboard');
		}
	}

	public function success()
	{
		$this->is_logged_in();

		$data = $this->data;

		$this->view('register/success', $data);
	}

	public function form()
	{
		$this->is_logged_in();

		$data = $this->data;
		$data['scripts'] = array($data['app_path'].'assets/js/register.js');

		$this->view('register/form', $data);
	}

	public function form_save()
	{
		$_json = array();
		
		$name 							= $this->input->post('name');
		$phone 						  = $this->input->post('phone');
		$email 						  = $this->input->post('email');
		$password 				  = $this->input->post('password');
		
		if($name == '')
		{
			$_json['error'] = true;
			$_json['message'] = 'Please enter your name.';
		}
		elseif($phone == '')
		{
			$_json['error'] = true;
			$_json['message'] = 'Please enter valid phone number.';
		}
		elseif($email == '')
		{
			$_json['error'] = true;
			$_json['message'] = 'Please enter valid email.';
		}
		elseif(!$_json && $this->User_model->getUserByEmail($email))
		{
			$_json['error'] = true;
			$_json['message'] = 'This email already in use.';
		}
		elseif($password == '' || strlen($password) < 7)
		{
			$_json['error'] = true;
			$_json['message'] = 'Please enter password upto 6 char.';
		}
		
		if(!$_json)
		{
			$v_name = explode(" ", $name);

			$user_data = array();
			$user_data['access_code'] = 'U';
			$user_data['first_name']  = $v_name[0];
			$user_data['last_name']   = (isset($v_name[1]) && ($v_name[1]!='')) ? $v_name[1] : '';
			$user_data['email']       = $email;
			$user_data['phone']       = $phone;
			$user_data['password']    = password_hash($password, PASSWORD_DEFAULT);
			$user_data['is_approved'] = 1;
			$user_data['is_active']   = 1;
			$user_data['created']     = date('Y-m-d H:i:s');
			$user_data['modified']    = date('Y-m-d H:i:s');

			$user_id = $this->User_model->saveUser($user_data);

			if($user_id > 0)
			{				
				// Signup Request (User)
				// $this->load->library('emailsender');
				// $username = $user_data['first_name'];
				// $to = $user_data['email'];
				// $subject = 'Signup Request!';
				// $body = '<p>Hi '.$username.',<br>Your request is still pending. Please wait until your request is approved by admin.<br><br>It usually takes around 24 - 48 hours to approve your request.<br><br>Thanks,<br>'.$this->data['app_name'].'</p>';
				// $this->emailsender->send($to, $subject, $body);

				// Signup Request (Admin)
				// $this->load->library('emailsender');
				// $to = 'info@globaltechnosys.com';
				// $subject = 'Signup Request!';
				// $body = '<p>Hi Admin,<br>A new user signup request has been successfully registered.<br>Please review the request at <a href="'.site_url('admin/registration-request').'"><b>Users->
				// Registration Request
				// </b></a><br><br>Thanks,<br>'.$this->data['app_name'].'</p>';
				// $this->emailsender->send($to, $subject, $body);

				$user = array();
				$user['user']                 = array();
				$user['user']['id']           = $user_id;
				$user['user']['access']       = 'U';
				$user['user']['group_id']     = 2;
				$user['user']['group'] 				= 'user';

				$user['user']['name']         = $user_data['first_name'].' '.$user_data['last_name'];
				$user['user']['first_name']   = $user_data['first_name'];
				$user['user']['last_name']    = $user_data['last_name'];
				$user['user']['email']        = $user_data['email'];
				$user['user']['phone']        = $user_data['phone'];

				$this->session->set_userdata($user);

				$_json['success'] = true;
				$_json['message'] = 'Registered successfully.';
				$_json['redirect'] = site_url('/forum');
			}
			else
			{
				$_json['error'] = true;
				$_json['message'] = 'Something went wrong, please try after sometime.';
			}
		}

		$this->json($_json);
	}

	public function validate_email()
  {
    $this->load->model('User_model');

    if($customer_row = $this->User_model->checkUserByEmail($this->input->post('email')))
    {
      echo 'false';
    }
    else
    {
      echo 'true';
    }
    exit();
  }
}
