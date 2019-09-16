<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {
	public function index()
	{
		$data = array('title' => 'About us');
		$this->load->view('includes/header', $data);
		$this->load->view('about');
		$this->load->view('includes/footer');
	}
}
