<?php

include_once("sql_conexao.php"); 

$conexao = new conexao();

$conexao->sql_conexao();

$codigo_acesso = $_POST['codigo_acesso'];
$email = $_POST['email'];
$cod = $_GET['cod'];


//PRINT DE COMO O CODIGO VEIO DO POST
//print_r($codigo_acesso . 'antes');

//Pega o tamanho do codigo e remove os espaçps
$codigo_tamanho = strlen(trim($codigo_acesso));

//Retira os ultimos 5 numero e deixa só o ID
$codigo = substr($codigo_acesso, 0, $codigo_tamanho-5);

$novo_codigo =  $codigo;


$sql = "SELECT * FROM dbo.PT_Usuario WHERE ID_Usuario = '$novo_codigo' and DE_Email = '$email' "; 

//PRINT DO CODIGO DE ACESSO
//print_r(' -- '. $novo_codigo . ' depois');


$query = sqlsrv_query($conexao->con, $sql);

$registro = sqlsrv_fetch_array($query);

$senha =  base64_encode($registro['DE_Senha']);

//PRINT DA SENHA
print_r($senha);



header('Location: ../autenticacao_usuario.php?cod=' . $cod . ' &status=' . $senha . ' ');

if(empty($registro)){
    header('Location: ../autenticacao_usuario.php?cod=' . $cod . ' &error=2 ');
}




?>