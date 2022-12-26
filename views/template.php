<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/template.css">
	<title>Meu site</title>
</head>
<body>

<header>
	<nav>
		<div class="logo">
			<a href="<?php BASE_URL; ?>"><p>LOGO</p></a>
		</div>
		<ul>
			<li>
				<p>Usuario X</p>
			</li>
			<li>
				<a href="<?php BASE_URL; ?>">SAIR</a>
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
<footer>
	footer
	TALIGADO
</footer>



<script type="text/javascript">
	var BASE_URL = '<?php echo BASE_URL; ?>';
</script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/chat.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
</body>
</html>