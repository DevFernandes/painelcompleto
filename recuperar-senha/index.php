<?php
session_start();
ob_start();
include_once 'conexao.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './lib/vendor/autoload.php';
$mail = new PHPMailer(true);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>SDK - Recuperar Senha</title>
</head>

<body>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendRecupSenha'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id, nome, usuario 
                    FROM usuarios 
                    WHERE usuario =:usuario  
                    LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
        $result_usuario->execute();

        if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            $chave_recuperar_senha = password_hash($row_usuario['id'], PASSWORD_DEFAULT);
            //echo "Chave $chave_recuperar_senha <br>";

            $query_up_usuario = "UPDATE usuarios 
                        SET recuperar_senha =:recuperar_senha 
                        WHERE id =:id 
                        LIMIT 1";
            $result_up_usuario = $conn->prepare($query_up_usuario);
            $result_up_usuario->bindParam(':recuperar_senha', $chave_recuperar_senha, PDO::PARAM_STR);
            $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);

            if ($result_up_usuario->execute()) {
                $link = "http://localhost/recuperar-senha/atualizar_senha.php?chave=$chave_recuperar_senha";

                try {
                    /*$mail->SMTPDebug = SMTP::DEBUG_SERVER;*/
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'matheusfernandesxlx@gmail.com';
                    $mail->Password   = 'matheusfo21';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('atendimento@celke.com', 'Atendimento');
                    $mail->addAddress($row_usuario['usuario'], $row_usuario['nome']);

                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Recuperar senha';
                    $mail->Body    = 'Prezado(a) ' . $row_usuario['nome'] .".<br><br>Você solicitou alteração de senha.<br><br>Para continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: <br><br><a href='" . $link . "'>" . $link . "</a><br><br>Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.<br><br>";
                    $mail->AltBody = 'Prezado(a) ' . $row_usuario['nome'] ."\n\nVocê solicitou alteração de senha.\n\nPara continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n" . $link . "\n\nSe você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.\n\n";

                    $mail->send();

                    $_SESSION['msg'] = "<p style='color: green'>Enviado e-mail com instruções para recuperar a senha. Acesse a sua caixa de e-mail para recuperar a senha!</p>";
                    header("Location: ../index.php");
                } catch (Exception $e) {
                    echo "Erro: E-mail não enviado sucesso. Mailer Error: {$mail->ErrorInfo}";
                }
} else {
                echo  "<div class='alert alert-danger' role='alert'>
                Erro, Tente novamente!
              </div>";
            }
        } else {
            echo "<<div class='alert alert-danger' role='alert'>
            Erro, Usuário não encontrado!
          </div>";
        }
    }

    if (isset($_SESSION['msg_rec'])) {
        echo $_SESSION['msg_rec'];
        unset($_SESSION['msg_rec']);
    }

        ?>

<div class="card bg-dark" style="width:400px">
    <h2>Recuperar senha</h2>
    
    <form method="POST" action="">
        <?php
        $usuario = "";
        if (isset($dados['usuario'])) {
            $usuario = $dados['usuario'];
        } ?>

<label for="exampleInputEmail1" class="form-label">E-mail</label>
        <input type="text" name="usuario" class="form-control" placeholder="Digite seu e-mail" value="<?php echo $usuario; ?>"><br><br>

        <input type="submit" class="btn btn-light" value="Recuperar" name="SendRecupSenha">
    
    </form>

    <br>
    <p style="color:white;">Lembrou? <a class= "btn btn-info" href="../index.php">clique aqui</a>
</body>

</html>