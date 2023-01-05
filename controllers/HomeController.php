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
			$dados['currentGroups'] = $this->users->getCurrentGroups();
			$dados['name'] = $this->users->getNome($this->users->getUid());
			// print_r($dados['currentGroups']);exit;
			// chama a view que vai renderizar na index, que e a view home
			$this->loadTemplate("home",$dados);
			
		}
		public function logout(){
			$this->users->clearSession();
			header('location:'.BASE_URL.'login');
			exit;
		}

		
	}
 ?>