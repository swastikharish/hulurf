<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination
{
  var $bootstrap_version = 3;
	function __construct($parameters = [])
	{
		parent::__construct();

    $this->bootstrap_version = isset($parameters['bootstrap_version']) ? $parameters['bootstrap_version'] : 3;

    if ($this->bootstrap_version > 4) {
  		$this->full_tag_open		= '<ul class="pagination justify-content-center">';
  		$this->full_tag_close		= '</ul>';

  		$this->first_tag_open		= '<li class="page-item">';
  		$this->first_tag_close	= '</li>';

      $this->first_link 			= 'First';
      $this->last_link 				= 'Last';
      
      $this->next_link 				= '<span aria-hidden="true">&raquo;</span>';
      $this->prev_link 				= '<span aria-hidden="true">&laquo;</span>';
     
      $this->last_tag_open 		= '<li class="page-item">';
      $this->last_tag_close 	= '</li>';

      
      $this->next_tag_open 		= '<li class="page-item">';
      $this->next_tag_close 	= '</li>';


      $this->prev_tag_open 		= '<li class="page-item">';
      $this->prev_tag_close 	= '</li>';

      $this->cur_tag_open 		= '<li class="page-item active"><a href="#" class="page-link">';
      $this->cur_tag_close 		= '</a></li>';

      $this->num_tag_open 		= '<li class="page-item">';
      $this->num_tag_close		= '</li>';

      $this->num_links 				= 1;
    	$this->use_page_numbers	= true;

    	$this->reuse_query_string 	= TRUE;
  		$this->page_query_string 		= TRUE;
  		$this->query_string_segment = 'page';
    } else {
      $this->full_tag_open    = '<ul class="pagination pagination-md">';
      $this->full_tag_close   = '</ul>';

      $this->first_tag_open   = '<li>';
      $this->first_tag_close  = '</li>';

      $this->first_link       = 'First';
      $this->last_link        = 'Last';
      
      $this->next_link        = '<i class="fa fa-chevron-right"></i>';
      $this->prev_link        = '<i class="fa fa-chevron-left"></i>';
     
      $this->last_tag_open    = '<li>';
      $this->last_tag_close   = '</li>';

      
      $this->next_tag_open    = '<li>';
      $this->next_tag_close   = '</li>';


      $this->prev_tag_open    = '<li>';
      $this->prev_tag_close   = '</li>';

      $this->cur_tag_open     = '<li class="active"><a href="">';
      $this->cur_tag_close    = '</a></li>';

      $this->num_tag_open     = '<li>';
      $this->num_tag_close    = '</li>';

      $this->num_links        = 1;
      $this->use_page_numbers = true;

      $this->reuse_query_string   = TRUE;
      $this->page_query_string    = TRUE;
      $this->query_string_segment = 'page';
    }
	}
}
