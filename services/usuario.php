<?php
include_once("sql_conexao.php"); 
include_once("suporteController.php");


    class usuario extends conexao{ 

        public $query;
        public $registro;
        Public $Nivel;
        public $Acessos; 
        public $sql;
        public $DE_RazaoSocial;
        public $DE_Email; 
        public $DE_Senha ;  
        public $ID_Perfil;
        public $IS_Ativo;   
        public $ID_Usuario;
        public $sql_orcamento;
        public $registro_orcamento;
        public $query_orcamento;
        public $ID_VendFor; 
        public $query_usuario;
        public $sql_usuario;
        public $registro_usuario;
        

        public function login(){            
            $this->sql_conexao('CPD');           
            $this->DE_Email = $_POST['DE_Email'];
            $this->DE_Senha = $_POST['DE_Senha'];    
            
            //Remove os espaçps
            $this->DE_Senha = trim($this->DE_Senha);

            $this->sql = "SELECT * FROM dbo.TB_Usuario WHERE usuarioEmail = '$this->DE_Email' and Senha = '$this->DE_Senha' "; 

            //Query da consulta sql            
            $this->query = sqlsrv_query($this->con, $this->sql);
            //Array da query
            $this->registro = sqlsrv_fetch_array($this->query);  

            //------------------------------SQL PARA ACESSO AO ITEM-----------
            $sqlValidateVisualisar = "SELECT patindex( '%Compras.Item%',(acessosDecriptados)) as acesso
                                FROM
                                    dbo.TB_Usuario
                                WHERE 
                                    login = '".$this->registro['usuarioEmail']."' ";

            $queryValidateVisualisar = sqlsrv_query($this->con, $sqlValidateVisualisar);

            $arrayValidateVisualisar = sqlsrv_fetch_array($queryValidateVisualisar);
            //--------------------------------FIM ---------------------------
            
            //------------------------------SQL PARA INSERIR O ITEM-----------

            $sqlValidateInserir = "SELECT patindex( '%Compras.Item.Inserir%',(acessosDecriptados)) as acesso
                    FROM
                        dbo.TB_Usuario
                    WHERE 
                        login = '".$this->registro['usuarioEmail']."' ";

            $queryValidateInserir = sqlsrv_query($this->con, $sqlValidateInserir);

            $arrayValidateInserir = sqlsrv_fetch_array($queryValidateInserir);

            //--------------------------------FIM ---------------------------

            //------------------------------SQL PARA Editar O ITEM------------

            $sqlValidateEditar = "SELECT patindex( '%Compras.Item.Alterar%',(acessosDecriptados)) as acesso
            FROM
                dbo.TB_Usuario
            WHERE 
                login = '".$this->registro['usuarioEmail']."' ";

            $queryValidateEditar = sqlsrv_query($this->con, $sqlValidateEditar);

            $arrayValidateEditar = sqlsrv_fetch_array($queryValidateEditar);
    
            //--------------------------------FIM ---------------------------
    

            //var_dump($arrayValidateInserir['acesso']);
            
            //var_dump($sqlValidate); 

            if($this->registro['Senha'] === 'senha@123'){
                header('Location: alterar_senha.php?id=' . base64_encode($this->registro['ID_Usuario']) . ' ');exit;
            }               
            
            //var_dump($this->registro['IS_Ativo'], $this->DE_Email, $this->DE_Senha);

            //Verificar se a query tem uma linha afetada
            if($this->registro['Ativo'] == 0 and $this->registro['Nome'] == $this->DE_Email and $this->registro['Senha'] == $this->DE_Senha){
                header('Location: login.php?error=2 ');
                                
            }    
            elseif (sqlsrv_has_rows($this->query) == true){   

                if (!isset($_SESSION)) {
                    session_start();
                    echo 'acionei';
                    var_dump(sqlsrv_has_rows($this->query));

                    if($arrayValidateVisualisar['acesso'] == null){
                        $arrayValidateVisualisar = '0';
                    }
                    
                    $_SESSION['editarItem'] = $arrayValidateEditar['acesso'];
                    $_SESSION['visualizarItem'] = $arrayValidateVisualisar['acesso'];
                    $_SESSION['inserirItem'] = $arrayValidateInserir['acesso'];
                    $_SESSION['email'] = $this->registro['usuarioEmail'];
                    $_SESSION["Nome_portal"] = " Sr. " . $this->registro['Nome'];
                    $_SESSION['ID'] = $this->registro['ID_Usuario'];
                    //$_SESSION['ID_VenFor'] = $this->registro['ID_VenFor'];
                    $_SESSION['ID_Perfil'] = $this->registro['Nivel'];
                    $_SESSION['IS_Ativo'] = $this->registro['Ativo'];
                    //$_SESSION['email'] = $this->registro['DE_Email'];
                    $_SESSION["timer_portal"]= time() + 600;   
                    /*if($this->registro['Nivel'] == 2   $this->registro['Nivel'] == 1 ){
                        header('Location: index.php');exit;    
                            
                    }    */             
                    header('Location: index.php');exit;                                    
                }
            }
            elseif(sqlsrv_has_rows($this->query) == false){
                header('Location: login.php?error=1 ');         

            }            
            
        }
        
        
        public function editarMeusDados(){
            $this->sql_conexao('CPD');

            $this->ID_Usuario = base64_decode($_POST['ID_Usuario']);
            $this->DE_Senha = $_POST['DE_Senha'];

            $sql = "UPDATE dbo.TB_Usuario  SET  Senha='$this->DE_Senha' WHERE ID_Usuario='$this->ID_Usuario'";

            $result = sqlsrv_query($this->con, $sql);
            

            $linha = sqlsrv_rows_affected($result);             
            
            
    
            if($linha == true ) {
                header('Location: editar_dados.php?status=1&id=' . base64_encode($this->ID_Usuario) . ' ');exit;
            
            
            }else{
                echo
                "<script>   
                    alert('Falha ao alterar!');
                    
                </script>";
            
            }
        }

        public function listar_usuario(){
            $this->sql_conexao('portal'); 
            //$this->get_IdVend();
            $nome = '';
            $email = '';

            if(!empty($_POST['nome'])){
                $nome = $_POST['nome'];
                //print_r("cheguei no nome ");
            }

            if(!empty($_POST['email'])){
                $email = $_POST['email'];
                //print_r("cheguei no email ");
            }
            
            
            if(!empty($nome)){
                $this->sql_usuario = "SELECT * from dbo.PT_Usuario WHERE DE_RazaoSocial like '$nome%'"; 

            }elseif(!empty($email)){
                $this->sql_usuario = "SELECT * from dbo.PT_Usuario WHERE DE_Email like '$email%'"; 

            }else{
                $this->sql_usuario =  "SELECT TOP 10 * FROM  dbo.PT_Usuario  order by DT_Inclusao DESC";

            }
            $this->query_usuario = sqlsrv_query($this->con, $this->sql_usuario); 

            //Resultado Sql do listar banco
            //var_dump($this->query_usuario);
            //print_r($_SESSION['ID_Perfil']);
            
            while($this->registro_usuario = sqlsrv_fetch_array($this->query_usuario)){
                
                
                echo "<tr>";
                
                echo "<td>" . $this->registro_usuario['DE_RazaoSocial'] . "</td>";
                echo "<td>" . $this->registro_usuario['DE_Email'] . "</td>";
                
                if ($_SESSION['ID_Perfil'] == 0){
                    echo "<td>" . $this->registro_usuario['DE_Senha'] . "</td>";

                }elseif($_SESSION['ID_Perfil'] != 0){
                    echo "<td> ***** </td>";

                }else{
                    "<td> ***** </td>";
                }
                
                if($this->registro_usuario['IS_Ativo'] == 1){
                    echo "<td>Ativo </td>";
                }else{
                    echo "<td>Desativado </td>";
                }
                
                echo "<td>";
                if($_SESSION['ID_Perfil'] == 0){
                    echo "<button type=button class='btn btn-xs btn-warning' '> <a href=editar_cliente.php?ID_Usuario=" . $this->registro_usuario['ID_Usuario'] . "  style='color: inherit;'</a> Editar</button>";

                }else{
                    echo "<a href=services/envia_senha.php?ID=" . $this->registro_usuario['ID_Usuario'] .  " ><button class='btn btn-xs btn-success'>Enviar Senha</button> </a> ";

                }
                echo "</td>";
                
                echo "<td>" .  "</td>";
                
                echo "</tr>";
            }  
            
            
        } 

        public function listar_usuarioInterno(){
            $this->sql_conexao('CPD');
            
            //$this->get_IdVend();
            $nome = '';
            $email = '';

            if(!empty($_POST['nome'])){
                $nome = $_POST['nome'];
                //print_r("cheguei no nome ");
            }

            if(!empty($_POST['email'])){
                $email = $_POST['email'];
                //print_r("cheguei no email ");
            }
            
            
            if(!empty($nome)){
                $this->sql_usuario = "SELECT * from dbo.TB_Usuario WHERE Nome like '$nome%'"; 

            }elseif(!empty($email)){
                $this->sql_usuario = "SELECT * from dbo.TB_Usuario WHERE Email_Envio like '$email%'"; 

            }else{
                $this->sql_usuario =  "SELECT TOP 10 * FROM  dbo.TB_Usuario WHERE Ativo = 1 and is_deleted = 'N' order by ID_Usuario DESC";

            }
            $this->query_usuario = sqlsrv_query($this->con, $this->sql_usuario); 

            //Resultado Sql do listar banco
            //var_dump($this->query_usuario);
            //print_r($_SESSION['ID_Perfil']);
            
            while($this->registro_usuario = sqlsrv_fetch_array($this->query_usuario)){
                
                
                echo "<tr>";
                
                echo "<td>" . $this->registro_usuario['Nome'] . "</td>";
                echo "<td>" . $this->registro_usuario['usuarioEmail'] . "</td>";
                echo "<td>" . $this->registro_usuario['Email_Envio'] . "</td>";
                
                if ($_SESSION['ID_Perfil'] == 0){
                    echo "<td>" . $this->registro_usuario['Senha'] . "</td>";

                }elseif($_SESSION['ID_Perfil'] != 0){
                    echo "<td> ***** </td>";

                }else{
                    "<td> ***** </td>";
                }
                
                if($this->registro_usuario['Ativo'] == 1){
                    echo "<td>Ativo </td>";
                }else{
                    echo "<td>Desativado </td>";
                }
                
                /*echo "<td>";
                if($_SESSION['ID_Perfil'] == 0){
                    echo "<button type=button class='btn btn-xs btn-warning' '> <a href=editar_cliente.php?ID_Usuario=" . $this->registro_usuario['ID_Usuario'] . "  style='color: inherit;'</a> Editar</button>";

                }else{
                    echo "<a href=services/envia_senha.php?ID=" . $this->registro_usuario['ID_Usuario'] .  " ><button class='btn btn-xs btn-success'>Enviar Senha</button> </a> ";

                }
                echo "</td>";*/
                
                echo "<td>" .  "</td>";
                
                echo "</tr>";
            }  
            
            
        } 

        public function cadastrarCliente(){
            $this->sql_conexao('portal');
            $this->DE_RazaoSocial = $_POST['DE_RazaoSocial'];
            $this->DE_Email = $_POST['DE_Email'];
            $this->ID_Perfil = $_POST['ID_Perfil'];
            $this->DE_Senha = $_POST['DE_Senha'];


            if($this->ID_Perfil  == 0){
                $this->ID_Perfil = 2;
            }         

            
            $sql  = " DECLARE @ID INT  SELECT @ID = MAX(ID_Usuario)+1 FROM dbo.PT_Usuario  ";
            $sql .= "DECLARE @NOW DATETIME SET @NOW = GETDATE()	";
            $sql .= "DECLARE @ID_VENFOR INT SELECT @ID_VENFOR = MAX(ID_VenFor)+1 FROM dbo.PT_Usuario";
            $sql  .= " INSERT INTO dbo.PT_Usuario(ID_Usuario,IS_Ativo,DT_Inclusao, DE_RazaoSocial, DE_Email, ID_Perfil, DE_Senha, ID_VenFor) VALUES ( @ID, 1, @NOW,  '$this->DE_RazaoSocial', '$this->DE_Email', '$this->ID_Perfil', '$this->DE_Senha', @ID_VENFOR) ";
            

            $result = sqlsrv_query($this->con, $sql);

            //PRINT_R($sql);

            if($result == true){
                echo
                "<script>  
                
                    alert('Cadastrado com sucesso! Os dados foram enviados para o e-mail cadastrado');
                    window.location.href='cadastro_clientes.php';
                    
                </script>";
                
                
                $msg = "    
                    Parabens! Voce agora tem acesso ao portal de Compras

                    Para ser feito o primeiro acesso sera necessario inserir uma nova senha apos o Login

                    Seu e-mail = $this->DE_Email
                    Sua senha = $this->DE_Senha
                    
                    Para acessar acesse este link: http://192.168.1.6:8089/portal/portal_php/portal/login.php.";

                
                
                //mail("$this->DE_Email","Acesso ao Portal de Compras",$msg, "From: sistema@fresadorasantana.com.br");

            }else{
                echo
                "<script>   
                    alert('Falha ao cadastrar!');

                </script>";
            }
            
        }

        public function cadastrarFuncionarios(){
            $this->sql_conexao('CPD');
            $nome = $_POST['Nome'];
            $sobrenome = $_POST['Sobrenome'];
            $usuarioEmail = $_POST['usuarioEmail'];
            $ramal = $_POST['Ramal'];
            $email_Envio = $_POST['Email_Envio'];
            $nivel = $_POST['Nivel'];
            $senha = $_POST['Senha'];

            
            $sql  = " DECLARE @ID INT  SELECT @ID = MAX(ID_Usuario)+1 FROM dbo.TB_Usuario  ";
            $sql .= " INSERT INTO dbo.TB_Usuario(ID_Usuario, Ativo, Nome, Sobrenome, usuarioEmail, Ramal, Email_Envio, Nivel, Senha, Email_Resposta, is_deleted, Login) 
                        VALUES (@ID, 1,   '$nome', '$sobrenome', '$usuarioEmail', '$ramal','$email_Envio', '$nivel','$senha', '$email_Envio', 'N', '$usuarioEmail' ) ";
            
            $result = sqlsrv_query($this->con, $sql);

            if($result == true){
                echo
                "<script>  
                
                    alert('Cadastrado com sucesso! Os dados foram enviados para o e-mail cadastrado');
                    window.location.href='cadastro_clientesInternos.php';
                    
                </script>";
                
                
                $msg = "    
                    Parabens! Voce agora tem acesso ao portal de Compras

                    Para ser feito o primeiro acesso sera necessario inserir uma nova senha apos o Login

                    Seu Login = $usuarioEmail
                    Sua senha = $senha
                    
                    Para acessar acesse este link: http://192.168.1.6:8089/PortalInterno/cadastro_clientesInternos.php";

                
                
                //mail("$email_Envio","Acesso ao Portal de Compras",$msg, "From: sistema@fresadorasantana.com.br");

            }else{
                echo
                "<script>   
                    alert('Falha ao cadastrar!');

                </script>";
            }
            
        }
        
        public function editarCliente(){
            $this->sql_conexao('portal');
            $this->ID_Usuario = $_POST['ID_Usuario'];
            $this->DE_RazaoSocial = $_POST['DE_RazaoSocial'];
            $this->DE_Email = $_POST['DE_Email'];
            $this->ID_Perfil = $_POST['ID_Perfil'];
            $this->IS_Ativo = $_POST['IS_Ativo'];
            $this->DE_Senha = $_POST['DE_Senha'];
            
            
            $sql = "UPDATE dbo.PT_Usuario  SET DE_RazaoSocial = '$this->DE_RazaoSocial', DE_Email = '$this->DE_Email', ID_Perfil = '$this->ID_Perfil', 
            IS_Ativo = '$this->IS_Ativo', DE_Senha='$this->DE_Senha' WHERE ID_Usuario='$this->ID_Usuario'";

            
            //print_r($sql);
            
            $result = sqlsrv_query($this->con, $sql);
            $linha = sqlsrv_rows_affected($result);             
            
    
            if($linha == true ) {
                echo
                "<script>   
                    alert('Alterado com sucesso!');
                    window.location.href='editar_cliente.php?ID_Usuario=$this->ID_Usuario';

                </script>";
                
            
            
            }else{
                echo
                "<script>   
                    alert('Falha ao alterar!');        
                </script>";
            
            }
        }

        public function criar_senha($id){
            $this->sql_conexao('CPD');

            $senha = $_POST['senha'];
            $confirmar_senha = $_POST['confirmar_senha'];
            
            if(!empty($id)){
                $id = base64_decode($_GET['id']);
            }                        

            //print_r($id);
            //print_r($senha);
            //print_r($confirmar_senha);

            if($senha != $confirmar_senha ){                
                header('Location: alterar_senha.php?erro=1&id=' . base64_encode($id) .'');exit;
            }

            $sql =  "UPDATE dbo.TB_Usuario  SET Senha = '$senha' WHERE ID_Usuario = '$id' ";
            print_r($sql);

            $result = sqlsrv_query($this->con, $sql);
            $linha = sqlsrv_rows_affected($result);             
            
    
            if($linha == true ) {
                echo
                "<script>   
                    alert('Criado com sucesso. Será necessário logar novamente');
                    window.location.href='login.php';

                </script>";
            
            }else{
                echo
                "<script>   
                    //alert('Falha ao alterar!');        
                </script>";
            
            }
        }

        public function msg_suporte(){
            $suporte = new SuporteController();
            //$email = $_POST['email'];
            $assunto = $_POST['assunto_mensagem'];
            $mensagem = $_POST['corpo_mensagem'];
            $idFornecedor = $_POST['idFornecedor'];
            
            //print_r($assunto);
            //print_r($mensagem);
            //print_r($email);

            $msg = $mensagem;                       
            $msg .= " Destinatario id= " . $idFornecedor ;
        
            //mail('sistema@fresadorasantana.com.br',$assunto,$msg, "From: sistema@fresadorasantana.com.br");

            $adicionarChamado = $suporte->AdicionarChamado($idFornecedor,$assunto,$mensagem);

            if($adicionarChamado == TRUE);{
                header('Location: suporte.php?idFornecedor='.base64_encode($idFornecedor).'&status=1');
            }            


        }   
        
        public function AcessoVisualizarItem($email){
            $this->sql_conexao('CPD');

            $sql = "SELECT patindex( '%Compras.Item%',(acessosDecriptados)) as acesso
                        FROM
                            dbo.TB_Usuario
                        WHERE 
                            login = '$email' ";

            $query = sqlsrv_query($this->con, $sql);

            $array = sqlsrv_fetch_array($query);

            if($array['acesso'] == 0){
                echo 'bloqueado';
            }else{
                echo 'permitido';
            }

            
        }
        

        public function logoff(){
            session_start();
            session_destroy();
            header('Location: login.php');

        }
        
        
        
    
}

$usuario = new usuario(); 


if(!empty($_POST['cod_post'])){    

    $cod_post = $_POST['cod_post'];    
    if($cod_post == "2"){        
        $usuario->editarMeusDados();

    }elseif($cod_post == "3"){
        $usuario->editarCliente();

    }elseif($cod_post == "4"){
        $usuario->cadastrarCliente();
    }elseif($cod_post == "7"){
        $usuario->msg_suporte();
    }elseif($cod_post == '8'){
        $usuario->cadastrarFuncionarios();

    }
    
}

if(!empty($_POST['emailUser'])){
    $email = $_POST['emailUser'];
    $usuario->AcessoVisualizarItem($email);

}




?>


