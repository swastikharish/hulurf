<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Topic_orm_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getTopicImages($topic_id, $all = false)
  {
    if ($all == true) {
      $this->db->select('image.*');
    } else {
      $this->db->select('image.image_id, image.type, image.position, image.name, image.description, image.image, image.url');
    }
    
    $this->db->from('topic_image');
    $this->db->join('image', 'image.image_id = topic_image.image_id', 'left');
    $this->db->where('topic_image.topic_id', $topic_id);
    $this->db->where('image.is_active', '1');
    $this->db->where('image.is_deleted', '0');

    return $this->db->get()->result_array();
  }

  public function deleteTopicImages($topic_id)
  {
    $this->db->where('topic_id', $topic_id);
    $this->db->delete('topic_image');
  }

  public function saveTopicImages($data = [])
  {
    if ($data) {
      $this->db->insert_batch('topic_image', $data);
    }
  }

  public function countTopicImages($topic_id)
  {
    $this->db->from('topic_image');
    $this->db->join('image', 'image.image_id = topic_image.image_id', 'left');
    $this->db->where('topic_image.topic_id', $topic_id);
    $this->db->where('image.is_active', '1');
    $this->db->where('image.is_deleted', '0');

    return $this->db->get('topic_image')->num_rows();
  }

  public function countForumConversations($topic_id)
  {
    $this->db->where('conversation.topic_id', $topic_id);

    return $this->db->get('conversation')->num_rows();
  }
}