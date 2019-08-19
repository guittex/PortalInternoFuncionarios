<?php
include_once("conexaoEnterprise.php");

class SuporteController extends conexaoEnterprise
{
    public function getArray($query, $email,$idFornecedor,$cor)
    {
        session_start();

        while($array = sqlsrv_fetch_array($query)){
            echo "<tr>";  
                    $idChamado = $array['id'];
            echo    "<td> ".$array['id']." </td>";
            echo    "<td> ".$array['assunto']." </td>";
            echo    "<td><p style=color:".$cor.">".$array['situacao']."</p></td>";
            echo    "<td>".date('d-m-Y h:i', strtotime($array['criado']))." </td>";
            echo    "<td>";
            //echo        "<a href='services/suporteController.php?idChamadoSuporteController=".$idChamado."&idFornecedor=".$idFornecedor."&mail=".$email." '><button type='button' class='btn btn-danger'>Apagar</button></a>";
                        if($array['situacao'] != 'Pendente' and $_SESSION['ID_Perfil'] == 0){
            echo        '<button type="button" class="btn btn-primary m-l-5" data-toggle="modal" data-target=#'.$array['id'].'>Ver</button>';
                        }elseif($array['situacao'] == 'Pendente' and $_SESSION['ID_Perfil'] == 0){
            echo            '<button type="button" class="btn btn-warning m-l-5" data-toggle="modal" data-target=#'.$array['id'].'>Responder</button>';

                        }
                        if($array['situacao'] != 'Pendente' and $_SESSION['ID_Perfil'] != 0){
            echo            '<button type="button" class="btn btn-primary m-l-5" data-toggle="modal" data-target=#'.$array['id'].'>Ver</button>';
                        }elseif($array['situacao'] == 'Pendente' and $_SESSION['ID_Perfil'] != 0){
            echo            '<button type="button" class="btn btn-primary m-l-5" data-toggle="modal" data-target=#'.$array['id'].'>Ver</button>';
        
                        }
            echo    "</td>";
            echo "</tr>";
            }            

    }
    public function index($idFornecedor,$email,$situacao){
        $this->sql_conexaoEnterprise('TESTE');
        $suporte = new SuporteController(); 
                
        if($situacao == "Pendente"){
            $sql = "SELECT * FROM dbo.chamados WHERE idFornecedor = '$idFornecedor' and situacao like '$situacao%' ";

            $query = sqlsrv_query($this->con, $sql);    
            
            echo 'to no pendente';

            echo $sql;
            $suporte->getArray($query,$email,$idFornecedor, 'red');

        }elseif($situacao == "Concluido"){
            $sql = "SELECT * FROM dbo.chamados WHERE idFornecedor = '$idFornecedor' and situacao like '$situacao%' ";

            $query = sqlsrv_query($this->con, $sql);

            $suporte->getArray($query,$email,$idFornecedor,'green');

        }elseif($situacao == "Todos"){
            $sql = "SELECT * FROM dbo.chamados WHERE idFornecedor = '$idFornecedor' ";

            $query = sqlsrv_query($this->con, $sql);

            $suporte->getArray($query,$email,$idFornecedor,'black');


        }else{
            $sql = "SELECT * FROM dbo.chamados WHERE idFornecedor = '$idFornecedor' ";

            $query = sqlsrv_query($this->con, $sql);

        }

        return $query;

    }

    public function IndexAdm($idFornecedor,$email,$situacao){
        $this->sql_conexaoEnterprise('TESTE');
        $suporte = new SuporteController(); 
                
        if($situacao == "Pendente"){
            $sql = "SELECT * FROM dbo.chamados WHERE situacao like '$situacao%' ";

            $query = sqlsrv_query($this->con, $sql);    
            
            echo 'to no pendente';

            echo $sql;
            $suporte->getArray($query,$email,$idFornecedor, 'red');

        }elseif($situacao == "Concluido"){
            $sql = "SELECT * FROM dbo.chamados WHERE situacao like '$situacao%' ";

            $query = sqlsrv_query($this->con, $sql);

            $suporte->getArray($query,$email,$idFornecedor,'green');

        }elseif($situacao == "Todos"){
            $sql = "SELECT * FROM dbo.chamados ";

            $query = sqlsrv_query($this->con, $sql);

            $suporte->getArray($query,$email,$idFornecedor,'black');


        }else{
            $sql = "SELECT * FROM dbo.chamados ";

            $query = sqlsrv_query($this->con, $sql);

        }

        return $query;

    }

    public function ListarChamadosAdm()
    {
        $this->sql_conexaoEnterprise('TESTE');

        $suporte = new SuporteController();  

        $sql = "SELECT * FROM dbo.chamados order by criado desc ";

        $query = sqlsrv_query($this->con, $sql);

        return $query;
        

    }

    public function RespostaChamadoAdm($resposta, $IdFuncionario , $IdChamado)
    {
        $this->sql_conexaoEnterprise('TESTE');

        $sql = "UPDATE dbo.chamados SET resposta = '$resposta', respondido = getdate(), situacao = 'Concluido', idFuncionario = $IdFuncionario WHERE id = $IdChamado ";

        $query = sqlsrv_query($this->con, $sql);

        //echo $sql ;
        
        if($query == true){
            return true;
            exit();
        }else{
            return false;
            exit();
        }
    }

    public function AdicionarChamado($idFornecedor,$assunto,$mensagem)
    {
        $this->sql_conexaoEnterprise('TESTE');

        $sql = "INSERT INTO dbo.chamados(idFornecedor,assunto,mensagem,situacao,criado) values
                ('$idFornecedor', '$assunto', '$mensagem', 'Pendente', getdate()) ";

        $query = sqlsrv_query($this->con, $sql);

        if($query == FALSE)
        {
            header('Location: suporte.php?idFornecedor='.base64_encode($idFornecedor).'&status=2&mail= ' . base64_encode($email) . ' ');
            exit();
        }else{
            return true;
        }
    }

    public function apagarChamado($idChamado, $email, $idFornecedor)
    {
        $this->sql_conexaoEnterprise('TESTE');

        echo $idChamado,$email,$idFornecedor;

        $sql = "DELETE FROM dbo.chamados WHERE id = '$idChamado' ";

        $query = sqlsrv_query($this->con, $sql);

        if($query == false){
            die("erro");
        }else{
            header('Location: ../suporte.php?idFornecedor='.base64_encode($idFornecedor).'&mail= ' . base64_encode($email) . ' ');

        }
    }
}


$suporte = new SuporteController();

if(!empty($_GET['idChamadoSuporteController'])){
    $idChamado = $_GET['idChamadoSuporteController'];
    $email = $_GET['mail'];
    $idFornecedor = $_GET['idFornecedor'];


    $suporte->apagarChamado($idChamado, $email, $idFornecedor);
}

if(!empty($_POST['situacao'])){

    $situacao = $_POST['situacao'];
    $idFornecedor = $_POST['idFornecedor'];
    $email = $_POST['mail'];

    
    if($email == '0'){
        $suporte->IndexAdm($idFornecedor,$email,$situacao);
        
    }else{
        $suporte->index($idFornecedor,$email,$situacao);
    }
}

?>