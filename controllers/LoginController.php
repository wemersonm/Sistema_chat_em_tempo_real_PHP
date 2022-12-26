<?php 

	class LoginController extends Controller{
			
		private $users;

		public function __construct(){
	   		parent::__construct();

	   		$this->users = new Users();
	   		
	   		if($this->users->verifyLogin()){
	   			header("location:".BASE_URL);
	   			exit;
	   		}
	   		

	   }
		public function index(){
			
			$dados = array();

			if(isset($_POST['submit']) && !empty($_POST['submit'])){
				$username = strip_tags($_POST['username']);
				$username = strtolower($username);
				$pass = $_POST['password'];
				if(!empty($username) && !empty($pass)){

					if($this->users->doLogin($username,$pass)){
						header("location:".BASE_URL);
						die;
					}else{
						$dados['error'] = "Username e/ou senha incorreto";
					}
				}else{
					$dados['error'] = "Preencha os campos";
				}

			}
			


			// chama a view que vai renderizar na index, que e a view home
			$this->loadView("login",$dados);
			
		}
		public function register(){
			
			$dados = array();
			if(isset($_POST['submit']) && !empty($_POST['submit'])){
				$username = strip_tags($_POST['username']);
				$username = strtolower($username);
				$pass = $_POST['password'];
				if(!empty($username) && !empty($pass)){
					if($this->users->validateUsername($username)){
					if(!$this->users->existsUser($username)){
						$pass = password_hash($pass,PASSWORD_DEFAULT);
						if($this->users->registerUser($username,$pass)){
							header("location:".BASE_URL.'login');
							die;
						}else{
							$dados['error'] = "Erro ao cadastrar usuario";
						}
					}else{
						$dados['error'] = "Nome de usuario já existente";
					}
				}else{
					$dados['error'] = "Username invalido (Digite apenas letras e numeros)";
				}
				
				}else{
					$dados['error'] = "Preencha os campos";
				}


			}

			

			
			// chama a view que vai renderizar na index, que e a view home
			$this->loadView("register",$dados);
			
		}

		
	}
 ?>