<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RouterController extends CI_Controller {

	public function index(){
		if($this->session->userdata(ADMIN_SESSION.'logged_in') == "1"){
			redirect(BASE_URL . "companies");
			return;
		} else {
			redirect(BASE_URL . "login");
			return;
		}
	}
	public function login(){
		$this->load->view('login');
	}
	public function authenticate(){
		$this->load->model('AuthenticationModal');
		echo json_encode($this->AuthenticationModal->login());
		return;
	}
	public function logout(){
		$this->load->model('AuthenticationModal');
		echo json_encode($this->AuthenticationModal->logout());
		return;
	}
	public function companies(){		
		$this->load->model('CompaniesModal');
		$data = array(
			"page_data" => array(
				'url' => "companies",
				"industries" => $this->CompaniesModal->getIndustries(),
				"states" => $this->CompaniesModal->getStates(),
			),
			"script_file" => "companies"
		);
		$this->basic_functions->render('companies', "Companies", $data);
	}
	public function cities(){
		$this->load->model('CompaniesModal');
		echo json_encode($this->CompaniesModal->getCities());
		return;
	}
	public function add_company(){
		$this->load->model('CompaniesModal');
		echo json_encode($this->CompaniesModal->addCompany());
		return;
	}
	public function get_companies(){
		$this->load->model('CompaniesModal');
		echo json_encode($this->CompaniesModal->getCompanies());
		return;
	}	
	public function view_company($company_id){
		$this->db->select("*");
		$this->db->where("company_udid", $company_id);
		$query = $this->db->get(TB_COMPANIES);
		$companies = $query->result_array();
		if(count($companies) == 0){
			redirect(BASE_URL . "companies");
			return;
		}
		$this->load->model('CompaniesModal');
		$data = array(
			"page_data" => array(
				'url' => "companies",
				"company_data" => $companies[0]
			),
			"script_file" => "companies_view"
		);
		$this->basic_functions->render('company_employees', $companies[0]['name'], $data);
		return;
	}	
	public function get_company_employees($company_id){
		$this->load->model('CompaniesModal');
		echo json_encode($this->CompaniesModal->getCompanyEmployees($company_id));
		return;
	}	
	public function employees(){		
		$this->load->model('CompaniesModal');
		$data = array(
			"page_data" => array(
				'url' => "employees",
			),
			"script_file" => "employees"
		);
		$this->basic_functions->render('employees', "Employees", $data);
	}
	public function add_employee($company_id){
		$this->db->select("*");
		$this->db->where("company_udid", $company_id);
		$query = $this->db->get(TB_COMPANIES);
		$companies = $query->result_array();
		if(count($companies) == 0){
			redirect(BASE_URL . "companies");
			return;
		}
		$this->load->model('CompaniesModal');
		echo json_encode($this->CompaniesModal->addEmployee($company_id));
		return;
	}
	public function get_all_employees(){
		$this->load->model('CompaniesModal');
		echo json_encode($this->CompaniesModal->getAllEmployees());
		return;
	}
	public function page_not_found(){
		$this->load->view('404_page');
	}
	
}
