<?php
session_start();
ob_start();

$btnCadUsuario = filter_input(INPUT_POST, 'btnCadUsuario', FILTER_SANITIZE_STRING);
if($btnCadUsuario){
	include_once 'conexao.php';
	$dados_rc = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	
	$erro = false;
	
	$dados_st = array_map('strip_tags', $dados_rc);
	$dados = array_map('trim', $dados_st);
	
if(in_array('',$dados)){
	$erro = true;
	$_SESSION['msg'] = "<div class='alert alert-warning' role='alert'>
	Necessário preencher todos os campos!
  </div>";
}elseif((strlen($dados['senha_usuario'])) < 6){
	$erro = true;
	$_SESSION['msg'] = "<div class='alert alert-info' role='alert'>
	A senha deve conter pelo menos 6 caracters!
  </div>";
}elseif(stristr($dados['senha_usuario'], "'")) {
	$erro = true;
	$_SESSION['msg'] = "Caracter ( ' ) utilizado na senha é inválido";
}else{
	$result_usuario = "SELECT id FROM usuarios WHERE usuario='". $dados['usuario'] ."'";
	$resultado_usuario = mysqli_query($conn, $result_usuario);
	if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
		$erro = true;
		$_SESSION['msg'] = "<div class='alert alert-info' role='alert'>
		Este usuário já está cadastrado!
	  </div>";
	}
	
	$result_usuario = "SELECT id FROM usuarios WHERE usuario='". $dados['usuario'] ."'";
	$resultado_usuario = mysqli_query($conn, $result_usuario);
	if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
		$erro = true;
		$_SESSION['msg'] = "<div class='alert alert-info' role='alert'>
		Este e-mail já está cadastrado!
	  </div>";
	}
}

	//var_dump($dados);
if(!$erro){
	//var_dump($dados);
	$dados['senha_usuario'] = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);
	
	$result_usuario = "INSERT INTO usuarios (nome, usuario, senha_usuario) VALUES (
					'" .$dados['nome']. "',
					'" .$dados['usuario']. "',
					'" .$dados['senha_usuario']. "'
					)";
	$resultado_usario = mysqli_query($conn, $result_usuario);
	if(mysqli_insert_id($conn)){
		$_SESSION['msgcad'] = "<div class='alert alert-success' role='alert'>
		Usuário cadastrado com sucesso!
	  </div>";
		header("Location: ../index.php");
	}else{
		$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>
		Erro ao cadastrar usuário!
	  </div>";
	}
}
}
	?>
	

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
		<title>SDK - Cadastro</title>
	</head>
	<body>
		<?php
			if(isset($_SESSION['msg'])){
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
		?>
		    <div class="card bg-dark" style="width:300px">
    <h5>Registre sua conta</h5>
<form method="POST" action="">
<div class="container mt-3">

 <div class="mb-3">
		<form method="POST" action="">
		<label for="exampleInputEmail1" class="form-label">Usuário</label>
			<input type="text" name="nome" class="form-control" placeholder="Digite o nome e o sobrenome"><br>
			<label for="exampleInputEmail1" class="form-label">E-mail</label>
			<input type="text" name="usuario"  class="form-control" placeholder="Digite o usuário"><br>
			<label for="exampleInputEmail1" class="form-label">Senha</label>
			<input type="password" name="senha_usuario"  class="form-control" placeholder="Digite a senha"><br><br>
			
			<input type="submit" class="btn btn-light" name="btnCadUsuario" value="Cadastrar"><br><br>
			
			<p style="color:white;">Já fez cadastro?</p> <a href="../index.php" class="btn btn-info">Clique aqui</a>
		</form>
		<script>
function voltar() {
window.history.back();
}
</script>
	</body>
</html>