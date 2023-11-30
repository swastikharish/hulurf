<?php
class Location_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getStates()
	{
		return $this->db->order_by('name', 'ASC')->get('state')->result();
	}

	function getState($state_id)
	{
		$this->db->where('state_id', $state_id);
		$this->db->where('status', 1);
		return $this->db->get('state')->row();
	}

}	