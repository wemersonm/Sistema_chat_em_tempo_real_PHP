<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
	<title>Cadastrar</title>
</head>
<body>
	<div class="content-form">
		<h1>Cadastre-se</h1>
		<form method="post">
			<label for="username">Nome de usuário</label><br>
			<input type="text" id="username" name="username"><br>
			<label for="password">Senha</label><br>
			<input type="password" id="password" name="password"><br><br>
			<input type="submit" name="submit" value="Cadastrar">
		</form> <br>
		<a href="<?php echo BASE_URL; ?>login">Login</a>
		<?php if(!empty($error)): ?>
		<p class="warning"><?php echo $error; ?> </p>
		<?php endif; ?>
	</div>
	



</body>
</html>