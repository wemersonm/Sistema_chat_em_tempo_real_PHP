<?php 

	class AjaxController extends Controller{
			
		private $users;

		public function __construct(){
	   		parent::__construct();

	   		$this->users = new Users();
	   		
	   		if(!$this->users->verifyLogin()){
	   			$array = array(
	   				status => '0'
	   				);
	   			echo json_encode($array);
	   			exit;
	   		}

	   }
		public function index(){
			
			$dados = array();

			// chama a view que vai renderizar na index, que e a view home
			$this->loadTemplate("home",$dados);
			
		}

		public function get_groups(){
			$array = array('status' => '1');
			$groups = new Groups();

			$array['listGroups'] = $groups->getListGroups();
			echo json_encode($array);
			exit;
		}

		
	}
 ?>