<?php
  require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }elseif($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 4){


?>

<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/adm_usuarios.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center clearfix">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                <div class="itens">
                    
                    <!--AREA DE CADASTRO DO USUARIO-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/group.png" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="usuario.php"><span class="link">Usuarios</span></a>
                        </div>
                    </div>
                    
                    <!--AREA DE CADASTRO DO NIVEL DE USUARIO-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/controls.png" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="nivel_usuario.php"><span class="link">Nível de Usuários</span></a>
                        </div>
                    </div>
                    
                    
                </div>
                
                <?php include('rodape.php')?>
                
            </div>
        </div>
    </body>
</html>

<?php 
  }else{
      
      header('location:index.php');
 }
?>