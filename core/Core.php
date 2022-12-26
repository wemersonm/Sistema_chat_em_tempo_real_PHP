<?php 

	class Core{
		/*
		 Estrutura padrão que faz o carregamento do controler de forma dinamica.
		 Ao acessar uma pagina www.meusite.com/produto
		 Está acessando o produtoController.php <=> Dentro do produtoController e criado funções
		 que e chamado de ACTION: ex. abrir() <=> que e reponsavel por abrir um produto.
		 E tem o parametro, que o i ID do produto.
		 www.meusite.com/produto/abrir/4 
		 => O controller vai ser o produto => produtoController 
		 => O abrir vai ser a ACTION que e a função que será executada
		 => 4 e o parametro <=> id do produto
		 Então nessa url vai ser acessado a pagina de produtos que acessar um produto então =>
		 abrir um produto do id 4
		 1=controller
		 2=action
		 3,4,5 .... são os parametros 

		
		*/
		public function run(){
		
		$url = '/';
		if(isset($_GET['url'])){ //colocando '/' na frente da url == /produto/abrir/54
			$url .= $_GET['url'];
		}
		$params = array();
		if(!empty($url) && $url != '/'){
			$url = explode("/",$url);
			array_shift($url);


			$currentController = $url[0]."Controller";
			array_shift($url);


			if(isset($url[0]) && !empty($url[0])){
				$currentAction = $url[0];
				array_shift($url);
			}else{
				$currentAction = 'index';
			}

			if(count($url) > 0){
				$params = $url;
			}
			
		}else{
			$currentController = 'homeController';
			$currentAction = 'index'; 
		}

		if(!file_exists("controllers/".$currentController.".php") && !method_exists($currentController,$currentAction)){
			$currentController = "NotFoundController";
			$currentAction = "index";
		}


		// instanciar uma variavel == instanciar o conteudo dela que vai ter o nome do controller
		$c = new $currentController();
		//rodar a action, que esta acessando. Não pode acessar direto igual o controller pq nao vai poder passar parametro
		call_user_func_array(array($c,$currentAction), $params);
		// esse metodo  vai executar a classe C e chamar o metodo action
		// e enviar os parametros se precisar, se nao ele envia um array vazio


		

		}
	}

 ?>
