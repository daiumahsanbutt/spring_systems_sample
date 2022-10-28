<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AuthenticationModal extends CI_Model {

    public function login(){
		$validationRules = array(
	        $this->basic_functions->createValidationRules("username", "Username", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("password", "Password", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	    );
	    $this->basic_functions->validateForm($validationRules);

		$this->db->select("name");
		$this->db->select("username");
		$this->db->select("password");
		$this->db->select("user_udid");
		$this->db->where("username", $_POST['username']);
		$query = $this->db->get(TB_ADMIN_USERS);
		$user_record = $query->result_array();
		
		if(count($user_record) == 0){
			return array(
				"status" => "0",
				"errors" => array(
					"login" => "Invalid Credentials."
				)
			);
		}

		$storedPassword = $user_record[0]['password'];
		$enteredPassword = $_POST['password'];
		if($this->bcrypt->checkPassword($enteredPassword, $storedPassword) != 1){
			return array(
				"status" => "0",
				"errors" => array(
					"login" => "Invalid Credentials."
				)
			);
		}

        $user_record = $user_record[0];
		$newdata = array( 
			ADMIN_SESSION.'name' => $user_record['name'],
			ADMIN_SESSION.'username' => $user_record['username'],
			ADMIN_SESSION.'user_udid' => $user_record['user_udid'],
			ADMIN_SESSION.'logged_in' => TRUE,
		); 

		$this->session->set_userdata($newdata);
		$response['status'] = 1;
		$response['text'] = "Logged in Successfully!";
		delete_cookie("spring_redirect_url");
		return $response;
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect(BASE_URL . "login");
		return;
	}
}

?>