<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Image_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getImages($data = array())
  {
    $this->db->select('image.image_id, image.type, image.position, image.name, image.description, image.image, image.url, image.start_date, image.end_date, image.is_active, image.created');
    $this->db->from('image');
    $this->db->where('image.is_deleted', '0');

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
                  $whereStr .= "(".$this->db->dbprefix('image').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('image.image_id', 'ASC');
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

  public function getTotalImages($data = array())
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
                  $whereStr .= "(".$this->db->dbprefix('image').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }
    }

    return $this->db->get('image')->num_rows();
  }

  public function getImage($image_id)
  {
    $this->db->select('image.image_id, image.type, image.position, image.name, image.description, image.image, image.url, image.start_date, image.end_date, image.is_active, image.created');
    $this->db->from('image');
    $this->db->where('image.image_id', $image_id);
    return $this->db->get()->row_array();
  }

  public function getImageData($image_id)
  {
    $this->db->where('image_id', $image_id);
    return $this->db->get('image')->row_array();
  }

  public function saveImage($data = array())
  {
    if(isset($data['image_id']) && ((int)$data['image_id'] > 0))
    {
      $this->db->where('image_id', (int)$data['image_id']);
      $this->db->update('image', $data);
      $image_id = $data['image_id'];
    }
    else
    {
      $this->db->insert('image', $data);
      $image_id = $this->db->insert_id();
    }
    
    return $image_id;
  }

  public function deleteImage($image_id)
  {
    $this->db->where('image_id', (int)$image_id);
    $this->db->delete('image');
    
    return $image_id;
  }
}