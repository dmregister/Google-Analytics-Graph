<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Google extends CI_Controller  {
	
	/*
	*Setup default user
	*
	*/
	private $user = 'dmregister1@gmail.com';
	private $pass = 'admin334';
	private $user_id = 40857948;
	
	
	/*
	*Load Google Analytics PHP Interface class
	*
	*/
	public function __construct(){
		parent::__construct();
      	require 'gapi.class.php';
    }
	
	/*
	*Loads homepage
	*
	*/
	function index(){
		$this->load->view('google_login');
	}
	
	/*
	*Basic form validation for the login form, Needs to be refined for security
	*
	*/
	function login_submit(){
		$this->form_validation->set_rules('username', 'Username', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$j=0;
			$form_error = array();
			foreach($this->form_validation->error_array() as $array){
				if($j == 0){
					$form_error['error'] = true;
					$j++;
				}
				$form_error[] = $array;
			}
			echo json_encode($form_error);
		}
		else
		{
			$this->session->set_userdata('username',set_value('username'));
			$this->session->set_userdata('password',set_value('password'));
			$this->get_user_id($this->session->userdata('username'), $this->session->userdata('password'));
		}
		
	}
	
	/*
	*Gets the account information for all google analytics under the logged in user
	*
	*/
	function get_user_id($username = null, $password = null){
	
		$ga = new gapi($username,$password);
		$ga->requestAccountData();
		$account_info = array('error'=>false);
		foreach($ga->getResults() as $result){
			$account_info[] = $result->getProfileId()."/".$result->getTitle();
		}
		//return account information for dropdown on login page
		echo json_encode($account_info);
	}
	
	/*
	*Check if the user is logged in and show analytics, else redirect To login
	*
	*/
	function logged_in(){
		$this->session->set_userdata('user_id', $this->input->post('user_id_list'));
		$username = $this->session->userdata('username');
		$password = $this->session->userdata('password');
		$user_id = $this->session->userdata('user_id');
		
		if($username == null || $password == null || $user_id == null){
			redirect('google/index');
		}
		
		$ga = new gapi($username,$password);

		$data['results'] = $ga->requestReportData($user_id,array('browser','browserVersion'),array('pageviews','visits'),'-visits');
		
		//can add multiple fitlering options for different return values
		
		//$filter = 'country == United States && browser == Firefox || browser == Chrome';
		//$data['results'] = $ga->requestReportData(40857948,array('browser','browserVersion'),array('pageviews','visits'),'-visits',$filter,'2011-05-25','2011-09-30');
		
		
		//Get all page views
		$data['total_views'] = $ga->getTotalResults();
		$data['total_pageviews'] = $ga->getPageviews();
		$data['total_visits'] = $ga->getVisits();
		$data['updated'] = $ga->getUpdated();
		
		//Setup vars
		$chrome_visits = 0;
		$chrome_views = 0;
		
		$firefox_visits = 0;
		$firefox_views = 0;
		
		$safari_visits = 0;
		$safari_views = 0;
		
		$ie_visits = 0;
		$ie_views = 0;
		
		foreach ($data['results'] as $result){
			if($result->getBrowser() == 'Chrome'){
				$chrome_visits += $result->getVisits(); 
				$chrome_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Firefox'){
				$firefox_visits += $result->getVisits(); 
				$firefox_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Internet Explorer'){
				$ie_visits += $result->getVisits(); 
				$ie_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Safari'){
				$safari_visits += $result->getVisits(); 
				$safari_views += $result->getPageviews();
			}
		}
		$json = array(
      		'label'=> array('Page Views', 'Visits'),
      		'updated' => array($ga->getUpdated()),
		      'values'=> array(
			      array(
			        'label'=> 'Chrome',
			        'values' => array($chrome_views, $chrome_visits) 
			      ),array(
			        'label' => 'Firefox',
			        'values'=> array($firefox_views, $firefox_visits) 
			      ),array(
			        'label' => 'Safari',
			        'values'=> array($safari_views, $safari_visits) 
			      ),array(
			        'label' => 'Internet Explorer',
			        'values'=> array($ie_views, $ie_visits) 
			      )
		     )
	     );

		//Encode array for use by javascript graph
	    $data['json'] = json_encode($json);
		
		$this->load->view('google_graph',$data);
	 }
	 
	 /*
	*Currently unsed funtion
	*
	*/
	 public function website_stats(){
		
		$this->config->load('admin_settings');
		
		$data['access_pages'] = explode(',',$this->config->item('active_tables'));
		$data['access_pages'] = array_filter($data['access_pages']);
		
		$data['stats_pages'] = array('Website Stats');
		$data['config'] = array('Settings');
		
		$data['base_url'] = base_url();
		$data['logo_url'] = $this->config->item('site_logo');
		
		$data['active_page'] = 'Website Stats';
		
		$data['username'] = $this->session->userdata('username');
		
		$ga = new gapi($this->user,$this->pass);

		$data['results'] = $ga->requestReportData($this->user_id,array('browser','browserVersion'),array('pageviews','visits'),'-visits');
		
		//$filter = 'country == United States && browser == Firefox || browser == Chrome';
		//$data['results'] = $ga->requestReportData(40857948,array('browser','browserVersion'),array('pageviews','visits'),'-visits',$filter,'2011-05-25','2011-09-30');
		
		$data['total_views'] = $ga->getTotalResults();
		$data['total_pageviews'] = $ga->getPageviews();
		$data['total_visits'] = $ga->getVisits();
		$data['updated'] = $ga->getUpdated();
		
		
		$chrome_visits = 0;
		$chrome_views = 0;
		
		$firefox_visits = 0;
		$firefox_views = 0;
		
		$safari_visits = 0;
		$safari_views = 0;
		
		$ie_visits = 0;
		$ie_views = 0;
		
		foreach ($data['results'] as $result){
			if($result->getBrowser() == 'Chrome'){
				$chrome_visits += $result->getVisits(); 
				$chrome_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Firefox'){
				$firefox_visits += $result->getVisits(); 
				$firefox_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Internet Explorer'){
				$ie_visits += $result->getVisits(); 
				$ie_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Safari'){
				$safari_visits += $result->getVisits(); 
				$safari_views += $result->getPageviews();
			}
		}
		$json = array(
      		'label'=> array('Page Views', 'Visits'),
      		'updated' => array($ga->getUpdated()),
		      'values'=> array(
			      array(
			        'label'=> 'Chrome',
			        'values' => array($chrome_views, $chrome_visits) 
			      ),array(
			        'label' => 'Firefox',
			        'values'=> array($firefox_views, $firefox_visits) 
			      ),array(
			        'label' => 'Safari',
			        'values'=> array($safari_views, $safari_visits) 
			      ),array(
			        'label' => 'Internet Explorer',
			        'values'=> array($ie_views, $ie_visits) 
			      )
		     )
	     );

	    $data['json'] = json_encode($json);
		
		$data['main_content'] = "admin/google_graph";
		$this->load->view('admin/template/admin_template',$data);
	
	}
	
	/*
	*Loads updated results for ajax request to update the graphs
	*
	*/
	function update(){
	 	
	 	
		$username = $this->session->userdata('username');
	 	$password = $this->session->userdata('password');
	 	$user_id = $this->session->userdata('user_id');
		
	 	
	 	if($username == null || $password == null $user_id == null){
			$username = this->user;
			$password = $this->pass;
			$user_id = $this->user_id;
		}
		
	 	$ga = new gapi($username,$password);

		$data['results'] = $ga->requestReportData($user_id,array('browser','browserVersion'),array('pageviews','visits'),'-visits');
		
		$chrome_visits = 0;
		$chrome_views = 0;
		
		$firefox_visits = 0;
		$firefox_views = 0;
		
		$safari_visits = 0;
		$safari_views = 0;
		
		$ie_visits = 0;
		$ie_views = 0;
		foreach ($data['results'] as $result){
			if($result->getBrowser() == 'Chrome'){
				$chrome_visits += $result->getVisits(); 
				$chrome_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Firefox'){
				$firefox_visits += $result->getVisits(); 
				$firefox_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Internet Explorer'){
				$ie_visits += $result->getVisits(); 
				$ie_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Safari'){
				$safari_visits += $result->getVisits(); 
				$safari_views += $result->getPageviews();
			}
		}
		$json = array(
			  'updated' => array($ga->getUpdated()),
		      'values'=> array(
			      array(
			        'label'=> 'Chrome',
			        'values' => array($chrome_views, $chrome_visits) 
			      ),array(
			        'label' => 'Firefox',
			        'values'=> array($firefox_views, $firefox_visits) 
			      ),array(
			        'label' => 'Safari',
			        'values'=> array($safari_views, $safari_visits) 
			      ),array(
			        'label' => 'Internet Explorer',
			        'values'=> array($ie_views, $ie_visits) 
			      )
		     )
	     );

	    echo json_encode($json);

	 }
	 
	 /*
	*Loads graph with default credentials, bypasses login
	*
	*/
	 function no_account(){
		
		$username = $this->user;
		$password = $this->pass;
		$user_id = $this->user_id;
		
		if($username == null || $password == null || $user_id == null){
			redirect('google/index');
		}
		
		$ga = new gapi($username,$password);

		$data['results'] = $ga->requestReportData($user_id,array('browser','browserVersion'),array('pageviews','visits'),'-visits');
		
		//$filter = 'country == United States && browser == Firefox || browser == Chrome';
		//$data['results'] = $ga->requestReportData(40857948,array('browser','browserVersion'),array('pageviews','visits'),'-visits',$filter,'2011-05-25','2011-09-30');
		
		
		$data['total_views'] = $ga->getTotalResults();
		$data['total_pageviews'] = $ga->getPageviews();
		$data['total_visits'] = $ga->getVisits();
		$data['updated'] = $ga->getUpdated();
		
		
		$chrome_visits = 0;
		$chrome_views = 0;
		
		$firefox_visits = 0;
		$firefox_views = 0;
		
		$safari_visits = 0;
		$safari_views = 0;
		
		$ie_visits = 0;
		$ie_views = 0;
		
		foreach ($data['results'] as $result){
			if($result->getBrowser() == 'Chrome'){
				$chrome_visits += $result->getVisits(); 
				$chrome_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Firefox'){
				$firefox_visits += $result->getVisits(); 
				$firefox_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Internet Explorer'){
				$ie_visits += $result->getVisits(); 
				$ie_views += $result->getPageviews();
			}elseif($result->getBrowser() == 'Safari'){
				$safari_visits += $result->getVisits(); 
				$safari_views += $result->getPageviews();
			}
		}
		$json = array(
      		'label'=> array('Page Views', 'Visits'),
      		'updated' => array($ga->getUpdated()),
		      'values'=> array(
			      array(
			        'label'=> 'Chrome',
			        'values' => array($chrome_views, $chrome_visits) 
			      ),array(
			        'label' => 'Firefox',
			        'values'=> array($firefox_views, $firefox_visits) 
			      ),array(
			        'label' => 'Safari',
			        'values'=> array($safari_views, $safari_visits) 
			      ),array(
			        'label' => 'Internet Explorer',
			        'values'=> array($ie_views, $ie_visits) 
			      )
		     )
	     );

	    $data['json'] = json_encode($json);
		$this->load->view('google_graph',$data);
	 }
	 
	 /*
	*Logout
	*
	*/
	 function logout(){
	 	$this->session->sess_destroy();
	 	redirect('google/index');
	 }
}
