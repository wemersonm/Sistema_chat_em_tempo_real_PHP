<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/template.css">
	<title>Meu site</title>
</head>
<body>

<header>
	<nav>
		<div class="logo">
			<a href="<?php echo BASE_URL; ?>"><p>LOGO</p></a>
		</div>
		<ul>
			<li>
				<p class="header-username"><?php echo $viewData['name']; ?></p>
			</li>
			<li>
				<a href="<?php echo BASE_URL; ?>home/logout">SAIR</a>
			</li>
		</ul>
	</nav>
</header>
<main>
<?php 
	// dados enviados peloa viewTemplate
	$this->loadViewInTemplate($viewName,$viewData); 

?>
<div class="modal_bg" style="display: none;">
	<div class="modal_area">
		aaa
	</div>
</div>
</main>




<script type="text/javascript">
	var BASE_URL = '<?php echo BASE_URL; ?>';
	var group_list = <?php echo json_encode($viewData['currentGroups'])?>
</script>
  
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/chat.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
</body>
</html>