<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Page_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getPages($data = array())
  {
    $this->db->select('page.page_id, page.title, page.slug, page.description');
    $this->db->from('page');
    $this->db->where('page.is_deleted', '0');

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
                  $whereStr .= "(".$this->db->dbprefix('page').".title LIKE \"%".trim($keyword)."%\")";
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
      $this->db->order_by('page.page_id', 'ASC');
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

  public function getTotalPages($data = array())
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
                  $whereStr .= "(".$this->db->dbprefix('page').".title LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }
    }

    return $this->db->get('page')->num_rows();
  }

  public function getPage($page_id)
  {
    $this->db->select('page.page_id, page.title, page.slug, page.description');
    $this->db->from('page');   
    $this->db->where('page.page_id', $page_id);
    return $this->db->get()->row_array();
  }

  public function getPageData($page_id)
  {
    $this->db->where('page_id', $page_id);
    return $this->db->get('page')->row_array();
  }

  public function savePage($data = array())
  {
    if(isset($data['page_id']) && ((int)$data['page_id'] > 0))
    {
      $this->db->where('page_id', (int)$data['page_id']);
      $this->db->update('page', $data);
      $page_id = $data['page_id'];
    }
    else
    {
      $this->db->insert('page', $data);
      $page_id = $this->db->insert_id();
    }
    
    return $page_id;
  }

  public function getPageBySlug($slug, $page_id = 0)
  {
    if($page_id)
    {
      $this->db->where('page_id <>', $page_id);
    }
    
    return $this->db->where('slug', $slug)->get('page')->row();
  }

  public function createPageSlug($slug, $page_id = 0, $count=0)
  {
    $page_row = $this->getPageBySlug($slug, $page_id);
    if($page_row)
    {
      $slug = $slug.(($count > 0) ? '-p'.$count : '');
      return $this->createPageSlug($slug, $page_id, $count++);
    }
    else
    {
      return $slug;
    }
  }
}