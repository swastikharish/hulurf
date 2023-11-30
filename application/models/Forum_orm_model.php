<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Forum_orm_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getForumVideos($forum_id, $all = false)
  {
    if ($all == true) {
      $this->db->select('video.*');
    } else {
      $this->db->select('video.video_id, video.type, video.name, video.youtube_video, video.mp4_video');
    }

    $this->db->from('forum_video');
    $this->db->join('video', 'video.video_id = forum_video.video_id', 'left');
    $this->db->where('forum_video.forum_id', $forum_id);
    $this->db->where('video.is_active', '1');
    $this->db->where('video.is_deleted', '0');

    return $this->db->get()->result_array();
  }

  public function saveForumVideos($data = [])
  {
    if ($data) {
      $this->db->insert_batch('forum_video', $data);
    }
  }

  public function deleteForumVideos($forum_id, $video_id = 0)
  {
    if ($video_id > 0) {
      $this->db->where('video_id', $video_id);
    }
    
    $this->db->where('forum_id', $forum_id);
    $this->db->delete('forum_video');
  }

  public function countForumVideos($forum_id)
  {
    $this->db->join('video', 'video.video_id = forum_video.video_id', 'left');
    $this->db->where('forum_video.forum_id', $forum_id);
    $this->db->where('video.is_active', '1');
    $this->db->where('video.is_deleted', '0');

    return $this->db->get('forum_video')->num_rows();
  }

  public function getForumImages($forum_id, $all = false)
  {
    if ($all == true) {
      $this->db->select('image.*');
    } else {
      $this->db->select('image.image_id, image.type, image.position, image.name, image.description, image.image, image.url');
    }
    
    $this->db->from('forum_image');
    $this->db->join('image', 'image.image_id = forum_image.image_id', 'left');
    $this->db->where('forum_image.forum_id', $forum_id);
    $this->db->where('image.is_active', '1');
    $this->db->where('image.is_deleted', '0');

    return $this->db->get()->result_array();
  }

  public function deleteForumImages($forum_id)
  {
    $this->db->where('forum_id', $forum_id);
    $this->db->delete('forum_image');
  }

  public function saveForumImages($data = [])
  {
    if ($data) {
      $this->db->insert_batch('forum_image', $data);
    }
  }

  public function countForumImages($forum_id)
  {
    $this->db->join('image', 'image.image_id = forum_image.image_id', 'left');
    $this->db->where('forum_image.forum_id', $forum_id);
    $this->db->where('image.is_active', '1');
    $this->db->where('image.is_deleted', '0');

    return $this->db->get('forum_image')->num_rows();
  }

  public function countForumTopics($forum_id)
  {
    $this->db->where('topic.forum_id', $forum_id);
    $this->db->where('topic.is_approved', '1');
    $this->db->where('topic.is_active', '1');
    $this->db->where('topic.is_deleted', '0');

    return $this->db->get('topic')->num_rows();
  }

  public function recentForumTopic($forum_id)
  {
    $this->db->select('topic.topic_id, topic.forum_id, forum.title AS forum_title, forum.slug AS forum_slug, topic.title, topic.slug, topic.description, topic.is_active, topic.created, topic.is_approved, topic.approved, topic.user_id, CONCAT_WS(" ", '.$this->db->dbprefix('user').'.first_name, '.$this->db->dbprefix('user').'.last_name) AS user_name');
    $this->db->from('topic');
    $this->db->join('user', 'user.user_id = topic.user_id');
    $this->db->join('forum', 'forum.forum_id = topic.forum_id', 'left'); 
    $this->db->where('topic.forum_id', $forum_id);
    $this->db->where('topic.is_approved', '1');
    $this->db->where('topic.is_active', '1');
    $this->db->where('topic.is_deleted', '0');
    $this->db->order_by('topic.created', 'DESC');
    
    return $this->db->get()->row_array();
  }
}