<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {
  
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Setting_model');
    $this->load->model('User_model');
    $this->load->model('User_activity_model');
    $this->load->model('Location_model');
  }

  public function directory()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'users')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'user';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'user.user_id';
    $sort_order = isset($sort_order) ? $sort_order : 'DESC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }
    $filter['is_approved'] = '1';

    $data['users'] = $this->User_model->getUsers(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter));
    $data['total_user'] = $this->User_model->getTotalUsers(array('filter' => $filter));

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'users');
    $config['total_rows']   = $data['total_user'];
    $config['per_page']     = $limit;

    if($data['total_user'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_user'] < ($page*$limit)) ? $data['total_user'] : ($page*$limit)).' of '.$data['total_user'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $this->pagination->initialize($config);

    $this->User_activity_model->log('user_directory', "Admin Users -> View");

    $this->view('user_directory', $data);
  }

  public function form($user_id = 0)
  {
    if($user_id > 0)
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'user/edit/'.$user_id)))
      {
        redirect($this->admin_folder.'login');
      }
    }
    else
    {
      if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'user/add')))
      {
        redirect($this->admin_folder.'login');
      }
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'user';

    $data['user_id']          = $user_id;
    $data['access_code']      = 'A';
    $data['first_name']       = '';
    $data['last_name']        = '';
    $data['email']            = '';
    $data['phone']            = '';
    $data['password']         = '';
    $data['is_active']        = '1';

    $data['groups'] = $this->User_model->getGroups();

    if($user_row = $this->User_model->getUserData($user_id))
    {
      $data['access_code']      = $user_row['access_code'];
      $data['first_name']       = $user_row['first_name'];
      $data['last_name']        = $user_row['last_name'];
      $data['email']            = $user_row['email'];
      $data['phone']            = $user_row['phone'];
      $data['is_active']        = $user_row['is_active'];

      $this->User_activity_model->log('user_form', "Edit User (".$user_row['first_name']." ".$user_row['last_name'].") -> Form Open");
    } 
    else
    {
      $this->User_activity_model->log('user_form', "Add New User -> Form Open");
    }

    $this->view('user_form', $data);
  }

  public function logs($user_id)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'users')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    if ($user_row = $this->User_model->getUserData($user_id)) {      
      $this->menu    = 'user';
      $this->submenu = 'user';

      $daterange_array = array('from' => date('Y-m-d'), 'to' => date('Y-m-d'));
      if($daterange = $this->input->get('daterange'))
      {
        $daterange_explode = explode(' - ', $daterange);
        if(count($daterange_explode) == 2)
        {
          $fromDateTime = new DateTime($daterange_explode[0].'T00:00:00');
          $toDateTime = new DateTime($daterange_explode[1].'T00:00:00');
          $daterange_array = array('from' => $fromDateTime->format('Y-m-d'), 'to' => $toDateTime->format('Y-m-d'));
          $data['daterange'] = $fromDateTime->format('m/d/Y').' - '.$toDateTime->format('m/d/Y');
        }
        else
        {
          $data['daterange'] = date('m/d/Y').' - '.date('m/d/Y');
        }
      }
      else
      {
        $data['daterange'] = date('m/d/Y').' - '.date('m/d/Y');
      }

      $page = (int)$this->input->get('page');
      $limit = (int)$this->input->get('limit');

      $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
      $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 50;
      $offset     = ($page - 1) * $limit;

      $data['user'] = $user_row;
      $data['activities'] = $this->User_model->getUserActivities(array('limit' => $limit, 'offset' => $offset, 'user_id' => $user_id, 'daterange' => $daterange_array));
      $data['total_activity'] = $this->User_model->getTotalUserActivities(array('user_id' => $user_id, 'daterange' => $daterange_array));

      $this->load->library('pagination');
      
      $config['base_url']     = site_url($this->admin_folder.'user/logs/'.$user_id);
      $config['total_rows']   = $data['total_activity'];
      $config['per_page']     = $limit;
      $config['num_links']    = 10;

      if($data['total_activity'] > 0)
      {
        $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_activity'] < ($page*$limit)) ? $data['total_activity'] : ($page*$limit)).' of '.$data['total_activity'].' items';
      }
      else
      {
        $data['pagination_string'] = '';
      }

      $this->pagination->initialize($config);

      $this->view('user_logs', $data);
    } else {
      $this->view('access_error', $data);
    }
  }

  public function save()
  {
    $_json = array();

    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'users')))
    {      
      $_json['success'] = true;
      $_json['redirect'] = site_url($this->admin_folder.'login');
      $this->json($_json);
    }

    $_json['error'] = array();
    
    if(($this->input->post('first_name') == '') || (strlen(trim($this->input->post('first_name'))) == 0))
    {
      $_json['error']['first_name'] = 'Please enter first name.';
    }

    if(($this->input->post('last_name') == '') || (strlen(trim($this->input->post('last_name'))) == 0))
    {
      $_json['error']['last_name'] = 'Please enter last name.';
    }

    if(($this->input->post('email') == '') || (strlen(trim($this->input->post('email'))) == 0))
    {
      $_json['error']['email'] = 'Please enter email.';
    }
    elseif(($this->input->post('email') != '') && ($user_row = $this->User_model->getUserByEmail($this->input->post('email'), $this->input->post('user_id'))))
    {
      $_json['error']['email'] = 'This email is already exist.';
      $_json['message'] = 'This email is already exist.';
    }

    if(($this->input->post('phone') == '') || (strlen(trim($this->input->post('phone'))) == 0))
    {
      $_json['error']['phone'] = 'Please enter phone.';
    }

    if(($this->input->post('user_id') == 0) && (($this->input->post('password') == '') || (strlen(trim($this->input->post('password'))) == 0)))
    {
      $_json['error']['password'] = 'Please enter password.';
    }

    if(empty($_json['error']))
    {
      $save_user = array();
      $save_user['user_id']             = $this->input->post('user_id');
      $save_user['access_code']         = $this->input->post('access_code');
      $save_user['first_name']          = $this->input->post('first_name');
      $save_user['last_name']           = $this->input->post('last_name');
      $save_user['email']               = $this->input->post('email');
      $save_user['phone']               = $this->input->post('phone');      
      $save_user['is_active']           = $this->input->post('is_active');

      if($this->input->post('user_id') > 0)
      {
        $save_user['modified'] = date('Y-m-d H:i:s');
        $_json['message'] = 'User has been updated successfully.';
      }
      else
      { 
        $save_user['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $save_user['modified'] = date('Y-m-d H:i:s');
        $save_user['created'] = date('Y-m-d H:i:s');
        $save_user['approved'] = date('Y-m-d H:i:s');
        $save_user['is_approved'] = 1;
        $save_user['is_active'] = 1;
        $_json['message'] = 'User has been saved successfully.';
      }

      $user_id = $this->User_model->saveUser($save_user);

      if($save_user['user_id'] > 0)
      {
        $this->User_activity_model->log('user_save', "User (".$save_user['first_name']." ".$save_user['last_name'].") -> Updated");
      }
      else
      {
        $this->User_activity_model->log('user_save', "User (".$save_user['first_name']." ".$save_user['last_name'].") -> Added");
      }

      $_json['success'] = true;
      $this->session->set_flashdata('success_message', $_json['message']);
      $_json['redirect'] = site_url($this->admin_folder.'users');
    }
    
    $this->json($_json);
  }

  public function get_info()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'users')))
    {
      redirect($this->admin_folder.'login');
    }

    $_json = array();

    $user_id = $this->input->post('user_id');

    if($user_id == 0 || $user_id == '')
    {
      $_json['error'] = true;
      $_json['message'] = 'Something went wrong!';
    } 

    if(!$_json)
    {
      $user_info    = $this->User_model->getUser($user_id);
      $u_other_info = $this->User_model->getUserOtherInfo($user_id);

      if($user_info && $u_other_info)
      {
        $u_content     = json_decode($u_other_info['content'],true);
        $location_info = $this->Location_model->getState($u_content['state_id']);

        $user = array();
        $user['firstname']       = $user_info['first_name'];
        $user['lastname']        = $user_info['last_name'];
        $user['access_code']     = $user_info['access_code'];
        $user['access_code_str'] = $this->User_model->getPositionName($user_info['access_code']);
        $user['email']           = $user_info['email'];
        $user['phone']           = $user_info['phone'];
        $user['requested_on']    = date('m/d/Y h:i A',strtotime($user_info['created']));
        $user['state_id']        = $u_content['state_id'];
        $user['state_name']      = $location_info->name;
        $user['address']         = $u_content['address'];
        $user['city']            = $u_content['city'];
        $user['zip_code']        = $u_content['zip_code'];

        if($user_info['access_code'] == 'V')
        {
          $user['company_name']     = $u_content['company_name'];
          $user['selling_products'] = implode(',',json_decode($u_content['selling_products'],true));
          $user['is_promotion']     = $u_content['is_promotion'];
          $user['promotion_file']   = '';

          if($user['is_promotion'] == 'yes')
          {
            $user['promotion_file']   = $u_content['promotion_file'];
          }
        }

        if($user_info['access_code'] == 'O')
        {
          $user['company_name']     = $u_content['company_name'];
          $user['fax']              = $u_content['fax'];
          $user['website']          = $u_content['website'];
          $user['total_boys']       = $u_content['total_boys'];
          $user['road_tech']        = $u_content['road_tech'];
          $user['total_tech']       = $u_content['total_tech'];
          $user['total_payroll']    = $u_content['total_payroll'];
          $user['m_gross_profit']   = $u_content['m_gross_profit'];
          $user['m_present_profit'] = $u_content['m_present_profit'];
          $user['shop_picture']     = $u_content['shop_picture'];
        }

        if($user_info['access_code'] == 'T')
        {
          $user['previous_company']  = $u_content['previous_company'];
          $user['experience']        = $u_content['experience'];
          $user['consider_yourself'] = $u_content['consider_yourself'];
          $user['moderator']         = $u_content['moderator'];
          $user['certificate']       = $u_content['certificate'];
        }
        
        $_json['user_info'] = array();
        $_json['success']   = true;
        $_json['user_info'] = $user;
      }
      else
      {
        $_json['error'] = true;
        $_json['message'] = 'No record in Database!';
      }
    }

    $this->json($_json);
  }

  public function delete($user_id)
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'users')))
    {
      redirect($this->admin_folder.'login');
    }

    if($user_id == 0)
    {
      $this->session->set_flashdata('error_message', 'Invalid user ID!');
      if($_SERVER['QUERY_STRING'] != '')
      {
        redirect($this->admin_folder.'users?'.$_SERVER['QUERY_STRING']);
      }
      else
      {
        redirect($this->admin_folder.'users');
      }
    }
    else
    {
      if($user_row = $this->User_model->getUserData($user_id))
      {
        $this->User_model->deleteUser($user_id);
        $this->session->set_flashdata('success_message', 'User has been removed successfully.');
        $this->User_activity_model->log('user_delete', "User (".$save_user['first_name']." ".$save_user['last_name'].") -> Removed");
      }
      
      if($_SERVER['QUERY_STRING'] != '')
      {
        redirect($this->admin_folder.'users?'.$_SERVER['QUERY_STRING']);
      }
      else
      {
        redirect($this->admin_folder.'users');
      }
    }
  }

  public function registration_request_list()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'registration-request')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'registration_request';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'user.user_id';
    $sort_order = isset($sort_order) ? $sort_order : 'DESC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }

    $filter['is_approved'] = '0';
    
    $data['users'] = $this->User_model->getUsers(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter),true);
    $data['total_user'] = $this->User_model->getTotalUsers(array('filter' => $filter),true);

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'registration-request');
    $config['total_rows']   = $data['total_user'];
    $config['per_page']     = $limit;

    if($data['total_user'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_user'] < ($page*$limit)) ? $data['total_user'] : ($page*$limit)).' of '.$data['total_user'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $data['page_method'] = 'registration-request';

    $this->session->set_userdata(array('user_refer' => array('method' => 'registration-request', 'page' => $page, 'q' => $q)));

    $this->pagination->initialize($config);

    $this->User_activity_model->log('registration_request_list', "Registration Users List -> View");

    $this->view('approved_user_list', $data);
  }

  public function set_user_approved()
  {
    $_json = array();

    $user_id = $this->input->post('primary_value'); 

    if(($user_id == '') || ($user_id <= 0))
    {
      $_json['error'] = true;
      $_json['message'] = 'Something went wrong!';
    }
    elseif($user_row = $this->User_model->getUser($user_id))
    {
      if($user_row)
      {
        if($user_row['is_approved'] == 1)
        {
          $_json['error'] = true;
          $_json['message'] = 'This user already approved';
        }
      }
      else
      {
        $_json['error'] = true;
        $_json['message'] = 'User not in database';
      }
    }

    if(!$_json)
    {
      $update_user = array();
      $update_user['user_id']     = $user_id;
      $update_user['is_approved'] = 1;
      $update_user['is_active']   = 1;
      $update_user['approved']    = date('Y-m-d H:i:s');

      $this->User_model->saveUser($update_user);

      $this->load->library('emailsender');
      $username = $user_row['first_name'];
      $to = $user_row['email'];
      $subject = 'Signup Request Approved!';
      $body = '<p>Hi '.$username.',<br>Your signup request has been approved.<br>Please <a href="'.site_url('login').'"><b>Login</b></a> and check your account.<br><br>Thanks,<br>'.$this->data['app_name'].'</p>';
      $this->emailsender->send($to, $subject, $body);

      $_json['success'] = true;
      $_json['message'] = 'User approved successfully';

      $this->User_activity_model->log('set_user_approved', "Approved Registration request -> ".$user_id."");

      $this->session->set_flashdata('success_message', $_json['message']);
    }

    $this->json($_json);
  }

  public function delete_registration_request()
  {
    $_json = array();

    $user_id = $this->input->post('primary_value'); 

    if(($user_id == '') || ($user_id <= 0))
    {
      $_json['error'] = true;
      $_json['message'] = 'Something went wrong!';
    }

    if(!$_json)
    {
      $update_user = array();
      $update_user['user_id']    = $user_id;
      $update_user['is_deleted'] = 1;
      
      $this->User_model->saveUser($update_user);

      $_json['success'] = true;
      $_json['message'] = 'Registration deleted successfully';

       $this->User_activity_model->log('delete_registration_request', "Delete Registration request -> ".$user_id."");

      $this->session->set_flashdata('success_message', $_json['message']);
    }

    $this->json($_json);
  }

  public function all_approved_user_list()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'approved-users')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'all_users';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'user.user_id';
    $sort_order = isset($sort_order) ? $sort_order : 'DESC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }

    $filter['is_approved'] = 1;
    
    $data['users'] = $this->User_model->getUsers(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter),true);
    $data['total_user'] = $this->User_model->getTotalUsers(array('filter' => $filter),true);

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'approved-users');
    $config['total_rows']   = $data['total_user'];
    $config['per_page']     = $limit;

    if($data['total_user'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_user'] < ($page*$limit)) ? $data['total_user'] : ($page*$limit)).' of '.$data['total_user'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $data['page_method'] = 'approved-users';

    $this->session->set_userdata(array('user_refer' => array('method' => 'approved-users', 'page' => $page, 'q' => $q)));

    $this->pagination->initialize($config);

    $this->User_activity_model->log('all_approved_user_list', "Approved Users List -> View");

    $this->view('approved_user_list', $data);
  }

  public function owner_list()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'owners')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'owner';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'user.user_id';
    $sort_order = isset($sort_order) ? $sort_order : 'DESC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }

    $filter['is_approved'] = 1;
    $filter['access_code'] = 'O';
    
    $data['users'] = $this->User_model->getUsers(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter),true);
    $data['total_user'] = $this->User_model->getTotalUsers(array('filter' => $filter),true);

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'owners');
    $config['total_rows']   = $data['total_user'];
    $config['per_page']     = $limit;

    if($data['total_user'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_user'] < ($page*$limit)) ? $data['total_user'] : ($page*$limit)).' of '.$data['total_user'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $data['page_method'] = 'owners';

    $this->session->set_userdata(array('user_refer' => array('method' => 'owners', 'page' => $page, 'q' => $q)));

    $this->pagination->initialize($config);

    $this->User_activity_model->log('owner_list', "Owner Users List -> View");

    $this->view('approved_user_list', $data);
  }

  public function technician_list()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'technicians')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'technician';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'user.user_id';
    $sort_order = isset($sort_order) ? $sort_order : 'DESC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }

    $filter['is_approved'] = 1;
    $filter['access_code'] = 'T';
    
    $data['users'] = $this->User_model->getUsers(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter),true);
    $data['total_user'] = $this->User_model->getTotalUsers(array('filter' => $filter),true);

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'technicians');
    $config['total_rows']   = $data['total_user'];
    $config['per_page']     = $limit;

    if($data['total_user'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_user'] < ($page*$limit)) ? $data['total_user'] : ($page*$limit)).' of '.$data['total_user'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $data['page_method'] = 'technicians';

    $this->session->set_userdata(array('user_refer' => array('method' => 'technicians', 'page' => $page, 'q' => $q)));

    $this->pagination->initialize($config);

    $this->User_activity_model->log('technician_list', "Technician Users List -> View");

    $this->view('approved_user_list', $data);
  }

  public function vendor_list()
  {
    if(!$this->auth->is_admin_logged_in(site_url($this->admin_folder.'vendors')))
    {
      redirect($this->admin_folder.'login');
    }

    $data = $this->data;

    $data['scripts'] = array($data['app_path'].'assets/admin/js/user.js');
    
    $this->menu    = 'user';
    $this->submenu = 'vendor';

    $q = $this->input->get('q');
    $page = $this->input->get('page');
    $limit = $this->input->get('limit');

    $order_by   = isset($order_by) ? $order_by : 'user.user_id';
    $sort_order = isset($sort_order) ? $sort_order : 'DESC';
    $page       = (isset($page) && ((int)$page > 1)) ? (int)$page : 1;
    $limit      = (isset($limit) && ((int)$limit > 0)) ? (int)$limit : 20;
    $offset     = ($page - 1) * $limit;

    $filter = array();
    if($q)
    {
      $filter['q'] = $q;
    }

    $filter['is_approved'] = 1;
    $filter['access_code'] = 'V';
    
    $data['users'] = $this->User_model->getUsers(array('limit' => $limit, 'offset' => $offset, 'order_by' => $order_by, 'sort_order' => $sort_order, 'filter' => $filter),true);
    $data['total_user'] = $this->User_model->getTotalUsers(array('filter' => $filter),true);

    $this->load->library('pagination');
    
    $config['base_url']     = site_url($this->admin_folder.'vendors');
    $config['total_rows']   = $data['total_user'];
    $config['per_page']     = $limit;

    if($data['total_user'] > 0)
    {
      $data['pagination_string'] = 'Showing '.($offset+1).' - '.(($data['total_user'] < ($page*$limit)) ? $data['total_user'] : ($page*$limit)).' of '.$data['total_user'].' items';
    }
    else
    {
      $data['pagination_string'] = '';
    }

    $data['page_method'] = 'vendors';

    $this->session->set_userdata(array('user_refer' => array('method' => 'vendors', 'page' => $page, 'q' => $q)));

    $this->pagination->initialize($config);

    $this->User_activity_model->log('vendor_list', "Vendor Users List -> View");

    $this->view('approved_user_list', $data);
  }
}
