<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

	function __construct() {
		$this->table = $this->config->item('table');
	}

	// Get open reports
	function get_reports($email) {
		$areas = $this->get_admin_areas($email);

		$reports = array();

		foreach ($areas as $area) {
			if(!is_null($area->country)) $this->db->where('country', $area->country);
			if(!is_null($area->admin_level_1)) $this->db->where('admin_area_level_1', $area->admin_level_1);
			if(!is_null($area->admin_level_2)) $this->db->where('admin_area_level_2', $area->admin_level_2);
			if(!is_null($area->sublocality)) $this->db->where('locality', $area->sublocality);
			$this->db->where('closed', 0);
			$query = $this->db->get($this->table['report']);

			$reports = array_merge($reports, $query->result());
		}

		return $reports;
	}

	// Get closed reports
	function get_reports_closed($email) {
		$areas = $this->get_admin_areas($email);

		$reports = array();

		foreach ($areas as $area) {
			if(!is_null($area->country)) $this->db->where('country', $area->country);
			if(!is_null($area->admin_level_1)) $this->db->where('admin_area_level_1', $area->admin_level_1);
			if(!is_null($area->admin_level_2)) $this->db->where('admin_area_level_2', $area->admin_level_2);
			if(!is_null($area->sublocality)) $this->db->where('locality', $area->sublocality);
			$this->db->where('closed', 1);
			$this->db->order_by('added_at', 'DESC');
			$this->db->limit(100);
			$query = $this->db->get($this->table['report']);

			$reports = array_merge($reports, $query->result());
		}

		return $reports;
	}

	function get_admin_areas($email) {
		$this->db->where('email', $email);
		$query = $this->db->get($this->table['admin']);

		return $query->result();
	}
}