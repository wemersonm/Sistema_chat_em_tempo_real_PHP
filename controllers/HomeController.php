<?php 

	class HomeController extends Controller{
			
		private $users;

		public function __construct(){
	   		parent::__construct();

	   		$this->users = new Users();
	   		
	   		if(!$this->users->verifyLogin()){
	   			header("location:".BASE_URL.'login');
	   			exit;
	   		}

	   }
		public function index(){
			
			$dados = array();

			// chama a view que vai renderizar na index, que e a view home
			$this->loadTemplate("home",$dados);
			
		}

		
	}
 ?>