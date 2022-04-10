<?php
if(isset($_POST["user_id"])){
	include_once "conexao.php";
	
$query_user = "DELETE FROM produto WHERE id='$id'";
	$resultado_usuario = mysqli_query($conn, $query_user);
    $row_user = mysqli_fetch_assoc($resultado_user);
    
		$_SESSION['msg'] = "<p style='color:green;'>Usuário apagado com sucesso</p>";
		header("Location: index.php");
	}else{
		
		$_SESSION['msg'] = "<p style='color:red;'>Erro o usuário não foi apagado com sucesso</p>";
		header("Location: index.php");
	}
}else{	
	$_SESSION['msg'] = "<p style='color:red;'>Necessário selecionar um usuário</p>";
	header("Location: index.php");
}
