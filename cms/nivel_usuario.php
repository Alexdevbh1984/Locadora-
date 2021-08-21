<?php
    

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $nivel_usuario = null;
    
    $botao = "Salvar";
    

    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
      //EXCLUINDO REGISTRO NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_nivel_usuario WHERE cod_nivel_usuario = ".addslashes($codigo)." ";
            
            mysqli_query($conexao,$sql);
        
        //FAZENDO BUSCA DE DADOS NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT * FROM tbl_nivel_usuario WHERE cod_nivel_usuario =".addslashes($codigo);
            
            $select = mysqli_query($conexao,$sql);
            
            if($rscontato = mysqli_fetch_array($select)){
                
                $nivel_usuario = $rscontato['nivel_usuario'];
                
                
            }
            
            $botao = "Editar";
            
            
        }
        
        
    }
    
    if(isset($_POST['btnEnviar'])){
        
        $nivel_usuario = $_POST['txt_nivel_usuario'];
        
      
        //INSERIDNO REGISTROS NO BANCO DE DADOS
        if($_POST['btnEnviar'] == "Salvar"){
        
            $sql = "INSERT INTO tbl_nivel_usuario(nivel_usuario) VALUES ('".addslashes($nivel_usuario)."')";


        //ATUALIZANDO REGISTROS NO BANCO DE DADOS    
        }elseif($_POST['btnEnviar'] == "Editar"){
            
            $sql = "UPDATE tbl_nivel_usuario SET nivel_usuario ='".addslashes($nivel_usuario)."' WHERE cod_nivel_usuario = ".$_SESSION['idregistro'];
            
            
            
        }
        
        if(mysqli_query($conexao,$sql)){

            header('location:nivel_usuario.php');
        }else{

            echo("<script>
                    alert('Erro no Cadastro');
                </script>");

        }
        
    }

    if(isset($_GET['ativo'])){
        
        $codigo = $_GET['id'];
        $ativo = $_GET['ativo'];
      
        //ATIVANDO O NIVEL  
        if($ativo == 0){
            
            $sql = "UPDATE tbl_nivel_usuario SET ativo = 1 WHERE cod_nivel_usuario =".$codigo;
          
            if(mysqli_query($conexao,$sql)){

                  header('location:nivel_usuario.php');
              }else{

                  echo("<script>
                          alert('Erro no Cadastro');
                      </script>");

              }
          
              
          
        //DESATIVANDO O NIVEL E O USUARIO QUE ESTÁ UTILIZANDO O NIVEL    
        }elseif($ativo == 1){
            
            $sql = "UPDATE tbl_nivel_usuario,tbl_usuario SET tbl_nivel_usuario.ativo = 0,tbl_usuario.ativo = 0
              WHERE tbl_nivel_usuario.cod_nivel_usuario = ".$codigo." AND tbl_usuario.cod_nivel_usuario =".$codigo;
          
            mysqli_query($conexao,$sql);
          
          $sql = "UPDATE tbl_nivel_usuario SET ativo = 0 WHERE cod_nivel_usuario =".$codigo;
          
          
            if(mysqli_query($conexao,$sql)){
                
                header('location:nivel_usuario.php');
            }else{
                
                echo("<script>
                        alert('Erro no Cadastro');
                    </script>");
                
            }
            
        }
        
        
    }
        
    

?>


<!doctype html>

<html lang="pt-br">
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/nivel_usuario.css" type="text/css" rel="stylesheet">
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
                    <div class="titulo_nivel_usuarios"><h2>Cadastro de Nivel de Usuários</h2></div>
                    <div class="cadastro_nivel_usuarios center">
                      
                        <!--FORMULARIO-->
                        <form action="nivel_usuario.php" method="post" name="frm_nivel_usuarios">
                          
                            <!--AREA DE CADASTRO DO NIVEL DO USUARIO-->
                            <div class="form_texto_usuario">
                                <label>Nível de Usuário:</label>
                            </div>
                            <div class="form_caixa_usuario">
                                <input name="txt_nivel_usuario" class="txtNivelUsuarios" type="text" value="<?php echo($nivel_usuario);?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
                            </div>

                            <div class="botao_form">
                                <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao)?>">
                            </div>
                        </form>
                    </div>
                    
                    <!--TABELA COM OS REGISTROS-->
                    <div class="tabela center">
                        <div class="linha_titulo">
                            <div class="titulo_form">
                                <h4>Nível do Usuário</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                        
                        <?php

                            //SELECT DOS REGISTROS DA TABELA NIVEL DE USUARIO
                            $sql = 'SELECT * FROM tbl_nivel_usuario ORDER BY nivel_usuario';

                            $select = mysqli_query($conexao, $sql);

                            while($rscontatos = mysqli_fetch_array($select)){

                                if($rscontatos['ativo'] == 0){

                                    $img = ('img/cancel.png');
                                    $legenda = "Desativado";

                                }elseif($rscontatos['ativo'] == 1){

                                    $img = ('img/ok.png');
                                    $legenda = "Ativado";
                                }


                        ?>
                      
                        <!--LINHAS COM REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($rscontatos['nivel_usuario']);?></span>
                            </div>
                            <div class="linha_botao">
                                <figure class="editar">
                                    <a href="nivel_usuario.php?modo=buscar&id=<?php echo($rscontatos['cod_nivel_usuario']);?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao " >
                                    </a>
                                </figure>

                                <figure class="excluir">
                                    <a href="nivel_usuario.php?modo=excluir&id=<?php echo($rscontatos['cod_nivel_usuario']);?>">
                                        <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao" onclick="return confirm('Deseja excluir esse nivel ?')">
                                    </a>
                                </figure>

                                <figure class="desativar">
                                    <a href="nivel_usuario.php?ativo=<?php echo($rscontatos['ativo'])?>&id=<?php echo($rscontatos['cod_nivel_usuario']);?>">

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