<?php 

	class Groups extends Model{

		public function getListGroups(){

			$array = array();
			$stmt = $this->db->prepare("SELECT * FROM chat.groups ORDER BY name ASC");
			$stmt->execute();
			if($stmt->rowCount() > 0){
				$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			return $array;
		}

		public function insertGroup($name){

			$stmt = $this->db->prepare("INSERT INTO chat.groups (name) VALUES(:name)");
			$stmt->bindValue(":name",$name);
			if($stmt->execute()){
				return true;
			}
			return false;
		}
		
	}
 ?>