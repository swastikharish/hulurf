<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Video_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getVideos($data = array(), $library = false)
  {
    $this->db->select('video.video_id, video.type, video.name, video.description, video.image, video.youtube_video, video.mp4_video, video.is_active, video.created, DATE_FORMAT('.$this->db->dbprefix('video').'.created, "%c/%e/%Y %h:%i %p") AS created_date, vc.name AS category_name');
    $this->db->from('video');
    $this->db->join('video_category AS vc','vc.category_id=video.category_id','left');

    if($library)
    {
      $this->db->where('video.category_id > ', 0);
    } 

    $this->db->where('video.is_deleted', '0');

    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if((isset($data['filter']['q']) && !empty($data['filter']['q'])) || (isset($data['filter']['f']) && !empty($data['filter']['f'])))
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
                  $whereStr .= "(".$this->db->dbprefix('video').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['c']) && $data['filter']['c'] > 0)
      {
        $this->db->where('video.category_id',$data['filter']['c']);
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('video.video_id', 'DESC');
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

  public function getTotalVideos($data = array(), $library = false)
  {
    $this->db->where('is_deleted', '0');

    if($library)
    {
      $this->db->where('category_id > ', 0);
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
                  $whereStr .= "(".$this->db->dbprefix('video').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['c']) && $data['filter']['c'] > 0)
      {
        $this->db->where('video.category_id',$data['filter']['c']);
      }
    }

    return $this->db->get('video')->num_rows();
  }

  public function getVideo($video_id)
  {
    $this->db->select('video.video_id, video.type, video.name, video.description, video.image, video.youtube_video, video.mp4_video, video.is_active, video.created, DATE_FORMAT('.$this->db->dbprefix('video').'.created, "%c/%e/%Y %h:%i %p") AS created_date');
    $this->db->from('video');
    $this->db->where('video.video_id', $video_id);
    return $this->db->get()->row_array();
  }

  public function getVideoData($video_id)
  {
    $this->db->where('video_id', $video_id);
    return $this->db->get('video')->row_array();
  }

  public function saveVideo($data = array())
  {
    if(isset($data['video_id']) && ((int)$data['video_id'] > 0))
    {
      $this->db->where('video_id', (int)$data['video_id']);
      $this->db->update('video', $data);
      $video_id = $data['video_id'];
    }
    else
    {
      $this->db->insert('video', $data);
      $video_id = $this->db->insert_id();
    }
    
    return $video_id;
  }

  public function deleteVideo($video_id)
  {
    $this->db->where('video_id', (int)$video_id);
    $this->db->delete('video');
    
    return $video_id;
  }

  public function getDocuments($video_id)
  {
    $this->db->where('video_id', (int)$video_id);
    return $this->db->get('video_document')->result_array();
  }

  public function saveDocuments($video_id, $documents)
  {
    $save_batch = array();
    foreach($documents as $document)
    {
      $save_batch[] = array('video_id' => $video_id, 'file_name' => $document);
    }

    if($save_batch)
    {
      $this->db->insert_batch('video_document', $save_batch);
    }
  }

  public function deleteDocument($video_id, $file_name)
  {
    $this->db->where('video_id', (int)$video_id);
    $this->db->where('file_name', $file_name);
    $this->db->delete('video_document');
  }
}