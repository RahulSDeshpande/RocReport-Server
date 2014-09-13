<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractor extends RR_Apicontractor {

	public function job($method = null) {
		switch ($method) {
			case 'fetch':
				$this->_get_jobs();
				break;
			case 'bid':
				$this->_bid_jobs();
				break;
			default:
				# code...
				break;
		}
	}

	/*
	 |--------------------------------------------------------------------------
	 | Api Methods
	 |--------------------------------------------------------------------------
	 */

	/**
	 * Get nearby jobs of some category
	 */
	private function _get_jobs() {
		$type = $this->input->get('type', true);
		$lat = $this->input->get('lat', true);
		$lng = $this->input->get('lng', true);
		$dis = $this->input->get('dist', true); 	// Distance input in km

		if(!$type)
			$this->_response_error(1);

		// If any other parameter is not supplied, use default
		if(!$lat)
			$lat = $this->contractor_data['latitude'];
		if(!$lng)
			$lng = $this->contractor_data['longitude'];
		if(!$dis)
			$dis = $this->contractor_data['radius'];

		$this->load->model('job_model', 'job');
		
		$jobs = $this->job->search_nearby_type($type, $lat, $lng, $dis);

		$this->_response_success($jobs);
	}

	/**
	 * Add a bid for a job
	 */
	private function _bid_job() {
		$job_id = $this->input->post('id', true);
		$amount = $this->input->post('amount', true);		// in USD
		$duration = $this->input->post('duration', true); 	// in days

		if(!$job_id || !$amount || !$duration)
			$this->_response_error(1);

		$this->load->model('job_model', 'job');
		$this->job->add_bid($job_id, $amount, $duration, $this->user_data['email']);

		$this->_response_success(array());
	}
}