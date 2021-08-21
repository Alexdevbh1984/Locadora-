<?php

  require_once('db/conexao.php');
  $conexao = conexaoMysql();

  session_start();

  //VERIFICACAO DA AREA DO LOGIN
  if(isset($_POST['btnOk'])){
    
    /*PEGO OS VALORES DAS CAIXAS DE TEXTO DO USUARIO E DA SENHA*/
    $usuario = $_POST['txtUsuario'];
    $senha = $_POST['txtSenha'];
    
    /*CRIPTOGRAFANDO A SENHA*/
    $senha_criptografada = md5($senha);
    
    /*FAZENDO UM SELECT NO BANCO PARA SABER SE AS SENHAS E O USUARIO CADASTRADOS ESTÃO CADASTRADOS*/
     $sql = "SELECT tbl_usuario.*, tbl_nivel_usuario.* 
            FROM tbl_usuario INNER JOIN tbl_nivel_usuario
            ON tbl_nivel_usuario.cod_nivel_usuario = tbl_usuario.cod_nivel_usuario
            WHERE tbl_usuario.ativo = 1 AND tbl_usuario.email ='".addslashes($usuario)."' AND tbl_usuario.senha = '".addslashes($senha_criptografada)."' AND tbl_nivel_usuario.ativo = 1";
    
    $select = mysqli_query($conexao,$sql);
    
    /*VERIFICACAO PARA SABER SE O RETORNO DE LINHAS FOR IGUAL A 1*/
    if(mysqli_num_rows($select) == 1){
      
      /*PEGA TODAS AS INFORMACOES DO REGISTRO*/
      if($login = mysqli_fetch_array($select)){
      
        /*GUARDANDO NAS VARIAVEIS DE SESSAO O USUARIO, A SENHA, O EMAIL*/
        $_SESSION['nome'] = $login['nome'];
        $_SESSION['email'] = $login['email'];
        $_SESSION['senha'] = $login['senha'];
        $_SESSION['nivel'] = $login['cod_nivel_usuario'];
        $_SESSION['login'] = "ativado";

        /*COMO TUDO DEU CERTO, ENTÃO EU REDIRECIONO PARA O CMS*/
        header('location:cms/');
      }
      
    }else{
      
      header('location:index.php?erro');
      
    }
    
    
  }

  


?>