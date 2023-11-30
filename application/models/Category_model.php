<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Category_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getCategories($data = [])
  {
    $this->db->select('category.category_id, category.name, category.price, category.created');
    $this->db->from('category');
    $this->db->where('category.is_deleted', '0');

    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if(isset($data['filter']['name']) && !empty($data['filter']['name']))
      {
        if(isset($data['filter']['name']) && !empty($data['filter']['name']) && $this->Setting_model->parseSearchString($searchKeywords, $data['filter']['name']))
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
                  $whereStr .= "(".$this->db->dbprefix('category').".name LIKE \"%".trim($keyword)."%\")";
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
      $this->db->order_by('category.name', 'ASC');
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

  public function getTotalCategories($data = [])
  {
    $this->db->where('is_deleted', '0');

    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if(isset($data['filter']['name']) && !empty($data['filter']['name']))
      {
        if(isset($data['filter']['name']) && !empty($data['filter']['name']) && $this->Setting_model->parseSearchString($searchKeywords, $data['filter']['name']))
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
                  $whereStr .= "(".$this->db->dbprefix('category').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }
    }

    return $this->db->get('category')->num_rows();
  }

  public function getCategory($category_id)
  {
    $this->db->select('category.category_id, category.name, category.price, category.created');
    $this->db->from('category');
    $this->db->where('category.category_id', $category_id);
    return $this->db->get()->row_array();
  }

  public function getCategoryByName($category_name, $category_id = 0)
  {
    if($category_id > 0)
    {
      $this->db->where('category.category_id !=', $category_id);
    }
    $this->db->where('category.is_deleted', 0);
    $this->db->like('category.name', $category_name);
    return $this->db->get('category')->num_rows();
  }

  public function getCategoryData($category_id)
  {
    $this->db->where('category_id', $category_id);
    return $this->db->get('category')->row_array();
  }

  public function saveCategory($data = [])
  {
    if(isset($data['category_id']) && ((int)$data['category_id'] > 0))
    {
      $this->db->where('category_id', (int)$data['category_id']);
      $this->db->update('category', $data);
      $category_id = $data['category_id'];
    }
    else
    {
      $this->db->insert('category', $data);
      $category_id = $this->db->insert_id();
    }
    
    return $category_id;
  }

  public function deleteCategory($category_id)
  {
    $this->db->where('category_id', (int)$category_id);
    $this->db->update('category', array('is_deleted' => 1, 'deleted' => date('Y-m-d H:i:s')));
    
    return $category_id;
  }
}