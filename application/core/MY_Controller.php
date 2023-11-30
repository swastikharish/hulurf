<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();

		//kill any references to the following methods
		$mthd = $this->router->method;
		if($mthd == 'view' || $mthd == 'partial')
		{
			show_404();
		}

		$this->db->where('code', 'application');
		$application_setting = $this->db->get('setting');
		
		foreach($application_setting->result() as $setting)
		{
			$this->config->set_item($setting->key, $setting->value);
		}

		//if SSL is enabled in config force it here.
    if (config_item('ssl_support') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off'))
		{
			$CI =& get_instance();
			$CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
			redirect($CI->uri->uri_string());
		}

    date_default_timezone_set('America/Chicago');
	}
}

class Admin_Controller extends Base_Controller {

	var $admin_session_data = [];
	var $admin_folder = '';
	var $data = [];

	var $success_message  = null;
	var $error_message    = null;
	var $warning_message  = null;
	var $info_message     = null;

	var $menu 	 = 'dashboard';
	var $submenu = 'dashboard';

	function __construct()
	{
		parent::__construct();

		if($this->auth->is_admin_logged_in())
		{
			$this->admin_session_data = $this->session->userdata('admin');
		}

		$this->admin_folder 		= $this->config->item('admin_folder');
		$this->data['app_path'] = $this->config->item('app_path');
		$this->data['app_name'] = $this->config->item('app_name');
		

		$this->success_message  = $this->session->flashdata('success_message');
		$this->error_message    = $this->session->flashdata('error_message');
		$this->warning_message  = $this->session->flashdata('warning_message');
		$this->info_message     = $this->session->flashdata('info_message');
	}

	function view($view, $vars = [], $string=false)
	{
		if($string)
		{
			$result	 = $this->load->view($this->admin_folder.'/header', $vars, true);
			$result	.= $this->load->view($this->admin_folder.'/'.$view, $vars, true);
			$result	.= $this->load->view($this->admin_folder.'/footer', $vars, true);
			
			return $result;
		}
		else
		{
			$this->load->view($this->admin_folder.'/header', $vars);
			$this->load->view($this->admin_folder.'/'.$view, $vars);
			$this->load->view($this->admin_folder.'/footer', $vars);
		}
	}
	
	function partial($view, $vars = [], $string=false)
	{
		if($string)
		{
			return $this->load->view($this->admin_folder.'/'.$view, $vars, true);
		}
		else
		{
			$this->load->view($this->admin_folder.'/'.$view, $vars);
		}
	}

	function json($json)
	{
		header('Content-Type: application/json', true);
		die(json_encode($json));
	}
}

class User_Controller extends Base_Controller {

  var $data = [];
  var $user_session_data = [];
  var $welcome_message  = null;
  var $success_message  = null;
  var $error_message    = null;
  var $warning_message  = null;
  var $info_message     = null;

  var $layout_styles = [];
  var $layout_scripts = [];

  function __construct()
  {
    parent::__construct();

    $this->data['app_path'] = $this->config->item('app_path');
    $this->data['app_name'] = $this->config->item('app_name');
    $this->data['meta_title'] = $this->config->item('meta_title');
    $this->data['meta_description'] = $this->config->item('meta_description');
    $this->data['meta_keyword'] = $this->config->item('meta_keyword');
    $this->data['assets_path'] = $this->data['app_path'].'assets';
    $this->data['page_name'] = 'Home';
    
    $this->welcome_message  = $this->session->flashdata('welcome_message');
    $this->success_message  = $this->session->flashdata('success_message');
    $this->error_message    = $this->session->flashdata('error_message');
    $this->warning_message  = $this->session->flashdata('warning_message');
    $this->info_message     = $this->session->flashdata('info_message');

    if($this->auth->is_user_logged_in())
    {
      $this->user_session_data = $this->session->userdata('user');
    }
  }

  function view($view, $vars = [], $string=false)
  {
    if($string)
    {
      $result   = $this->load->view('layout/header', $vars, true);
      $result  .= $this->load->view($view, $vars, true);
      $result  .= $this->load->view('layout/footer', $vars, true);
      return $result;
    }
    else
    {
      $this->load->view('layout/header', $vars);
      $this->load->view($view, $vars);
      $this->load->view('layout/footer', $vars);
    }
  }
  
  function partial($view, $vars = [], $string=false)
  {
    if($string)
    {
      return $this->load->view($view, $vars, true);
    }
    else
    {
      $this->load->view($view, $vars);
    }
  }

  function json($json)
  {
    header('Content-Type: application/json', true);
    die(json_encode($json));
  }
}