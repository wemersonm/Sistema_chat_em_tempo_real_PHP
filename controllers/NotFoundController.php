<?php 

	class NotFoundController extends Controller{
			
	   
		public function index(){
			
			$dados = array();

			// chama a view que vai renderizar na index, que e a view home
			$this->loadView("404",$dados);
			
		}

		
	}
 ?>