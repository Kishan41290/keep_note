<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = array('title' => 'Note Maker');
		$uid = $this->common_model->get_userid();
		$data['get_all_notes'] = $this->common_model->get_all_records('user_notes', '*', array('UserId' => $uid, 'Status' => 'Enable'),'Id DESC' )->result();
		

		// print_r($data['get_all_notes'] );
		// exit;
		$this->load->view('includes/header', $data);
		$this->load->view('home');
		$this->load->view('includes/footer');
	}
}
