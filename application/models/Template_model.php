<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Template_model extends CI_Model {

  var $CI;

  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('text');
    $this->CI->load->helper('format');
  }

  public function getTemplates($data = array())
  {
    $this->db->select('template.template_id, template.name, template.content');
    $this->db->from('template');

    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if(isset($data['filter']['q']) && !empty($data['filter']['q']))
      {
        if(isset($data['filter']['q']) && !empty($data['filter']['q']) && $this->Setting_model->parseSearchString($data['filter']['q'], $searchKeywords))
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
                  $whereStr .= "(".$this->db->dbprefix('template').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['template_type']) && !empty($data['filter']['template_type']))
      {
        $this->db->where('template_type', $data['filter']['template_type']);
      }
    }

    if(!empty($data['order_by']) && !empty($data['sort_order']))
    {
      $this->db->order_by($data['order_by'], $data['sort_order']);
    }
    else
    {
      $this->db->order_by('template.template_id', 'ASC');
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

  public function getTotalTemplates($data = array())
  {
    if(!empty($data['filter']) && is_array($data['filter']))
    {
      if(isset($data['filter']['q']) && !empty($data['filter']['q']))
      {
        if(isset($data['filter']['q']) && !empty($data['filter']['q']) && $this->Setting_model->parseSearchString($data['filter']['q'], $searchKeywords))
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
                  $whereStr .= "(".$this->db->dbprefix('template').".name LIKE \"%".trim($keyword)."%\")";
                  break;
              }
            }
            $whereStr .= ")";

            $this->db->where($whereStr, null);
          }
        }
      }

      if(isset($data['filter']['template_type']) && !empty($data['filter']['template_type']))
      {
        $this->db->where('template_type', $data['filter']['template_type']);
      }
    }

    return $this->db->get('template')->num_rows();
  }

  public function getTemplate($template_id)
  {
    $this->db->select('template.template_id, template.name, template.content');
    $this->db->from('template');
    $this->db->where('template.template_id', $template_id);
    return $this->db->get()->row_array();
  }

  public function getTemplateData($template_id)
  {
    $this->db->where('template_id', $template_id);
    return $this->db->get('template')->row_array();
  }

  public function saveTemplate($data = array())
  {
    if(isset($data['template_id']) && ((int)$data['template_id'] > 0))
    {
      $this->db->where('template_id', (int)$data['template_id']);
      $this->db->update('template', $data);
      $template_id = $data['template_id'];
    }
    else
    {
      $this->db->insert('template', $data);
      $template_id = $this->db->insert_id();
    }
    
    return $template_id;
  }
}