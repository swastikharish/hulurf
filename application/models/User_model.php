<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class User_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getGroups($exclude_ids = [])
  {
    if($exclude_ids)
    {
      $this->db->where_not_in('user_group_id', $exclude_ids);
    }
    
    return $this->db->get('user_group')->result_array();
  }

  public function getUsers($data = array(), $admin_user = false)
  {
    $this->db->select('user.user_id, user.access_code, user.first_name, user.last_name, CONCAT_WS(\' \', '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name, user.email, user.phone, user.is_active, user.is_approved, IF('.$this->db->dbprefix('user').'.logdate IS NOT NULL, DATE_FORMAT('.$this->db->dbprefix('user').'.logdate, "%c/%e/%Y %h:%i %p"), "--") AS logdate_date, IF('.$this->db->dbprefix('user').'.approved IS NOT NULL, DATE_FORMAT('.$this->db->dbprefix('user').'.approved, "%c/%e/%Y %h:%i %p"), "--") AS approved_date,user.created');
    $this->db->from('user');
    $this->db->where('user.is_deleted', '0');
    $this->db->where('user.user_id !=', '1');

    if($admin_user)
    { 
      $this->db->where('user.access_code <>', 'A');
    }

    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if(isset($data['filter']['q']) && !empty($data['filter']['q']))
      {
        if(isset($data['filter']['q']) && !empty($data['filter']['q']) && $this->Setting_model->parseSearchString($searchKeywords, $data['filter']['q']))
        {
          if(isset($searchKeywords) && (sizeof($searchKeywords) > 0))
          {
            $whereStr = "(";
            for($i=0, $n=sizeof($searchKeywords); $i<$n; $i++ )
            {
              switch($searchKeywords[$i])
              {
                case '(':
                case ')':
                case 'and':
                case 'or':
                  $whereStr .= " ".$searchKeywords[$i]." ";
                  break;
                default:
                  $keyword = trim($searchKeywords[$i]);
                  $whereStr .= "(".$this->db->dbprefix('user').".first_name LIKE \"%".trim($keyword)."%\" OR ".$this->db->dbprefix('user').".last_name LIKE \"%".trim($keyword)."%\" OR ".$this->db->dbprefix('user').".email LIKE \"%".trim($keyword)."%\" OR ".$this->db->dbprefix('user').".phone LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['is_approved']) && ($data['filter']['is_approved']!=''))
      {
        $this->db->where('is_approved',$data['filter']['is_approved']);
      }

      if(isset($data['filter']['access_code']) && ($data['filter']['access_code']!=''))
      {
        $this->db->where('access_code',$data['filter']['access_code']);
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('user.user_id', 'ASC');
    }

    if(!empty($data['limit']))
    {
      $this->db->limit($data['limit']);
    }
    
    if(isset($data['offset']))
    {
      $this->db->offset($data['offset']);
    }

    return $this->db->get()->result_array();
  }

  public function getTotalUsers($data = array(), $admin_user = false)
  {
    $this->db->where('is_deleted', '0');

    if($admin_user)
    { 
      $this->db->where('access_code <>', 'A');
    }

    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if(isset($data['filter']['q']) && !empty($data['filter']['q']))
      {
        if(isset($data['filter']['q']) && !empty($data['filter']['q']) && $this->Setting_model->parseSearchString($searchKeywords, $data['filter']['q']))
        {
          if(isset($searchKeywords) && (sizeof($searchKeywords) > 0)){
            $whereStr = "(";
            for($i=0, $n=sizeof($searchKeywords); $i<$n; $i++ ){
              switch($searchKeywords[$i]){
                case '(':
                case ')':
                case 'and':
                case 'or':
                  $whereStr .= " ".$searchKeywords[$i]." ";
                  break;
                default:
                  $keyword = trim($searchKeywords[$i]);
                  $whereStr .= "(".$this->db->dbprefix('user').".first_name LIKE \"%".trim($keyword)."%\" OR ".$this->db->dbprefix('user').".last_name LIKE \"%".trim($keyword)."%\" OR ".$this->db->dbprefix('user').".email LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['is_approved']) && ($data['filter']['is_approved']!=''))
      {
        $this->db->where('is_approved',$data['filter']['is_approved']);
      }

      if(isset($data['filter']['access_code']) && ($data['filter']['access_code']!=''))
      {
        $this->db->where('access_code',$data['filter']['access_code']);
      }
    }

    return $this->db->get('user')->num_rows();
  }

  public function getUser($user_id)
  {
    $this->db->select('user.user_id, user.access_code, user.first_name, user.last_name, CONCAT_WS(\' \', '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name, user.email, user.phone, user.is_active, user.is_approved, user.created');
    $this->db->from('user');
    $this->db->where('user.user_id', $user_id);
    return $this->db->get()->row_array();
  }

  public function getUserData($user_id)
  {
    $this->db->where('user_id', $user_id);
    return $this->db->get('user')->row_array();
  }

  public function getUserByEmail($email, $id = 0)
  {
    if($id)
    {
      $this->db->where('user_id <>', $id);
    }
    $this->db->like('email', $email);
    $this->db->where('is_deleted', 0);
    return $this->db->get('user')->num_rows();
  }

  public function getUserByPhone($phone, $id = 0)
  {
    if($id)
    {
      $this->db->where('user_id <>', $id);
    }
    $this->db->where('phone', $phone);
    $this->db->where('is_deleted', 0);
    return $this->db->get('user')->num_rows();
  }

  public function saveUser($data = array())
  {
    if(isset($data['user_id']) && ((int)$data['user_id'] > 0))
    {
      $this->db->where('user_id', (int)$data['user_id']);
      $this->db->update('user', $data);
      $user_id = $data['user_id'];
    }
    else
    {
      $this->db->insert('user', $data);
      $user_id = $this->db->insert_id();
    }
    
    return $user_id;
  }

  public function deleteUser($user_id)
  {
    $this->db->where('user_id', (int)$user_id);
    $this->db->delete('user');
    
    return $user_id;
  }

  public function getUserSubscriptions($data = array())
  {
    $this->db->select('user_subscription.*, DATE_FORMAT('.$this->db->dbprefix('user_subscription').'.created, "%c/%e/%Y %h:%i %p") AS created_date');
    $this->db->from('user_subscription');
    $this->db->where('user_subscription.user_id', $data['user_id']);

    return $this->db->get()->result_array();
  }

  public function getUserActivities($data = array())
  {
    $this->db->select('user_activity.*, DATE_FORMAT('.$this->db->dbprefix('user_activity').'.created, "%c/%e/%Y %h:%i %p") AS created_date');
    $this->db->from('user_activity');
    $this->db->where('user_activity.user_id', $data['user_id']);

    if(!empty($data['daterange']))
    {
      $this->db->where('(DATE('.$this->db->dbprefix('user_activity').'.created) BETWEEN DATE("'.$data['daterange']['from']. '") AND DATE("'.$data['daterange']['to'].'"))', null);
    }
    
    $this->db->order_by('user_activity.created', 'DESC');

    if(!empty($data['limit']))
    {
      $this->db->limit($data['limit']);
    }
    
    if(isset($data['offset']))
    {
      $this->db->offset($data['offset']);
    }

    return $this->db->get()->result_array();
  }

  public function getTotalUserActivities($data = array())
  {
    $this->db->where('user_activity.user_id', $data['user_id']);
    
    if(!empty($data['daterange']))
    {
      $this->db->where('(DATE('.$this->db->dbprefix('user_activity').'.created) BETWEEN DATE("'.$data['daterange']['from']. '") AND DATE("'.$data['daterange']['to'].'"))', null);
    }

    return $this->db->get('user_activity')->num_rows();
  }

  public function getPositionName($access_code)
  {
    $position = [
      'A' => 'Admin',
      'U' => 'User',
     ];

    if($access_code!='')
    {
      return $position[$access_code];
    }
  }

  public function saveUserInfo($data = array())
  {

    $this->db->where('user_id',$data['user_id']);
    $this->db->delete('user_info');
    
    $this->db->insert('user_info', $data);
    $user_id = $this->db->insert_id();

    return $user_id;
  }

  public function getUserOtherInfo($user_id)
  {
    $this->db->where('user_id', $user_id);
    return $this->db->get('user_info')->row_array();
  }

  public function checkUserByEmail($email, $id = 0)
  {
    if($id)
    {
      $this->db->where('user_id <>', $id);
    }

    $this->db->where('is_deleted', 0);
    $this->db->like('email', $email);
    return $this->db->get('user')->num_rows();
  }
}