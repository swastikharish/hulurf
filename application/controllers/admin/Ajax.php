<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends Admin_Controller {
  
  public function index()
  {
    $_json = array('error' => 'Somethhing went wrong, please check your url');
    $this->json($_json);
  }

  public function login()
  {
    $_json = array();

    $post = $this->input->post();

    if($this->auth->is_admin_logged_in())
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'dashboard');
      $this->json($_json);
    }

    if(!isset($post['a_email']) || empty($post['a_email']) || (strlen(trim($post['a_email'])) == 0))
    {
      $_json['error'] = true;
      $_json['message'] = 'Please enter email.';
    }

    if(!$_json && (!isset($post['a_password']) || empty($post['a_password']) || (strlen(trim($post['a_password'])) == 0)))
    {
      $_json['error'] = true;
      $_json['message'] = 'Please enter password.';
    }

    if(!$_json)
    {
      if($this->auth->login_admin(trim($post['a_email']), trim($post['a_password'])))
      {
        $admin = $this->session->userdata('admin');

        $action_data = array(
          'user_name' => $admin['name'],
          'user_id' => $admin['id'],
          'ip' => $this->input->ip_address(),
          'created' => date('Y-m-d H:i:s'),
          'action' => 'ajax_login',
          'message' => "Logged in at ".date('h:i a')
        );

        $this->db->insert('user_activity', $action_data);

        $_json['success'] = true;

        $_json['redirect'] = site_url($this->admin_folder.'dashboard');
        
        if($this->input->post('redirect') != '')
        {
          $_json['redirect'] = $this->input->post('redirect');
        }
      }
      elseif($this->auth->admin_email(trim($post['a_email']))) 
      {
        $_json['error'] = true;
        $_json['message'] = 'We were unable to validate the email and password combination.';
      }
      else
      {
        $_json['error'] = true;
        $_json['message'] = 'The email is invalid.';
      }
    }

    $this->json($_json);
  }

  public function update_table()
  {
    $this->load->model('User_activity_model');
    
    $_json = array();
    if(!$this->auth->is_admin_logged_in())
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    if($this->input->post('table_name') == '')
    {
      $_json['error'] = true;
      $_json['message'] = 'Please provide table name.';
    }

    if($this->input->post('primary_name') == '')
    {
      $_json['error'] = true;
      $_json['message'] = 'Please provide primary name.';
    }

    if($this->input->post('primary_value') == '')
    {
      $_json['error'] = true;
      $_json['message'] = 'Please provide primary value.';
    }

    if($this->input->post('field_name') == '')
    {
      $_json['error'] = true;
      $_json['message'] = 'Please provide field name.';
    }

    if($this->input->post('field_value') == '')
    {
      $_json['error'] = true;
      $_json['message'] = 'Please provide field value.';
    }

    if(!$_json)
    {
      $table_name = $this->input->post('table_name');
      $primary_name = $this->input->post('primary_name');
      $primary_value = $this->input->post('primary_value');
      $field_name = $this->input->post('field_name');
      $field_value = $this->input->post('field_value');

      if($this->db->table_exists($table_name))
      {
        $table_select_query = $this->db->query("SELECT * FROM ".$this->db->dbprefix($table_name)." WHERE ".$primary_name." = ".$primary_value." ");
        if($table_select_row = $table_select_query->row_array())
        {
          if ($field_value == 0) {
            $action_message = "User ".$table_select_row['first_name']." ".$table_select_row['last_name']." Inactive";
          } else {
            $action_message = "User ".$table_select_row['first_name']." ".$table_select_row['last_name']." Active";
          }

          $this->User_activity_model->log('ajax_update_table', $action_message);

          $table_update_query = $this->db->query("UPDATE ".$this->db->dbprefix($table_name)." SET ".$field_name." = ".$field_value." WHERE ".$primary_name." = ".$primary_value." ");
          if($table_update_query)
          {
            $_json['success'] = true;
            $_json['message'] = ucwords(str_replace('_', ' ', $field_name)).' has been updated successfully.';
            $this->session->set_flashdata('success_message', $_json['message']);
          }
          else
          {
            $_json['error'] = true;
            $_json['message'] = 'Please provide correct field data.';
          }
        }
      }
      else
      {
        $_json['error'] = true;
        $_json['message'] = 'Please provide correct table.';
      }
    }

    $this->json($_json);
  }
}
