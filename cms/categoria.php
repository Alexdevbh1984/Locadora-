<?php
    

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    //CONEXÃO
    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $categoria = null;
    
    $botao = "Salvar";
    

    //SE A VARIÁVEL NA URL EXITIR
    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
      //GUARDANDO NA VARIÁVEL DE SESSÃO O ID DO REGISTRO
        $_SESSION['idregistro'] = $codigo;
        
      //EXCLUINDO REGISTRO NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_categoria WHERE cod_categoria = ".addslashes($codigo)." ";
            
            mysqli_query($conexao,$sql);
        
        //FAZENDO BUSCA DE DADOS NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT * FROM tbl_categoria WHERE cod_categoria =".addslashes($codigo);
            
            $select = mysqli_query($conexao,$sql);
            
            if($rscategoria = mysqli_fetch_array($select)){
                
                $categoria = $rscategoria['categoria'];
                
                
            }
            
            $botao = "Editar";
            
            
        }
        
        
    }
    

    //AO APERTAR O BOTÃO SALVAR OU EDITAR
    if(isset($_POST['btnEnviar'])){
        
        $categoria = $_POST['txt_categoria'];
        
      
        //INSERIDNO REGISTROS NO BANCO DE DADOS
        if($_POST['btnEnviar'] == "Salvar"){
        
            $sql = "INSERT INTO tbl_categoria(categoria) VALUES ('".addslashes($categoria)."')";


        //ATUALIZANDO REGISTROS NO BANCO DE DADOS    
        }elseif($_POST['btnEnviar'] == "Editar"){
            
            $sql = "UPDATE tbl_categoria SET categoria ='".addslashes($categoria)."' WHERE cod_categoria = ".$_SESSION['idregistro'];
            
            
            
        }
        
        if(mysqli_query($conexao,$sql)){

            header('location:categoria.php');
        }else{

            echo("<script>
                    alert('Erro no Cadastro');
                </script>");

        }
        
    }


    //ATIVANDO OU DESATIVANDO O REGSITRO
    if(isset($_GET['ativo'])){
        
        $codigo = $_GET['id'];
        $ativo = $_GET['ativo'];
      
        //SE O ATIVO DO REGISTRO FOR IGUAL A "0", ENTÃO DESATIVADO, IRÁ ATIVAR
        if($ativo == 0){
            
          $sql = "UPDATE tbl_categoria SET ativo = 1 WHERE cod_categoria =".$codigo;
          
          if(mysqli_query($conexao,$sql)){

              header('location:categoria.php');
          }else{

              echo("<script>
                      alert('Erro no Cadastro');
                  </script>");

          }

              
        //SE O ATIVO DO REGISTRO FOR IGUAL A "1", ENTÃO ATIVADO, IRÁ DESATIVAR  
        }else{
          
          
          $sql = "UPDATE tbl_categoria SET ativo = 0 WHERE cod_categoria =".$codigo;
          
          mysqli_query($conexao,$sql);
             
           

          header('location:categoria.php');
          
          
              
          
        }
      
    }
        
    
    

?>


<!doctype html>

<html lang="pt-br">
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/categoria.css" type="text/css" rel="stylesheet">
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
                  <div id="menu_cadastro">
                      <a href="filme.php">
                          <div class="menu menu_filme">

                               Filme

                          </div>
                      </a> 
                      <a href="categoria.php">
                          <div class="menu menu_opcoes">

                               Categoria

                          </div>
                      </a>
                      <a href="subcategoria.php">
                          <div class="menu menu_opcoes">

                               Subcategoria

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
                  
                    <div class="titulo_categoria"><h2>Cadastro de Categorias</h2></div>
                    <div class="cadastro_categoria center">
                      
                        <!--FORMULARIO-->
                        <form action="categoria.php" method="post" name="frm_categoria">
                          
                            <!--AREA DE CADASTRO DA CATEGORIA-->
                            <div class="form_texto_usuario">
                                <label>Categoria:</label>
                            </div>
                            <div class="form_caixa_usuario">
                                <input name="txt_categoria" class="txtCategoria" type="text" value="<?php echo($categoria);?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
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
                                <h4>Categoria</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                        
                        <?php

                            //SELECT DOS REGISTROS DA TABELA CATEGORIA
                            $sql = 'SELECT * FROM tbl_categoria ORDER BY categoria';

                            $select = mysqli_query($conexao, $sql);

                            while($rscategoria = mysqli_fetch_array($select)){

                                //SE O ATIVO IGUAL A "0", A IMAGEM SERÁ DE X
                                if($rscategoria['ativo'] == 0){

                                    $img = ('img/cancel.png');
                                    $legenda = "Desativado";

                                //SE O ATIVO IGUAL A "1", A IMAGEM SERÁ DE OK
                                }elseif($rscategoria['ativo'] == 1){

                                    $img = ('img/ok.png');
                                    $legenda = "Ativado";
                                }


                        ?>
                      
                        <!--LINHAS COM REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($rscategoria['categoria']);?></span>
                            </div>
                          
                            <!--AREA COM OS BOTÕES-->
                            <div class="linha_botao">
                              
                                <!--AREA COM O BOTÃO EDITAR-->
                                <figure class="editar">
                                    <a href="categoria.php?modo=buscar&id=<?php echo($rscategoria['cod_categoria']);?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao " >
                                    </a>
                                </figure>

                                <!--AREA COM O BOTÃO EXCLUIR-->
                                <figure class="excluir">
                                    <a href="categoria.php?modo=excluir&id=<?php echo($rscategoria['cod_categoria']);?>">
                                        <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao" onclick="return confirm('Deseja excluir essa categoria ?')">
                                    </a>
                                </figure>
                                
                                <!--AREA COM O BOTÃO DE DESATIVAR E ATIVAR-->
                                <figure class="desativar">
                                    <a href="categoria.php?ativo=<?php echo($rscategoria['ativo'])?>&id=<?php echo($rscategoria['cod_categoria']);?>">

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
                    
                    
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
    </body>
</html>