<?php

class Route_model extends CI_Model {
	function __construct()
	{
		parent::__construct();		
	}

	function saveRoute($route)
	{
		if(!empty($route['route_id']))
		{
			$this->db->where('route_id', $route['route_id']);
			$this->db->update('route', $route);
			
			return $route['route_id'];
		}
		else
		{
			$this->db->insert('route', $route);
			return $this->db->insert_id();
		}
	}
	
	function checkSlug($slug, $route_id = 0)
	{
		if($route_id)
		{
			$this->db->where('route_id !=', $route_id);
		}

		$this->db->where('slug', $slug);
		
		return (bool) $this->db->count_all_results('route');
	}
	
	function validateSlug($slug, $route_id = 0, $count = 0)
	{
		if($this->checkSlug($slug, $route_id))
		{
			if(!$count)
			{
				$count	= 1;
			}
			else
			{
				$count++;
			}

			return $this->validateSlug($slug, $route_id, $count);
		}
		else
		{
			return $slug.(($count > 0) ? '-r'.$count : '');
		}
	}
	
	function deleteRoute($route_id)
	{
		$this->db->where('route_id', $route_id);
		$this->db->delete('route');
	}
}