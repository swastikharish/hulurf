<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Topic_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getTopics($data = array())
  {
    $this->db->select('topic.topic_id, topic.forum_id, forum.title AS forum_title, forum.category_id AS forum_category_id, forum.slug AS forum_slug, topic.title, topic.slug, topic.description, topic.pdf, topic.is_active, topic.created, topic.is_approved, topic.approved, topic.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name');
    $this->db->from('topic');
    $this->db->join('user', 'user.user_id = topic.user_id', 'left');
    $this->db->join('forum', 'forum.forum_id = topic.forum_id', 'left');
    $this->db->where('topic.is_deleted', '0');

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
                  $whereStr .= "(".$this->db->dbprefix('topic').".title LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['forum_id']) && ((int)$data['filter']['forum_id'] > 0))
      {
        $this->db->where('topic.forum_id', $data['filter']['forum_id']);
      }

      if(isset($data['filter']['is_approved']))
      {
        $this->db->where('topic.is_approved', $data['filter']['is_approved']);
      }

      if(isset($data['filter']['is_active']))
      {
        $this->db->where('topic.is_active', $data['filter']['is_active']);
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('topic.topic_id', 'ASC');
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

  public function getTotalTopics($data = array())
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
                  $whereStr .= "(".$this->db->dbprefix('topic').".title LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['forum_id']) && ((int)$data['filter']['forum_id'] > 0))
      {
        $this->db->where('topic.forum_id', $data['filter']['forum_id']);
      }

      if(isset($data['filter']['is_approved']))
      {
        $this->db->where('topic.is_approved', $data['filter']['is_approved']);
      }

      if(isset($data['filter']['is_active']))
      {
        $this->db->where('topic.is_active', $data['filter']['is_active']);
      }
    }

    return $this->db->get('topic')->num_rows();
  }

  public function getTopic($topic_id)
  {
    $this->db->select('topic.topic_id, topic.forum_id, forum.title AS forum_title, forum.category_id AS forum_category_id, forum.slug AS forum_slug, topic.title, topic.slug, topic.description, topic.pdf, topic.is_active, topic.created, topic.is_approved, topic.approved, topic.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name');
    $this->db->from('topic');
    $this->db->join('user', 'user.user_id = topic.user_id');
    $this->db->join('forum', 'forum.forum_id = topic.forum_id', 'left'); 
    $this->db->where('topic.topic_id', $topic_id);
    return $this->db->get()->row_array();
  }

  public function getTopicData($topic_id)
  {
    $this->db->where('topic_id', $topic_id);
    return $this->db->get('topic')->row_array();
  }

  public function saveTopic($data = array())
  {
    if(isset($data['topic_id']) && ((int)$data['topic_id'] > 0))
    {
      $this->db->where('topic_id', (int)$data['topic_id']);
      $this->db->update('topic', $data);
      $topic_id = $data['topic_id'];
    }
    else
    {
      $this->db->insert('topic', $data);
      $topic_id = $this->db->insert_id();
    }
    
    return $topic_id;
  }

  public function deleteTopic($topic_id)
  {
    $this->db->where('topic_id', (int)$topic_id);
    $this->db->update('topic', array('is_deleted' => 1, 'deleted' => date('Y-m-d H:i:s')));
    
    return $topic_id;
  }

  public function getTopicBySlug($topic_slug, $topic_id = 0)
  {
    $this->db->from('topic');
    if($topic_id == 0)
    {
      $this->db->select('topic.topic_id, topic.forum_id, forum.title AS forum_title, forum.category_id AS forum_category_id, forum.slug AS forum_slug, topic.title, topic.slug, topic.description, topic.pdf, topic.is_active, topic.created, topic.is_approved, topic.approved, topic.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name, user.email');      
      $this->db->join('user', 'user.user_id = topic.user_id');
      $this->db->join('forum', 'forum.forum_id = topic.forum_id', 'left'); 
    }
    
    if($topic_id > 0)
    {
      $this->db->where('topic.topic_id', $topic_id);
    }
    $this->db->where('topic.slug', $topic_slug);

    return $this->db->get()->row_array();
  }

  public function checkTopicBySlug($topic_slug, $topic_id = 0)
  {
    if($topic_id > 0)
    {
      $this->db->where('topic.topic_id !=', $topic_id);
    }

    $this->db->where('topic.slug', $topic_slug);

    return $this->db->get('topic')->num_rows();
  }

  public function createTopicSlug($slug, $topic_id = 0, $count=0)
  {
    $topic_row = $this->checkTopicBySlug($slug, $topic_id);
    if($topic_row > 0)
    {
      $slug = $slug.'-f'.($count + 1);
      return $this->createTopicSlug($slug, $topic_id, $count++);
    }
    else
    {
      return $slug;
    }
  }

  public function countTopicConversations($topic_id)
  {
    $this->db->where('conversation.topic_id', $topic_id);
    return $this->db->get('conversation')->num_rows();
  }

  public function recentTopicConversation($topic_id)
  {
    $this->db->select('conversation.conversation_id, conversation.forum_id, forum.title AS forum_title, forum.category_id AS forum_category_id, conversation.topic_id, topic.title AS topic_title, conversation.content, conversation.created, conversation.is_approved, conversation.approved, conversation.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name, user_group.name AS user_group_name');
    $this->db->from('conversation');
    $this->db->join('user', 'user.user_id = conversation.user_id');
    $this->db->join('user_group', 'user_group.code = user.access_code', 'left');
    $this->db->join('topic', 'topic.topic_id = conversation.topic_id', 'left'); 
    $this->db->join('forum', 'forum.forum_id = conversation.forum_id', 'left'); 
    $this->db->where('conversation.topic_id', $topic_id);
    $this->db->order_by('conversation.created', 'DESC');

    return $this->db->get()->row_array();
  }
}