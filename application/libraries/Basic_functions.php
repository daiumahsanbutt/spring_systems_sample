<?php

use libphonenumber\PhoneNumberUtil;
	class Basic_functions {

		protected $layout = 'layout/layout';
		public function render($file, $pageTitle, $sourcedData){
			$CI =& get_instance();
			$page_data = array();
			if(isset($sourcedData['page_data'])){
				$page_data = $sourcedData['page_data'];
			}
			$data['pageTitle'] = $pageTitle;
			$data['body'] = $CI->load->view($file,$page_data, TRUE);
			if(isset($sourcedData['script_file'])){
				$data['script_file'] = $sourcedData['script_file'];
			}
			$CI->load->view($this->layout, $data);
			return;		
		}	
		public function authenticateUser(){
			$CI =& get_instance();
			$current_url = str_replace("index.php/", "", current_url());
			$pageAccessed = $CI->router->fetch_method();
			if($pageAccessed == 'login' && $CI->session->userdata(ADMIN_SESSION.'logged_in') == "1"){
				redirect(BASE_URL . "companies");
				return;
			} else if($pageAccessed != 'login' && $CI->session->userdata(ADMIN_SESSION.'logged_in') != "1"){
				$CI->input->set_cookie("spring_redirect_url", $current_url, "3600");
				redirect(BASE_URL . "login");
				return;
			}		
		}
		public function getCountries($noList = 0){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("name");
			$portalDB->order_by("name", "asc");
			$query = $portalDB->get(TB_COUNTRIES);
			$countries = $query->result_array();
			return $countries;
		}
		public function getCountryName($country_id){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("name");
			$portalDB->where("id", $country_id);
			$query = $portalDB->get(TB_COUNTRIES);
			$countries = $query->result_array()[0]['name'];
			return $countries;
		}
		public function getStates($country_id = null){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("name");
			$portalDB->select("country_id");
			if($country_id != null){
				$portalDB->where("country_id", $country_id);
			}
			$portalDB->order_by("name", "asc");
			$query = $portalDB->get(TB_STATES);
			$states = $query->result_array();
			return $states;
		}
		public function getStateName($state_id){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("name");
			$portalDB->where("id", $state_id);
			$query = $portalDB->get(TB_STATES);
			$states = $query->result_array()[0]['name'];
			return $states;
		}
		public function getCities($country_id, $state_id = null){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("name");
			$portalDB->select("latitude");
			$portalDB->select("longitude");
			$portalDB->select("country_id");
			$portalDB->select("state_id");
			$portalDB->where("country_id", $country_id);
			if($state_id != null){
				$portalDB->where("state_id", $state_id);
			}
			$portalDB->order_by("name", "asc");
			$query = $portalDB->get(TB_CITIES);
			$cities = $query->result_array();
			return $cities;
		}
		public function getCityName($city_id){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("name");
			$portalDB->where("id", $city_id);
			$query = $portalDB->get(TB_CITIES);
			$cities = $query->result_array()[0]['name'];
			return $cities;
		}
		public function getTimeZones(){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("id");
			$portalDB->select("country_code");
			$portalDB->select("timezone");
			$portalDB->select("gmt_offset");
			$portalDB->order_by("gmt_offset", "asc");
			$query = $portalDB->get(TB_TIME_ZONES);
			$time_zones = $query->result_array();
			return $time_zones;
		}
		public function getCurrencies(){
			$CI =& get_instance();
			$portalDB = $CI->load->database('default', TRUE);
			$portalDB->select("*");
			$portalDB->order_by("currency_name", "asc");
			$query = $portalDB->get(TB_CURRENCIES);
			$currencies = $query->result_array();
			return $currencies;
		}
		public function getCountryPhoneCode($country_id = null){
			$CI =& get_instance();
			$parentDB = $CI->load->database('default', TRUE);
			$parentDB->select("b.country_code");
			$parentDB->from(TB_COUNTRIES . ' a');
			$parentDB->where('a.id', $country_id);	
			$query = $parentDB->get();
			$country_code = $query->result_array()[0]['country_code'];		
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
			return $phoneNumberUtil->getCountryCodeForRegion($country_code);
		}
		public function createValidationRules($field, $label, $rules, $errors){
			return array(
				'field' => $field,
				'label' => $label,
				'rules' => $rules,
				'errors' => $errors,
			);
		}
		public function formValidationErrors($validationRules){
			$errors = array();
			foreach($validationRules as $rule){
				$errors[$rule['field']] = strip_tags(form_error($rule['field']));
			}
			return $errors;
		}
		public function validateForm($validationRules){
			$CI =& get_instance();
			$CI->load->database();
			$CI->form_validation->set_rules($validationRules);
			if ($CI->form_validation->run() == FALSE){
				$Message = json_encode(array(
					"status" => 0,
					"errors" => $this->formValidationErrors($validationRules)
				));
				echo $Message;
				exit;
			}
		}
		public function formatPhoneNumber($phone_number){
			$countryCode = 'US';
			$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			$phone_number_parsed = $phoneUtil->parse($phone_number, $countryCode);
			return $phoneUtil->format($phone_number_parsed, \libphonenumber\PhoneNumberFormat::E164);
		}
		public function generateSyncID($len){
			$arr = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$len = $len - 14;
			$ans = ''; 
			$maxIndex = strlen($arr);
			for ($i = $len; $i > 0; $i--) {
				$ans .= substr($arr, floor(rand(0,$maxIndex)), 1);
			} 
			$date = new DateTime(date ('Y-m-d H:i:s'), new DateTimeZone('UTC'));
            $ans = substr($ans, 0, 3) . $date->format('YmdHis') . substr($ans, 3);
			return $ans;
		}
		public function generateSyncIDNum($len){
			$arr = '123456789';
			$len = $len - 14;
			$ans = ''; 
			$maxIndex = strlen($arr);
			for ($i = $len; $i > 0; $i--) {
				$ans .= substr($arr, floor(rand(0,$maxIndex)), 1);
			} 
			$date = new DateTime(date ('Y-m-d H:i:s'), new DateTimeZone('UTC'));
            $ans = substr($ans, 0, 3) . $date->format('YmdHis');
			return $ans;
		}
		public function generateRecordHistoryInsert(){
			$CI =& get_instance();
			$date = new DateTime(date ('Y-m-d H:i:s'), new DateTimeZone('UTC'));
			$date = $date->format('Y-m-d H:i:s');
			$sync_id = $this->generateSyncID(20);
			$insertInfo = array(
				"udid" => $sync_id,
				"datestamp" => $date,
			);
			return $insertInfo;
		}
	}