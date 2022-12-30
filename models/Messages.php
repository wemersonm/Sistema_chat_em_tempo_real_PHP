<?php 

	class Messages extends Model{

		public function insertMessage($uid,$id_group,$msg){
			$stmt = $this->db->prepare('INSERT INTO chat.messages(id_user,id_group,date_msg,msg)
				VALUES(:id_user,:id_group,NOW(),:msg)');
			$stmt->bindValue(":id_user",$uid);
			$stmt->bindValue(":id_group",$id_group);
			$stmt->bindValue(":id_user",$uid);
			$stmt->bindValue(":msg",$msg);
			if($stmt->execute()){
				return true;
			}
			return false;
		}

		public function getMessages($last_time,$id_groups){
			$array = array();
			$stmt = $this->db->prepare('SELECT * FROM chat.messages WHERE date_msg > :date_msg AND 
				id_group IN('.implode(',',$id_groups).')';
			$stmt->bindValue(":date_msg",$last_time);
			$stmt->execute();
			if($stmt->rowCount()){
				$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			return $array;
		}


	}
 ?>		
