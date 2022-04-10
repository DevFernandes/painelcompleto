<?php
session_start();
ob_start();
include_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>SDK</title>
</head>

<body>

    <div class="card bg-dark" style="width:400px">
    <h2>Administração</h2>
<form method="POST" action="">
<div class="container mt-3">

 <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">E-mail</label>
        <input type="text" name="usuario" class="form-control" placeholder="Digite o usuário" value="<?php if(isset($dados['usuario'])){ echo $dados['usuario']; } ?>">
        <div id="emailHelp" class="form-text">Nunca compartilharemos seu e-mail com mais ninguém.</div>
        <br>
  <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" name="senha_usuario" placeholder="Digite a senha" value="<?php if(isset($dados['senha_usuario'])){ echo $dados['senha_usuario']; } ?>">
       <br> 
        <input type="submit" class="btn btn-primary" value="Acessar" name="SendLogin">
   
    <br></br>
    <a href="cadastro" type="button" class="btn btn-success">Criar conta</a> <a href="recuperar-senha" class="btn btn-warning">Esqueceu a senha?</a>
    </div> 

    <?php
			if(isset($_SESSION['msg'])){
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
			if(isset($_SESSION['msgcad'])){
				echo $_SESSION['msgcad'];
				unset($_SESSION['msgcad']);
			}
		?>
    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendLogin'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id, nome, usuario, senha_usuario 
                        FROM usuarios 
                        WHERE usuario =:usuario  
                        LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
        $result_usuario->execute();

        if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_usuario);
            if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])){
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['nome'] = $row_usuario['nome'];
                header("Location: dashboard.php");
            }else{
                $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>
                Erro: Usuário ou senha inválido!
              </div>";
            }
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>
            Erro: Usuário ou senha inválido!
          </div>";
        }

        
    }

    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
</form>

</div>
</body>

</html>