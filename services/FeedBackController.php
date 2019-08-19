<?php
include_once('sql_conexao.php');

class Feedback extends conexao{

    public function SalvarFeeback($idFornecedor,$mensagem){
        $this->sql_conexao('portal');           

        $sql = "INSERT INTO dbo.Feedback (ID_Usuario,Mensagem) VALUES ($idFornecedor, '$mensagem') ";

        $query = sqlsrv_query($this->con, $sql);
        
        if($query == false){
            die("erro na função salvar feedback");
        }


    }
}

?>