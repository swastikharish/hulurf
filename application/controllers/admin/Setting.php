<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();

		if(!$this->admin_session_data)
		{
			redirect($this->admin_folder.'login');
		}

    $this->load->model(array('Setting_model','User_activity_model','Location_model'));
	}

  //////////Application Setting //////////////
	public function index()
	{	
		$this->menu 	 = 'setting';
		$this->submenu = 'system';

    $data = $this->data;

    $this->User_activity_model->log('setting_index', "System Settings -> Open");
    $this->view('setting', $data);
	}

	public function global_form()
  {
    $this->menu    = 'setting';
    $this->submenu = 'system';

    $data = $this->data;

    $data['scripts']  = array($data['app_path'].'assets/admin/js/setting.js');

    $data['states'] = array();
    if($state_result = $this->Location_model->getStates())
    {
      foreach($state_result as $state)
      {
        $data['states'][$state->state_id] = $state->name;
      }
    }

    $application  = array();
    if($settings = $this->Setting_model->getSettings())
    {
      foreach($settings as $setting)
      {
        $application[$setting->key] = $setting->value;
      }
    }

    $data['app_name'] = (isset($application['app_name']) && $application['app_name'] != '' ) ? $application['app_name'] : '';
    $data['phone']    = (isset($application['phone']) && $application['phone'] != '' ) ? $application['phone'] : '';
    $data['email']    = (isset($application['email']) && $application['email'] != '' ) ? $application['email'] : '';
    $data['address']  = (isset($application['address']) && $application['address'] != '' ) ? $application['address'] : '';
    $data['city']     = (isset($application['city']) && $application['city'] != '' ) ? $application['city'] : '';
    $data['state_id'] = (isset($application['state_id']) && $application['state_id'] != '' ) ? $application['state_id'] : '';
    $data['zip']      = (isset($application['zip']) && $application['zip'] != '' ) ? $application['zip'] : '';

    $data['meta_keyword'] = (isset($application['meta_keyword']) && $application['meta_keyword'] != '' ) ? $application['meta_keyword'] : '';
    $data['meta_title']       = (isset($application['meta_title']) && $application['meta_title'] != '' ) ? $application['meta_title'] : '';
    $data['currency_symbol']  = (isset($application['currency_symbol']) && $application['currency_symbol'] != '' ) ? $application['currency_symbol'] : '';
    $data['meta_description'] = (isset($application['meta_description']) && $application['meta_description'] != '' ) ? $application['meta_description'] : '';
    
    
    $this->User_activity_model->log('setting_global_form', "Global Settings -> Form Open");

    $this->view('setting_global_form', $data);
  }

  public function global_form_save()
  {
    $_json = array();
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'users')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('app_name', 'Site Name', 'trim|required');
    $this->form_validation->set_rules('currency_symbol', 'Currency', 'trim|required');
    $this->form_validation->set_rules('meta_title', 'Meta Title', 'trim');
    $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim');
    $this->form_validation->set_rules('meta_keyword', 'Meta keyword', 'trim');
    $this->form_validation->set_rules('email', 'email', 'trim|required');
    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
    $this->form_validation->set_rules('address', 'Address', 'trim|required');
    $this->form_validation->set_rules('city', 'City', 'trim|required');
    $this->form_validation->set_rules('state_id', 'State', 'trim|required');
    $this->form_validation->set_rules('zip', 'Zip', 'trim|required');
    
    if($this->form_validation->run() == false)
    {
      $_json['error'] = $this->form_validation->error_array();
    }
    else
    {
      $application = array();
      if($settings = $this->Setting_model->getSettings())
      {
        foreach($settings as $setting)
        {
          $application[$setting->key] = $setting->value;
        }
      }

      $save = array();
      $save = $this->input->post();

      if(!empty($save))
      {
        if((isset($save['state_id'])) && ($save['state_id'] > 0))
        {
          if($state_row = $this->Location_model->getState($save['state_id']))
          {
            $save['state_code'] = $state_row->code;
            $save['state_name'] = $state_row->name;
          }
        }
        
        foreach($save as $key=>$value)
        {
          if((is_array($value)) && (count($value)))
          {
            $value = implode(',', $value);
          }
          if(array_key_exists($key, $application))
          {
            $update = array('value'=>$value);
            $this->Setting_model->updateSetting($key, $update);
          }
          else
          {
            $insert = array('code'=>'application', 'key'=>$key, 'value'=>$value);
            $this->db->insert('setting', $insert);
          }
        }

        $this->User_activity_model->log('setting_global_form_save', "Global Settings -> Updated");

        $_json['success'] = true;
        $_json['message'] = 'Global setting has been saved successfully.';
        //$_json['redirect'] = site_url($this->admin_folder.'setting');
      }
      else
      {
        $_json['error'] = 'Something went wrong. Please try again';
      }
    }

    $this->json($_json);
  }
}
