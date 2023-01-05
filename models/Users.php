<?php 

	class Users extends Model{

		private $uid;

		public function verifyLogin(){
			if(isset($_SESSION['loggedInUser']) && !empty($_SESSION['loggedInUser'])){
				$session = $_SESSION['loggedInUser'];
				return $this->verifyHash($session); // == return true or false
			}
			return false;
		}

		public function verifyHash($session){

			$data = array();
			$stmt = $this->db->prepare("SELECT * FROM users WHERE loginhash=:hash");
			$stmt->bindValue(":hash",$session);
			$stmt->execute();
			if($stmt->rowCount() > 0){
				$data = $stmt->fetch();
				$this->uid = $data['id'];
				return true;
			}
			return false;
		}
		public function clearSession(){
			$_SESSION['loggedInUser'] = '';
		}
		public function getUid(){
			return $this->uid;
		}
		public function getNome($id){
			$array = array();
			$stmt= $this->db->prepare('SELECT username FROM chat.users WHERE id=:id');
			$stmt->bindValue(':id',$id);
			$stmt->execute();
			if($stmt->rowCount() > 0){
				$array = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			return $array['username'];
		}

		public function validateUsername($name){
			if(preg_match('/^[a-z0-9]+$/',$name)){
				return true;
			}
			return false;
		}
		public function existsUser($username){

			$stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
			$stmt->bindValue(":username",$username);
			$stmt->execute();
			if($stmt->rowCount() > 0){
				return true;
			}
			return false;
		}
		public function registerUser($username,$pass){
			$stmt = $this->db->prepare("INSERT INTO users(username,password) VALUES(:username,:pass)");
			$stmt->bindValue(":username",$username);
			$stmt->bindValue(":pass",$pass);
			if($stmt->execute()){
				return true;
			}
			return false;
		}
		public function doLogin($username,$pass){
			$dados =array();
			$stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
			$stmt->bindValue(":username",$username);
			$stmt->execute();
			if($stmt->rowCount() > 0){
				$dados = $stmt->fetch();
				if(password_verify($pass, $dados['password'])){
					$loginhash = md5(time().rand(0,99999).$dados['id']);
					if($this->setLoginHash($dados['id'],$loginhash)){
						$_SESSION['loggedInUser'] = $loginhash;
						return true;	
					}
					
				}
				
			}
			return false;
		}
		public function setLoginHash($uid,$loginhash){
			$stmt = $this->db->prepare("UPDATE users SET loginhash = :loginhash WHERE id = :uid");
			$stmt->bindValue(":loginhash",$loginhash);
			$stmt->bindValue(":uid",$uid);
			if($stmt->execute()){
				return true;
			}
			return false;
		}

		public function updateGroups($groups){
			$groupsString = '';
			if(count($groups) > 0){
				$groupsString = "!".implode("!",$groups)."!";
			}

			$stmt = $this->db->prepare("UPDATE chat.users SET users.last_update = NOW(), users.groups=:groups WHERE id=:id");
			$stmt->bindValue(':groups',$groupsString);
			$stmt->bindValue(':id',$this->uid);
			$stmt->execute();
		}
		public function clearGroups(){
			$stmt = $this->db->prepare("UPDATE chat.users SET users.groups='' WHERE users.last_update <
			 DATE_ADD(NOW(),INTERVAL -2 MINUTE)");
			$stmt->execute();
		}

		public function getUsersInGroup($idGroup){
			$array =array();
			$stmt = $this->db->prepare("SELECT username FROM chat.users WHERE users.groups LIKE :groups");
			$stmt->bindValue(':groups','%!'.$idGroup.'!%');
			$stmt->execute();
			if($stmt->rowCount() > 0){
				$list =  $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($list as $value) {
				    $array[] = $value['username'];	
				}
			}

			return $array;
		}

		public function getCurrentGroups(){
			$array = array();
			$stmt = $this->db->prepare('SELECT users.groups FROM chat.users WHERE id=:id');
			$stmt->bindValue(':id',$this->uid);
			$stmt->execute();

			$sql = $stmt->fetch(PDO::FETCH_ASSOC);
			$array = explode('!',$sql['groups']);
			if(count($array) > 0){
				array_pop($array);
				array_shift($array);	
				$groups = new Groups();
				$array = $groups->getNamesByArray($array);			
			}
			
			return $array;
			
		}

	}
 ?>