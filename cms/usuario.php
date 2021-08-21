<?php
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    $nome = null;
    $email = null;
    $nivel_usuario = null;
    $senha = null;
    $confirmsenha = null;
    $img = "img/cancel.png";
    $legenda = "Desativado";
    $cod_nivel_usuario = 0;
    $modo = null;
    $status = null;
    $botao = "Salvar";

    if(isset($_POST['btnEnviar'])){
        
        $nome = $_POST['txt_nome_usuario'];
        $email = $_POST['txt_email_usuario'];
        $senha = $_POST['txt_senha_usuario'];
        $confirmsenha = $_POST['txt_confirm_senha'];
        $nivel_usuario = $_POST['slt_nivel_usuario'];
        
        $senha_criptografada = md5($senha);
        
        //INSERINDO REGISTROS NO BANCO DE DADOS
        if($_POST['btnEnviar'] == 'Salvar'){
            
              if($senha == $confirmsenha){
                
                $sql = "INSERT INTO tbl_usuario (nome,email,senha,cod_nivel_usuario) VALUES ('".addslashes($nome)."','".addslashes($email)."','".addslashes($senha_criptografada)."',".addslashes($nivel_usuario).")";
              
                mysqli_query($conexao,$sql);

                header('location:usuario.php');
                
              }else{
                
                echo("<script> alert('A confirmação de senha está errada!') </script>");
              }
              
           
        
        //ATUALIZANDO OS REGISTROS NO BANCO DE DADOS
        }elseif($_POST['btnEnviar'] == "Editar"){
      
                  
              if($senha == null){
                
                  $sql = "UPDATE tbl_usuario SET nome='".addslashes($nome)."', email='".addslashes($email)."', cod_nivel_usuario=".addslashes($nivel_usuario)." WHERE cod_usuario = ".$_SESSION['idregistro'];
                
                  mysqli_query($conexao,$sql);

                  header('location:usuario.php');
                
              }elseif($senha != null ){
                
                if($senha == $confirmsenha){
                  
                  $sql = "UPDATE tbl_usuario SET nome='".addslashes($nome)."', email='".addslashes($email)."', senha='".addslashes($senha_criptografada)."', cod_nivel_usuario=".addslashes($nivel_usuario)." WHERE cod_usuario = ".$_SESSION['idregistro'];
                
                  mysqli_query($conexao,$sql);

                  header('location:usuario.php');
                  
                }else{
                  
                  echo("<script> alert('A confirmação de senha está errada!') </script>");
                  $botao = "Editar";
                  
                }
                
          }
          
             
       
        }
      
      
        
    }

    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUINDO REGISTROS
        if($modo == 'excluir'){
        
            $sql = "DELETE FROM tbl_usuario WHERE cod_usuario=".$codigo." ";

            mysqli_query($conexao,$sql);
          
          
        //FAZENDO BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            //SELECT NA TABELA USUARIO E O NÍVEL DE USUARIO
            $sql = "SELECT tbl_usuario.*, tbl_nivel_usuario.nivel_usuario 
            FROM tbl_usuario INNER JOIN tbl_nivel_usuario
            ON tbl_usuario.cod_nivel_usuario = tbl_nivel_usuario.cod_nivel_usuario
            WHERE tbl_usuario.cod_usuario = ".$codigo;
            
            
            $select = mysqli_query($conexao, $sql);
            
            if($rsusuario = mysqli_fetch_array($select)){
                
                $nome = $rsusuario['nome'];
                $email = $rsusuario['email'];
                
                $nivel_usuario = $rsusuario['nivel_usuario'];
                $cod_nivel_usuario = $rsusuario['cod_nivel_usuario'];
                $botao = "Editar";
    
            }
            
            
        }
        
    }

    //LÓGICA ATIVAR E DESATIVAR
    if(isset($_GET['ativo'])){
        
        $codigo = $_GET['id'];
        $ativo = $_GET['ativo'];
        $nivel = $_GET['nivel'];
      
          //sel4ect que traz or regsitros do usuario e o nivel de usuario
          $sql = "SELECT tbl_nivel_usuario.ativo AS ativo_nivel, tbl_usuario.*
                  FROM tbl_nivel_usuario INNER JOIN tbl_usuario
                  ON tbl_nivel_usuario.cod_nivel_usuario = tbl_usuario.cod_nivel_usuario WHERE tbl_nivel_usuario.cod_nivel_usuario=".addslashes($nivel)." AND tbl_usuario.cod_usuario=".addslashes($codigo);
      
          $select = mysqli_query($conexao,$sql);
      
        
      
          while($rsativo = mysqli_fetch_array($select)){
          
            //se o ativo do nivel for igual a 1
            if($rsativo['ativo_nivel'] == 1){
              
              //e o ativo do usuairo igual a 0 ele ativa o usuairo
              if($ativo == 0){

                    $sql = "UPDATE tbl_usuario SET ativo = 1 WHERE cod_usuario=".$codigo;

              //se o ativo do usuairo igual a 1 ele desativa o usuairo 
              }elseif($ativo == 1){


                    $sql = "UPDATE tbl_usuario SET ativo = 0 WHERE cod_usuario=".$codigo;

              }
              
              
             if(mysqli_query($conexao,$sql)){

                 header('location:usuario.php');

             }else{
                 echo("Erro!!!");

             }
             
            //se o ativo do nivel estiver desativado não poderar ativar o nivel para o usuario
            }elseif($rsativo['ativo_nivel'] == 0){
              
              echo("<script> alert('O usuário que você deseja ativar está com o nível desativado!') </script>");
              
              
            }
            
            

            
          }
        
        

        
    }


