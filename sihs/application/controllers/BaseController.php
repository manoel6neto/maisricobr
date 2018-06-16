<?php
class BaseController extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		if ($this->session->userdata ( 'logged' ) === FALSE) {
			redirect ( 'in/login' );
		}
	}
}