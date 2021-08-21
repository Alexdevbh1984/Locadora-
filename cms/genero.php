<?php
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $img = "img/cancel.png";
    $legenda = "Desativado";
    $genero = null;
    $botao = "Salvar";
    $modo = null;

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUINDO REGISTROS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_genero WHERE cod_genero = ".addslashes($codigo)."";
            
            mysqli_query($conexao, $sql);
            
        
        //FAZENDO UMA BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            $sql = "SELECT * FROM tbl_genero
                    WHERE cod_genero =".addslashes($codigo);
            
            $select = mysqli_query($conexao, $sql);
            
            if($rsgenero = mysqli_fetch_array($select)){
                
                
                /*var_dump($rsloja);*/
                
                $genero = $rsgenero['genero'];

            }
            
                $botao = 'Editar';
            
            
        }
        
        
        
    }
    

    if(isset($_POST['btnEnviar'])){
        
        $genero = $_POST['txt_genero'];
        
      
          //INSERIR REGISTROS NO BANCO DE DADOS         
          if($_POST['btnEnviar'] == "Salvar"){

              $sql = "INSERT INTO tbl_genero (genero)
                      VALUES ('".addslashes($genero)."')";

            
          //ATUALIZANDO OS REGISTROS NO BANCO DE DADOS  
          }elseif($_POST['btnEnviar'] == "Editar"){


              $sql = "UPDATE tbl_genero SET genero = '".addslashes($genero)."' WHERE cod_genero =".$_SESSION['idregistro'];


          }


          if(mysqli_query($conexao,$sql)){

              header('location:genero.php');

          }else{

              echo('erro');

          }
    }


    //ATIVANDO E DESATIVANDO O REGISTRO
    if(isset($_GET['ativo'])){

    $id = $_GET['id'];
      $ativo = $_GET['ativo'];

    
    /*ATIVANDO O GENERO*/
    if($ativo == 0){
      
      /*ATIVANDO REGISTRO*/
      $sql = "UPDATE tbl_genero SET ativo = 1 WHERE cod_genero =".$id;
      

    //SENÃO DESATIVA O GENERO  
    }else{
      
      $sql = "UPDATE tbl_genero SET ativo = 0 WHERE cod_genero =".$id;
      
      
    }
      
      if(mysqli_query($conexao,$sql)){

        header('location:genero.php');

      }else{

        echo("Erro");

      }
      
      
    
  }
               


?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/genero.css" type="text/css" rel="stylesheet">
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
                  
                    <!--MENU VERTICAL RELACIONADO AO FILME-->
                    <div id="menu_cadastro">
                        <a href="filme.php">
                            <div class="menu menu_filme">

                                 Filme

                            </div>
                        </a> 

                        <a href="diretor.php">
                            <div class="menu menu_opcoes">

                                 Diretor

                            </div>
                        </a>  
                        <a href="genero.php">
                              <div class="menu menu_opcoes">

                                   Gênero

                            </div>
                        </a>  
                        <a href="classificacao.php">
                            <div class="menu menu_opcoes">

                                 Classificação

                            </div>
                        </a>  

                        <a href="ator.php">
                            <div class="menu menu_opcoes">

                                 Ator

                            </div>
                        </a>  

                        <div class="menu menu_opcoes">

                             Relação
                            <div class="sub_menu">
                                <a href="filme_ator.php">
                                  <div class="sub_itens">
                                      Ator e Filme
                                  </div>
                              </a>
                                <a href="filme_diretor.php">

                                  <div class="sub_itens">
                                      Diretor e Filme
                                  </div>
                              </a>
                                <a href="filme_genero.php">

                                  <div class="sub_itens">
                                      Gênero e Filme
                                  </div>
                              </a>


                            </div>

                        </div>

                        <a href="filme_mes.php">
                            <div class="menu menu_opcoes">

                                 Filme do mês

                            </div>
                        </a>  
                        
                    </div>
                    
                    <div class="cadastro">
                        <div class="titulo_genero"><h2>Cadastro de Gêneros</h2></div>
                        <div class="cadastro_genero center">
                          
                            <!--FORMULÁRIO-->
                            <form action="genero.php" method="post" name="frm_genero" enctype="multipart/form-data">
                                
                                <!--CADASTRO DE GENERO-->
                                <div class="form_genero">
                                    <div class="form_texto_genero">
                                        <label>Gênero:</label>
                                    </div>
                                    <div class="form_caixa_genero">
                                        <input name="txt_genero" class="txtGenero" type="text" value="<?php echo($genero)?>" onkeypress="return ValidarNumero(event);" required autocomplete="off" maxlength="100">
                                    </div>
                                </div>

                                <div class="botao_form">
                                    <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao);?>">
                                </div>
                            </form>
                        </div>
                      
                        <!--TABELA DE REGISTROS-->
                        <div class="tabela center">
                          
                            <!--TITULOS DAS COLUNAS-->
                            <div class="linha_titulo">
                                <div class="titulo_form">
                                    <h4>Genero</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>

                                <?php

                                    /*SELECT PARA PEGAR TODAS AS LOJAS, AS CIDADES E OS ESTADOS*/
                                    $sql = "SELECT * from tbl_genero";


                                    $select = mysqli_query($conexao, $sql);



                                    while($rsgenero = mysqli_fetch_array($select)){
                                      
                                      if($rsgenero['ativo'] == 0){
                                        
                                      $img = "img/cancel.png";
                                      $legenda = "Desativado";
                                        
                                      }else{
                                        
                                        $img = "img/ok.png";
                                      $legenda = "Ativado";
                                       
                                      }
                                      



                                ?>
                                
                                <!--LINHAS COM OS REGISTROS-->
                                <div class="informacoes_form">
                                    <div class="linha_informacao">
                                        <span><?php echo($rsgenero['genero'])?></span>
                                    </div>
                                    <div class="linha_botao">

                                        <figure class="editar">
                                            <a href="genero.php?modo=buscar&id=<?php echo($rsgenero['cod_genero'])?>">
                                                <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                            </a>
                                        </figure>

                                        <figure class="excluir">
                                            <a href="genero.php?modo=excluir&id=<?php echo($rsgenero['cod_genero']);?>">

                                                <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                            </a>
                                        </figure>
                                      
                                       <figure class="desativar">
                                            <a href="genero.php?ativo=<?php echo($rsgenero['ativo']);?>&id=<?php echo($rsgenero['cod_genero']);?>">

                                                <img src="<?php echo($img)?>" alt="<?php echo($legenda)?>" title="<?php echo($legenda)?>" class="botao" >

                                            </a>
                                        </figure>


                                    </div>

                                </div>  
                                <?php

                                    }

                                ?>


                        </div>
                    </div>
                  
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
        <script src="../js/expressaoRegular.js"></script>

    </body>
</html>