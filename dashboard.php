<?php
session_start();
ob_start();
include_once 'conexao.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>
    Necessário realizar o login para acessar a página!
  </div>";
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico">
    <link rel="stysheet" href="../css/nav.css">
    <title>SDK - Área restrita</title>
</head>

<body>

<nav class="navbar navbar-expand-lg text-white  navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="btn btn-primary" href="#">Administração</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Link</a>
        </li>
      </ul>
      <form class="d-flex">
      <ul class="nav justify-content-center">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <h5 class="btn btn-light"><?php echo $_SESSION['nome']; ?></h5>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="#">Trocar senha</a></li>
            <li><a class="dropdown-item" href="#">Configurações</a></li>
            <li><a class="dropdown-item" href="sair.php">Logout</a></li>
          </ul>
        </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#"></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#"></a>
  </li>
  
    </form>
    </div>
  </div>
</nav>

<h5></h5>
</body>

</html>