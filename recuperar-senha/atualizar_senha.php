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
    <title>SDK - Atualizar Senha</title>
</head>

<body>

    <?php
    $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);


    if (!empty($chave)) {
        //var_dump($chave);

        $query_usuario = "SELECT id 
                            FROM usuarios 
                            WHERE recuperar_senha =:recuperar_senha  
                            LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':recuperar_senha', $chave, PDO::PARAM_STR);
        $result_usuario->execute();

        if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($dados);
            if (!empty($dados['SendNovaSenha'])) {
                $senha_usuario = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);
                $recuperar_senha = 'NULL';

                $query_up_usuario = "UPDATE usuarios 
                        SET senha_usuario =:senha_usuario,
                        recuperar_senha =:recuperar_senha
                        WHERE id =:id 
                        LIMIT 1";
                $result_up_usuario = $conn->prepare($query_up_usuario);
                $result_up_usuario->bindParam(':senha_usuario', $senha_usuario, PDO::PARAM_STR);
                $result_up_usuario->bindParam(':recuperar_senha', $recuperar_senha);
                $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);

                if ($result_up_usuario->execute()) {
                    $_SESSION['msg'] = "<div class='alert alert-success' role='alert'>
                    A senha foi atualizada com sucesso!
                  </div>";
                    header("Location: ../index.php");
                } else {
                    echo "<p style='color: #ff0000'>Erro: Tente novamente!</p>";
                }
            }
        } else {
            $_SESSION['msg_rec'] = "<div class='alert alert-danger' role='alert'>
            Erro: Link está inválido, solicite um novo link para atualizar a senha!
          </div>";
            header("Location: ../index.php");
        }
    } else {
        $_SESSION['msg_rec'] = "<p style='color: #ff0000'>Erro: Link inválido, solicite novo link para atualizar a senha!</p>";
        header("Location: ../index.php");
    }

    ?>
<div class="card bg-dark" style="width:400px">
    <h2>Nova Senha</h2>
    <br>
    <form method="POST" action="">
        <?php
        $usuario = "";
        if (isset($dados['senha_usuario'])) {
            $usuario = $dados['senha_usuario'];
        } ?>
        <input type="password" class="form-control" name="senha_usuario" placeholder="Digite a nova senha" value="<?php echo $usuario; ?>"><br><br>

        <input type="submit" class="btn btn-light" value="Atualizar" name="SendNovaSenha">
    </form>

    <br>
    <p style="color:white;">Lembrou? <a class="btn btn-info" href="../index.php">clique aqui</a>

</body>

</html>