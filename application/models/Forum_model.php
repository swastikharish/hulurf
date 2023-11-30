<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Forum_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getForums($data = array())
  {
    $this->db->select('forum.forum_id, forum.category_id, category.name AS category_name, category.price AS category_price, forum.title, forum.slug, forum.short_description, forum.description, forum.is_active, forum.created, forum.is_approved, forum.approved, forum.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name');
    $this->db->from('forum');
    $this->db->join('category', 'category.category_id = forum.category_id', 'left');
    $this->db->join('user', 'user.user_id = forum.user_id', 'left');
    $this->db->where('forum.is_deleted', '0');

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
                  $whereStr .= "(".$this->db->dbprefix('forum').".title LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['category_id']) && ((int)$data['filter']['category_id'] > 0))
      {
        $this->db->where('forum.category_id', $data['filter']['category_id']);
      }

      if(isset($data['filter']['is_approved']))
      {
        $this->db->where('forum.is_approved', $data['filter']['is_approved']);
      }

      if(isset($data['filter']['is_active']))
      {
        $this->db->where('forum.is_active', $data['filter']['is_active']);
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('forum.forum_id', 'ASC');
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

  public function getTotalForums($data = array())
  {
    $this->db->where('is_deleted', '0');

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
                  $whereStr .= "(".$this->db->dbprefix('forum').".title LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['category_id']) && ((int)$data['filter']['category_id'] > 0))
      {
        $this->db->where('forum.category_id', $data['filter']['category_id']);
      }

      if(isset($data['filter']['is_approved']))
      {
        $this->db->where('forum.is_approved', $data['filter']['is_approved']);
      }

      if(isset($data['filter']['is_active']))
      {
        $this->db->where('forum.is_active', $data['filter']['is_active']);
      }
    }

    return $this->db->get('forum')->num_rows();
  }

  public function getForum($forum_id)
  {
    $this->db->select('forum.forum_id, forum.category_id, category.name AS category_name, category.price AS category_price, forum.title, forum.slug, forum.short_description, forum.description, forum.is_active, forum.created, forum.is_approved, forum.approved, forum.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name');
    $this->db->from('forum');
    $this->db->join('category', 'category.category_id = forum.category_id'); 
    $this->db->join('user', 'user.user_id = forum.user_id');    
    $this->db->where('forum.forum_id', $forum_id);
    return $this->db->get()->row_array();
  }

  public function getForumData($forum_id)
  {
    $this->db->where('forum_id', $forum_id);
    return $this->db->get('forum')->row_array();
  }

  public function saveForum($data = array())
  {
    if(isset($data['forum_id']) && ((int)$data['forum_id'] > 0))
    {
      $this->db->where('forum_id', (int)$data['forum_id']);
      $this->db->update('forum', $data);
      $forum_id = $data['forum_id'];
    }
    else
    {
      $this->db->insert('forum', $data);
      $forum_id = $this->db->insert_id();
    }
    
    return $forum_id;
  }

  public function deleteForum($forum_id)
  {
    $this->db->where('forum_id', (int)$forum_id);
    $this->db->update('forum', array('is_deleted' => 1, 'deleted' => date('Y-m-d H:i:s')));
    
    return $forum_id;
  }

  public function getForumBySlug($forum_slug, $forum_id = 0)
  {    
    $this->db->from('forum');
    
    if($forum_id == 0)
    {
      $this->db->select('forum.forum_id, forum.category_id, category.name AS category_name, category.price AS category_price, forum.title, forum.slug, forum.short_description, forum.description, forum.is_active, forum.created, forum.is_approved, forum.approved, forum.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name');
      $this->db->join('category', 'category.category_id = forum.category_id'); 
      $this->db->join('user', 'user.user_id = forum.user_id');
    }

    if($forum_id > 0)
    {
      $this->db->where('forum.forum_id', $forum_id);
    }

    $this->db->where('forum.slug', $forum_slug);

    return $this->db->get()->row_array();
  }

  public function checkForumBySlug($forum_slug, $forum_id = 0)
  {    
    if($forum_id > 0)
    {
      $this->db->where('forum.forum_id !=', $forum_id);
    }

    $this->db->where('forum.slug', $forum_slug);

    return $this->db->get('forum')->num_rows();
  }

  public function createForumSlug($slug, $forum_id = 0, $count = 0)
  {
    $forum_row = $this->checkForumBySlug($slug, $forum_id);
    $this->db->last_query();
    if($forum_row > 0)
    {
      $slug = $slug.'-f'.($count + 1);
      return $this->createForumSlug($slug, $forum_id, $count++);
    }
    else
    {
      return $slug;
    }
  }
}