<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Conversation_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getConversations($data = array())
  {
    $this->db->select('conversation.conversation_id, conversation.forum_id, forum.title AS forum_title, conversation.topic_id, topic.title AS topic_title, conversation.content, conversation.created, conversation.is_approved, conversation.approved, conversation.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name, user_group.name AS user_group_name');
    $this->db->from('conversation');
    $this->db->join('user', 'user.user_id = conversation.user_id', 'left');
    $this->db->join('user_group', 'user_group.code = user.access_code', 'left');
    $this->db->join('topic', 'topic.topic_id = conversation.topic_id', 'left');
    $this->db->join('forum', 'forum.forum_id = conversation.forum_id', 'left');

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
                  $whereStr .= "(".$this->db->dbprefix('conversation').".title LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['topic_id']) && ((int)$data['filter']['topic_id'] > 0))
      {
        $this->db->where('conversation.topic_id', $data['filter']['topic_id']);
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('conversation.conversation_id', 'ASC');
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

  public function getTotalConversations($data = array())
  {
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
                  $whereStr .= "(".$this->db->dbprefix('conversation').".title LIKE \"%".trim($keyword)."%\")";
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
        $this->db->where('conversation.forum_id', $data['filter']['forum_id']);
      }
    }

    return $this->db->get('conversation')->num_rows();
  }

  public function getConversation($conversation_id)
  {
    $this->db->select('conversation.conversation_id, conversation.forum_id, forum.title AS forum_title, conversation.topic_id, topic.title AS topic_title, conversation.content, conversation.created, conversation.is_approved, conversation.approved, conversation.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name, user_group.name AS user_group_name');
    $this->db->from('conversation');
    $this->db->join('user', 'user.user_id = conversation.user_id');
    $this->db->join('user_group', 'user_group.code = user.access_code', 'left');
    $this->db->join('topic', 'topic.topic_id = conversation.topic_id', 'left'); 
    $this->db->join('forum', 'forum.forum_id = conversation.forum_id', 'left'); 
    $this->db->where('conversation.conversation_id', $conversation_id);
    return $this->db->get()->row_array();
  }

  public function getConversationData($conversation_id)
  {
    $this->db->where('conversation_id', $conversation_id);
    return $this->db->get('conversation')->row_array();
  }

  public function saveConversation($data = array())
  {
    if(isset($data['conversation_id']) && ((int)$data['conversation_id'] > 0))
    {
      $this->db->where('conversation_id', (int)$data['conversation_id']);
      $this->db->update('conversation', $data);
      $conversation_id = $data['conversation_id'];
    }
    else
    {
      $this->db->insert('conversation', $data);
      $conversation_id = $this->db->insert_id();
    }
    
    return $conversation_id;
  }

  public function deleteConversation($conversation_id)
  {
    $this->db->where('conversation_id', (int)$conversation_id);
    $this->db->delete('conversation');
  }
}