?>

<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/usuario.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="../js/valida.js"></script>
   <meta charset="utf-8">
      
        
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                
                <div class="itens">
                    <div class="titulo_usuarios"><h2>Cadastro de Usuários</h2></div>
                    <div class="cadastro_usuarios center">
                      
                      <!--FORMULARIO-->
                        <form action="usuario.php" method="post" name="frm_usuarios">
                            
                            <!--AREA DE CADASTRADO DO NOME-->
                            <div class="form_nome">
                                <div class="form_texto_usuario">
                                    <label>Nome:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <input name="txt_nome_usuario" class="txtNomeUsuarios" type="text" value="<?php echo($nome)?>" onkeypress="return ValidarNumero(event);" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            
                            <!--AREA DE CADASTRO DO EMAIL-->
                            <div class="form_email">
                                <div class="form_texto_usuario">
                                    <label>Email:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <input name="txt_email_usuario" class="txtEmailUsuarios" type="email" value="<?php echo($email)?>" required placeholder="acme@locadora.com" maxlength="250" autocomplete="off">
                                </div>
                            </div>
                            
                          <!--AREA DE CADASTRO DO NIVEL-->
                            <div class="form_nivel_usuario">
                                <div class="form_texto_usuario">
                                    <label>Nível do Usuário:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    
                                    
                                    <select name="slt_nivel_usuario" class="txtNivelUsuarios" required title="Selecione um item na lista.">
                                    <?php
                                        
                                        if($modo == "buscar"){
                                    
                                        
                                    ?>    
                                        <option value="<?php echo($cod_nivel_usuario);?>" selected><span><?php echo($nivel_usuario)?></span></option>
                                    
                                    <?php
                                    
                                        }else{ 
                                            
                                    ?>       
                                            
                                        <option value="" selected><span>Selecione...</span></option>
                                        
                                        
                                    <?php
                                        }
                                        
                                        //seelct para trazer os niveis diferentes do selecionado
                                        $sql = "SELECT * FROM tbl_nivel_usuario WHERE cod_nivel_usuario <> ".$cod_nivel_usuario." ORDER BY nivel_usuario";
    
                                        $select = mysqli_query($conexao,$sql);
                
                                        while($rsniveis = mysqli_fetch_array($select)){
                                            
                                            if($rsniveis['ativo'] != 0){
                                    
                                    ?>
                                        
                                                <option value="<?php echo($rsniveis['cod_nivel_usuario']);?>"><?php echo($rsniveis['nivel_usuario']);?></option>
                                        
                                        
                                    <?php 
                                            }
                                            
                                        }

                                    ?>
                                        
                                    </select>
                                    
                                    
                                </div>
                            </div>
                            
                          <!--AREA DE CADASTRO DA SENHA-->
                            <div class="form_senha">
                                <div class="form_texto_usuario">
                                    <label>Senha:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                  <?php
                                  
                                   if($botao == "Salvar"){
                                  
                                  ?>
                                  
                                  
                                    <input name="txt_senha_usuario" id='txtSenha' class="txtSenhaUsuarios" value="<?php echo($senha)?>" type="password" maxlength="255" required>
                                  
                                  <?php
                                  
                                  
                                    }else{
                                  
                                  ?>
                                  
                                  <input name="txt_senha_usuario" id='txtNovaSenha' class="txtSenhaUsuarios" value="<?php echo($senha)?>" type="password" maxlength="255">
                                  
                                  <?php
                                  
                                  
                                    }
                                  
                                  ?>
                                  
                                </div>
                            </div>
                          
                            <!--AREA DE CONFIRMACAO DA SENHA-->
                            <div class="form_confirm_senha">
                                <div class="form_texto_usuario">
                                    <label>Confirmação:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                  
                                  <?php
                                  
                                  
                                    if($botao == "Salvar"){
                                  
                                  ?>
                                  
                                      <input name="txt_confirm_senha" id='txtConfirmacaoSenha' class="txtConfirmSenhaUsuarios" value="" type="password" maxlength="255" required>
                                  
                                  <?php
                                  
                                    }elseif($botao == "Editar" && $senha != null){
                                  
                                  
                                  ?>
                                  
                                      <input name="txt_confirm_senha" id='txtConfirmacaoSenha' class="txtConfirmSenhaUsuarios" value="" type="password" maxlength="255" required>
                                  
                                  <?php
                                  
                                  
                                    }else{
                                  
                                  ?>
                                  
                                      <input name="txt_confirm_senha" id='txtConfirmacaoSenha' class="txtConfirmSenhaUsuarios" value="" type="password" maxlength="255">
                                  
                                  <?php
                                  
                                  
                                    }
                                  
                                  ?>
                               
                                  
                                </div>
                            </div>
                          
                            <div class="botao_form">
                                <input type="submit" name="btnEnviar" class="form_btn" id='btnSalvar' value="<?php echo($botao)?>">
                            </div>
                        </form>
                    </div>
                    
                    <!--TABELA COOM OS REGISTROS-->
                    <div class="tabela center">
                        <div class="linha_titulo">
                            <div class="titulo_form">
                                <h4>Nome</h4>
                            </div>
                            <div class="titulo_email">
                                <h4>Nível</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                            
                        <?php

                            //SELECT NA TABELA USUARIO E DO NIVEL DO USUARIO
                            $sql = "SELECT tbl_usuario.*,tbl_nivel_usuario.nivel_usuario 
                            FROM tbl_usuario INNER JOIN tbl_nivel_usuario 
                            ON tbl_usuario.cod_nivel_usuario = tbl_nivel_usuario.cod_nivel_usuario 
                            ORDER BY tbl_usuario.cod_usuario DESC";

                            $select = mysqli_query($conexao,$sql);

                            while($rsusuarios = mysqli_fetch_array($select)){

                            if($rsusuarios['ativo'] == 0){
                                $img = "img/cancel.png";
                                $legenda = "Desativado";

                            }elseif($rsusuarios['ativo'] == 1){
                                $img = "img/ok.png";
                                $legenda = "Ativado";

                            }

                        ?>    

                        <!--LINHAS COM OS REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($rsusuarios['nome']);?></span>
                            </div>
                            <div class="linha_email">
                                <span><?php echo($rsusuarios['nivel_usuario']);?></span>
                            </div>
                            <div class="linha_botao">


                                <figure class="editar">
                                    <a href="usuario.php?modo=buscar&id=<?php echo($rsusuarios['cod_usuario'])?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                    </a>
                                </figure>

                                <figure class="excluir">
                                    <a href="usuario.php?modo=excluir&id=<?php echo($rsusuarios['cod_usuario'])?>">

                                        <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                    </a>
                                </figure>

                                <figure class="desativar">
                                    <a href="usuario.php?ativo=<?php echo($rsusuarios['ativo'])?>&id=<?php echo($rsusuarios['cod_usuario'])?>&nivel=<?php echo($rsusuarios['cod_nivel_usuario'])?>">
                                        <img src="<?php echo($img)?>" alt="<?php echo($legenda)?>" title="<?php echo($legenda)?>" class="botao">
                                    </a>
                                </figure>


                            </div>

                        </div> 
                      
                        <?php
                          
                            }
                                       
                        ?>
                        
                    </div>
                    
                    
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
    </body>
</html>