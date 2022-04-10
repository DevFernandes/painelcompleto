<?php

include_once 'conexao.php';

$produto = filter_input(INPUT_POST, 'produto', FILTER_SANITIZE_STRING);
$valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_STRING);

$query_usuario = "INSERT INTO prod (produto, valor) VALUES ('$produto', '$valor')";
mysqli_query($conn, $query_usuario);

if(mysqli_insert_id($conn)){
	echo true;
}else{
	echo false;
}