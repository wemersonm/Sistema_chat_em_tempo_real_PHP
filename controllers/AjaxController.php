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
		public function getUserList(){
			$array = array('status'=> '1', 'users'=>array());

			$groups = array();
			if(!empty($_GET['groups'])){
				$groups = $_GET['groups'];
			}
			$groups = explode(',',$groups);

			foreach ($groups as $key => $idGroup) {
				$array['users'][$idGroup] = $this->users->getUsersInGroup($idGroup);
			}

			echo json_encode($array);
			exit;
		}
		public function getMessages(){
			$array = array('status'=> '1', 'msgs'=>array(), 'last_time'=>date('Y-m-d H:i:s'));
			$messages = new Messages();

			set_time_limit(60);
			$ult_msg = date('Y-m-d H:i:s');
			if(!empty($_GET['last_time']) && strlen($_GET['last_time'] == 19) ){
				$ult_msg = $_GET['last_time'];
			}

			$groups = array();
			if(!empty($_GET['groups'])){
				$groups = $_GET['groups'];
			}
			$groups = explode(',',$groups);
			
			$this->users->updateGroups($groups);
			$this->users->clearGroups();

			while(true){
				session_write_close();
				$msgs = $messages->getMessage($ult_msg,$groups);

				if(count($msgs) > 0){
					$array['msgs'] = $msgs;
					$array['last_time'] = date('Y-m-d H:i:s'); 
					break;
				}else{
					sleep(2);
					continue;
				}
			}

			echo json_encode($array);
			exit;
		}

		public function add_photo(){
			$messages = new Messages();
			$array = array('status'=> '1','error'=>'0');
			if(!empty($_POST['id_group'])){
				$id_group = $_POST['id_group'];
				$uid = $this->users->getUid();

				$allowed = array('image/png','image/jpeg','image/jpg');
				if(!empty($_FILES['photo']['tmp_name'])){
					if(in_array($_FILES['photo']['type'], $allowed)){
						$nameArchive = md5(time().rand(0,9999)).'.jpg';
						$url = 'C:/xampp/htdocs/PHP/PROJETOS MASTERS/CHAT/assets/images/photos/';
						if(move_uploaded_file($_FILES['photo']['tmp_name'],$url.$nameArchive)){
							$messages->insertMessage($uid,$id_group,$nameArchive,'img');
						}

					}else{
						$array['error'] = '1';
						$array['errorMsg'] = 'Arquivo invalido';
					}
				}

				
			}else{
				$array['error'] = '1';
				$array['errorMsg'] = 'Grupo invalido';
			}

			echo json_encode($array);
			exit;
		}
	}
 ?>