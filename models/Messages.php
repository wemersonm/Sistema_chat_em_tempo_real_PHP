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



	}
 ?>		
