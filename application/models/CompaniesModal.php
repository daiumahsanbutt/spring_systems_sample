<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CompaniesModal extends CI_Model {

	public function getIndustries(){
		$this->db->select("*");
		$query = $this->db->get(TB_INDUSTRIES);
		return $query->result_array();
	}
	public function getStates(){
		$this->db->select("id");
		$this->db->select("name");
		$this->db->order_by("name");
		$this->db->where('country_id', 233);
		$query = $this->db->get(TB_STATES);
		return $query->result_array();
	}
	public function getCities(){
		$this->db->select("id");
		$this->db->select("name");
		$this->db->order_by("name");
		$this->db->where('state_id', $_POST['state']);
		$query = $this->db->get(TB_CITIES);
		return $query->result_array();
	}	
	public function addCompany(){
		$validationRules = array(
	        $this->basic_functions->createValidationRules("industry", "Industry", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("company_name", "Company Name", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("street_1", "Street Address 1", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("state", "State", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("city", "City", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("zip", "Zip Code", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("phone", "Phone Number", 'trim|required|xss_clean|validatePhoneNumber[233]', array('required' => '%s can not be empty')),
	    );    
	    $this->basic_functions->validateForm($validationRules);	
		$insert_history = $this->basic_functions->generateRecordHistoryInsert();
        $company_record = array(
            "name" => $_POST['company_name'],
            "address_1" => $_POST['street_1'],
            "address_2" => $_POST['street_2'],
            "city" => $_POST['city'],
            "state" => $_POST['industry'],
            "zip" => $_POST['zip'],
            "phone" => $this->basic_functions->formatPhoneNumber($this->input->post('phone',true)),			
            "email" => $_POST['email_address'],
            "industry" => $_POST['industry'],			
            "company_udid" => $insert_history['udid'],
            "created_date" => $insert_history['datestamp'],
        );
		$query = $this->db->insert(TB_COMPANIES, $company_record);
        if(!$query){
            return array(
                "status" => 0,
                "errors" => array(
                    "invoice" => "There was an error adding company. Please try again or contact support."
                ),
                "error" => $this->db->error()
            );
        }
		return array(
            "status" => 1,
            "text" => "Company created successfully."
        );
	}
	public function getCompanies(){
        $companies = array();
        $total_records = 0;
        $filteredRecords = 0;
        
		
		$columns = [
			TB_COMPANIES . '.name',
			'COUNT(employees.id)',
			"state_name",
			"city_name",
			"",
		];
		$this->db->select('COUNT(*) as totalEntries');
		$query = $this->db->get(TB_COMPANIES);
		$total_records = $query->result_array()[0]['totalEntries'];



		$this->db->select('COUNT(*) as totalEntries');
		if(isset($_POST['search']['value']) && $_POST['search']['value'] != ""){
			$searchPhrase = $_POST['search']['value'];
			$this->db->like(TB_COMPANIES . '.name', trim($searchPhrase)); 
		}     
		$query = $this->db->get(TB_COMPANIES);
		$filteredRecords = $query->result_array()[0]['totalEntries'];


		$this->db->select(TB_COMPANIES . ".name as company_name");
		$this->db->select(TB_COMPANIES . ".company_udid as company_udid");
		$this->db->select(TB_COMPANIES . ".state as state_id");
		$this->db->select(TB_COMPANIES . ".city as city_id");
		$this->db->select("states.name as state_name");
		$this->db->select("cities.name as city_name");
		$this->db->select("COUNT(employees.id) as total_employees");
		$this->db->from(TB_COMPANIES);
        $this->db->join(TB_EMPLOYEES . " as employees", "employees.company_udid = " . TB_COMPANIES . ".company_udid", "left");
        $this->db->join(TB_STATES . " as states", "states.id = " . TB_COMPANIES . ".state", "left");
        $this->db->join(TB_CITIES . " as cities", "cities.id = " . TB_COMPANIES . ".city", "left");
		if(isset($_POST['search']['value']) && $_POST['search']['value'] != ""){
			$searchPhrase = $_POST['search']['value'];
			$this->db->like(TB_COMPANIES . '.name', trim($searchPhrase)); 
		}     
		if(isset($_POST['order'])){
			$orderSettings = $_POST['order'];
			foreach($orderSettings as $order){
				$index = $order['column'];
				$direction = $order['dir'];
				$this->db->order_by($columns[$index] . " " . $direction); 
			}
		} else {
			$this->db->order_by(TB_COMPANIES . ".name");
		}          
		if(isset($_POST['length']) && $_POST['length'] != -1){
			$this->db->limit($_POST['length'], $_POST['start']);
		} else {
			$this->db->limit(10, 0);
		}
		$query = $this->db->get();
		$companies = $query->result_array();

		if(count($companies) == 1){
			if($companies[0]['company_name'] == null){
				$companies = array();
			}
		}
        $output = array(
            "recordsTotal" => $total_records,
            "recordsFiltered" => $filteredRecords,
            "data" => $companies,
        );
        return $output;
	}	
	public function getCompanyEmployees($company_udid){
        $total_records = 0;
        $filteredRecords = 0;        
		
		$columns = [
			'first_name',
			'last_name',
			'phone',
			"email",
			"salary",
			"",
		];
		$this->db->select('COUNT(*) as totalEntries');
		$this->db->where('company_udid', $company_udid);
		$query = $this->db->get(TB_EMPLOYEES);
		$total_records = $query->result_array()[0]['totalEntries'];



		$this->db->select('COUNT(*) as totalEntries');
		if(isset($_POST['search']['value']) && $_POST['search']['value'] != ""){
			$searchPhrase = $_POST['search']['value'];
			$this->db->like('first_name', trim($searchPhrase)); 
			$this->db->like('last_name', trim($searchPhrase)); 
			$this->db->like('CONCAT(first_name, " ", last_name)', trim($searchPhrase)); 
			$this->db->like('CONCAT(last_name, " ", first_name)', trim($searchPhrase)); 
		}     
		$this->db->where('company_udid', $company_udid);
		$query = $this->db->get(TB_EMPLOYEES);
		$filteredRecords = $query->result_array()[0]['totalEntries'];


		$this->db->select("employee_udid");
		$this->db->select("first_name");
		$this->db->select("last_name");
		$this->db->select("phone");
		$this->db->select("email");
		$this->db->select("salary");
		if(isset($_POST['search']['value']) && $_POST['search']['value'] != ""){
			$searchPhrase = $_POST['search']['value'];
			$this->db->like('first_name', trim($searchPhrase)); 
			$this->db->like('last_name', trim($searchPhrase)); 
			$this->db->like('CONCAT(first_name, " ", last_name)', trim($searchPhrase)); 
			$this->db->like('CONCAT(last_name, " ", first_name)', trim($searchPhrase)); 
		}        
		if(isset($_POST['order'])){
			$orderSettings = $_POST['order'];
			foreach($orderSettings as $order){
				$index = $order['column'];
				$direction = $order['dir'];
				$this->db->order_by($columns[$index] . " " . $direction); 
			}
		} else {
			$this->db->order_by("first_name");
			$this->db->order_by("last_name");
		}          
		if(isset($_POST['length']) && $_POST['length'] != -1){
			$this->db->limit($_POST['length'], $_POST['start']);
		} else {
			$this->db->limit(10, 0);
		}
		$this->db->where('company_udid', $company_udid);
		$query = $this->db->get(TB_EMPLOYEES);
		$companies = $query->result_array();
        $output = array(
            "recordsTotal" => $total_records,
            "recordsFiltered" => $filteredRecords,
            "data" => $companies,
        );
        return $output;
	}
	public function getAllEmployees(){
        $total_records = 0;
        $filteredRecords = 0;        
		
		$columns = [
			'companies.name',
			'first_name',
			'last_name',
			'phone',
			"email",
			"salary",
			"",
		];
		$this->db->select('COUNT(*) as totalEntries');
		$query = $this->db->get(TB_EMPLOYEES);
		$total_records = $query->result_array()[0]['totalEntries'];

		$this->db->select('COUNT(*) as totalEntries');
		if(isset($_POST['search']['value']) && $_POST['search']['value'] != ""){
			$searchPhrase = $_POST['search']['value'];
			$this->db->like('first_name', trim($searchPhrase)); 
			$this->db->like('last_name', trim($searchPhrase)); 
			$this->db->like('CONCAT(first_name, " ", last_name)', trim($searchPhrase)); 
			$this->db->like('CONCAT(last_name, " ", first_name)', trim($searchPhrase)); 
		}     
		$query = $this->db->get(TB_EMPLOYEES);
		$filteredRecords = $query->result_array()[0]['totalEntries'];

		$this->db->select("companies.name as company_name");
		$this->db->select("emp.employee_udid");
		$this->db->select("emp.first_name");
		$this->db->select("emp.last_name");
		$this->db->select("emp.phone");
		$this->db->select("emp.email");
		$this->db->select("emp.salary");
		if(isset($_POST['search']['value']) && $_POST['search']['value'] != ""){
			$searchPhrase = $_POST['search']['value'];
			$this->db->like('first_name', trim($searchPhrase)); 
			$this->db->like('last_name', trim($searchPhrase)); 
			$this->db->like('CONCAT(first_name, " ", last_name)', trim($searchPhrase)); 
			$this->db->like('CONCAT(last_name, " ", first_name)', trim($searchPhrase)); 
		}        
		if(isset($_POST['order'])){
			$orderSettings = $_POST['order'];
			foreach($orderSettings as $order){
				$index = $order['column'];
				$direction = $order['dir'];
				$this->db->order_by($columns[$index] . " " . $direction); 
			}
		} else {
			$this->db->order_by("first_name");
			$this->db->order_by("last_name");
		}          
		if(isset($_POST['length']) && $_POST['length'] != -1){
			$this->db->limit($_POST['length'], $_POST['start']);
		} else {
			$this->db->limit(10, 0);
		}
		$this->db->from(TB_EMPLOYEES . " emp");
        $this->db->join(TB_COMPANIES . " as companies", "companies.company_udid = emp.company_udid", "left");
		$query = $this->db->get(TB_EMPLOYEES);
		$companies = $query->result_array();
        $output = array(
            "recordsTotal" => $total_records,
            "recordsFiltered" => $filteredRecords,
            "data" => $companies,
        );
        return $output;
	}

	public function addEmployee($company_udid){
		$validationRules = array(
	        $this->basic_functions->createValidationRules("first_name", "First Name", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("last_name", "Last Name", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("salary", "Salary", 'trim|required|xss_clean', array('required' => '%s can not be empty')),
	        $this->basic_functions->createValidationRules("phone", "Phone Number", 'trim|required|xss_clean|validatePhoneNumber[233]', array('required' => '%s can not be empty')),
	    );    
	    $this->basic_functions->validateForm($validationRules);	
		$insert_history = $this->basic_functions->generateRecordHistoryInsert();
        $employee_record = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "salary" => $_POST['salary'],
            "phone" => $this->basic_functions->formatPhoneNumber($this->input->post('phone',true)),			
            "email" => $_POST['email_address'],		
            "company_udid" => $company_udid,	
            "employee_udid" => $insert_history['udid'],
            "created_date" => $insert_history['datestamp'],
        );
		$query = $this->db->insert(TB_EMPLOYEES, $employee_record);
        if(!$query){
            return array(
                "status" => 0,
                "errors" => array(
                    "invoice" => "There was an error adding employee. Please try again or contact support."
                ),
                "error" => $this->db->error()
            );
        }
		return array(
            "status" => 1,
            "text" => "Emplpyee created successfully."
        );

	}
}

?>