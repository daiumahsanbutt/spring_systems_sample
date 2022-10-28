<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use libphonenumber\PhoneNumberUtil;
class MY_Form_validation extends CI_Form_validation {
 	protected $CI;
 	function __construct() {
		parent::__construct();
	}
    function editUnique($value, $params) {
        $CI =& get_instance();
        $CI->load->database();
        list($table, $field, $compare_column, $current_id) = explode(".", $params);
        $query = $CI->db->select()->from($table)->where($field, $value)->limit(1)->get();
        $record = $query->result_array();
        if(count($record) > 0 && $record[0][$compare_column] != $current_id){
            $CI->form_validation->set_message('editUnique', "Sorry, $value is already in use.");
            return FALSE;
        }
        return TRUE;
    }
    public function validatePhoneNumber($phone_number, $country_id){
        $CI =& get_instance();
        $countryCode = 'US';
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phone_number_parsed = $phoneUtil->parse($phone_number, $countryCode);
            $isValid = $phoneUtil->isValidNumber($phone_number_parsed);
            if($isValid){
                $data['location_number'] = $phoneUtil->format($phone_number_parsed, \libphonenumber\PhoneNumberFormat::E164);
                return TRUE;
            } else {
                $CI->form_validation->set_message('validatePhoneNumber', $phone_number . ' is not a valid number for ' . $countryCode);
                return FALSE;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $CI->form_validation->set_message('validatePhoneNumber', 'There was an error validating the phone number.');
            return FALSE;
        }
    }
    public function valid_password($password = ''){
        $CI =& get_instance();
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';
        if (empty($password)){
            $CI->form_validation->set_message('valid_password', 'The {field} field is required.');
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1){
            $CI->form_validation->set_message('valid_password', 'The {field} field must have at least one lowercase letter.');
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1){
            $CI->form_validation->set_message('valid_password', 'The {field} field must have at least one uppercase letter.');
            return FALSE;
        }

        if (preg_match_all($regex_number, $password) < 1){
            $CI->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
            return FALSE;
        }   
        if (preg_match_all($regex_special, $password) < 1){
            $CI->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>ยง~'));
            return FALSE;
        }
        if (strlen($password) < 5){
            $CI->form_validation->set_message('valid_password', 'The {field} field must be at least 5 characters in length.');
            return FALSE;
        }
        if (strlen($password) > 32){
            $CI->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');
            return FALSE;
        }
        return TRUE;
    }
}