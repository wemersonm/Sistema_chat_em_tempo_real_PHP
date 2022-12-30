<?php 

	class AjaxController extends Controller{
			
		private $users;

		public function __construct(){
	   		parent::__construct();

	   		$this->users = new Users();
	   		
	   		if(!$this->users->verifyLogin()){
	   			$array = array(
	   				'status' => '0'
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

		public function add_group(){
			$array = array('status' => '1','error' => '0');

			$groups = new Groups();

			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = strip_tags($_POST['name']);
				$groups->insertGroup($name);

			}else{
				$array['error'] = '1';
				$array['errorMsg'] = 'Nome do grupo não informado';
			}

			echo json_encode($array);
		}

		public function add_messages(){
			$messages = new Messages();
			$array = array('status'=> '1','error'=>'0');
			if(!empty($_POST['msg']) && !empty($_POST['id_group'])){
				$msg = strip_tags($_POST['msg']);
				$id_group = $_POST['id_group'];
				$uid = $this->users->getUid();
				$messages->insertMessage($uid,$id_group,$msg);
			}else{
				$array['error'] = '1';
				$array['errorMsg'] = 'Mensagem não informada';
			}

			echo json_encode($array);
			exit;
		}

		public function getMessages(){
			$array = array('status'=> '1');
			$messages = new Messages();

			set_time_limit(60);
			$ult_msg = date('Y-m-d H:i:s');
			if(!empty($_GET['last_time'])){
				$ult_msg = $_GET['last_time'];
			}
			echo json_encode($array);
			exit;
		}

		
	}
 ?>