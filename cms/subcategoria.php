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
    $genero = null;
    $img = "img/cancel.png";
    $legenda = "Desativado";
    $cod_categoria = 0;
    $cod_genero = 0;
    $cod_produto_filme = 0;
    $modo = null;
    $status = null;
    $botao = "Salvar";

    if(isset($_POST['btnEnviar'])){
        
        $categoria = $_POST['slt_categoria'];
        $genero = $_POST['slt_subcategoria'];
        $produto_filme = $_POST['slt_produto'];
        
      //VERIFICANDO SE A CATEGORIA, O PRODUOT E A SUBCATEGORIA QUE ESTÃO SENDO CADASTRADOS JÁ EXISTEM
        $sql = "SELECT * FROM tbl_subcategoria WHERE cod_produto_filme =".$produto_filme." AND cod_categoria =".$categoria." AND cod_genero=".$genero." ";
        $select = mysqli_query($conexao,$sql);
      
      
        if(mysqli_num_rows($select) == 0){
      
            //INSERINDO REGISTROS NO BANCO DE DADOS
            if($_POST['btnEnviar'] == 'Salvar'){

              //INSERIR REGISTRO
              $sql = "INSERT INTO tbl_subcategoria (cod_categoria, cod_genero, cod_produto_filme) VALUES (".addslashes($categoria).",".addslashes($genero).", ".addslashes($produto_filme).")";
              //var_dump($sql);

              mysqli_query($conexao,$sql);

              header('location:subcategoria.php');

                 /* echo($sql);*/


            //ATUALIZANDO OS REGISTROS NO BANCO DE DADOS
            }elseif($_POST['btnEnviar'] == "Editar"){


                  $sql = "UPDATE tbl_subcategoria SET cod_categoria=".addslashes($categoria).", cod_genero=".addslashes($genero).", cod_produto_filme=".addslashes($produto_filme)." WHERE cod_subcategoria = ".$_SESSION['idregistro'];

                  mysqli_query($conexao,$sql);

                  header('location:subcategoria.php');


            }
          
          
        }else{
          echo("<script>alert('Já possui produto cadastrado nessa subcategoria e categoria')</script>");
        }
        
    }

    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUINDO REGISTROS
        if($modo == 'excluir'){
        
            $sql = "DELETE FROM tbl_subcategoria WHERE cod_subcategoria=".$codigo." ";

            mysqli_query($conexao,$sql);
          
          
        //FAZENDO BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            //SELECT NA TABELA CATEGORIA, A SUBCATEGORIA E O PRODUTO
            $sql = "SELECT tbl_categoria.*, tbl_genero.*, tbl_produto_filme.nome_filme, tbl_produto_filme.cod_produto_filme
                    FROM tbl_categoria INNER JOIN tbl_subcategoria
                    ON tbl_categoria.cod_categoria = tbl_subcategoria.cod_categoria
                    INNER JOIN tbl_genero
                    ON tbl_genero.cod_genero = tbl_subcategoria.cod_genero
                    INNER JOIN tbl_produto_filme
                    ON tbl_subcategoria.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                    WHERE tbl_subcategoria.cod_subcategoria =".$codigo;
            
            
            $select = mysqli_query($conexao, $sql);
            
            if($rssubcategoria = mysqli_fetch_array($select)){
                
                //PEGANDO AS INFORMAÇÕES QUE ESTÃO NO SELECT
                $categoria = $rssubcategoria['categoria'];
                $cod_categoria = $rssubcategoria['cod_categoria'];
              
                $genero = $rssubcategoria['genero'];
                $cod_genero = $rssubcategoria['cod_genero'];
              
                $produto_filme = $rssubcategoria['nome_filme'];
                $cod_produto_filme = $rssubcategoria['cod_produto_filme'];
              
              
                $botao = "Editar";
    
            }
            
            
        }
        
    }



?>

<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/subcategoria.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
      <script src="../js/jquery.js"></script>
        <script src="../js/valida.js"></script>
        <!-- <script src="js/funcoes.js"></script> -->
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
                  
                  
                      <div class="titulo_subcategoria"><h2>Cadastro de Subcategorias</h2></div>
                      <div class="cadastro_subcategoria center">

                        <!--FORMULARIO-->
                          <form action="subcategoria.php" method="post" name="frm_subcategoria">

                            <!--AREA DE CADASTRO DE CATEGORIAS-->
                              <div class="form_categoria">
                                  <div class="form_texto_usuario">
                                      <label>Categoria:</label>
                                  </div>
                                  <div class="form_caixa_usuario">


                                      <select name="slt_categoria" id="sltCategorias" class="txtCategoria" required title="Selecione um item na lista.">
                                      <?php

                                          if($modo == "buscar"){


                                      ?>    
                                          <option value="<?php echo($cod_categoria);?>" selected><span><?php echo($categoria)?></span></option>

                                      <?php

                                          }else{ 

                                      ?>       

                                          <option value="" selected><span>Selecione...</span></option>


                                      <?php
                                          }

                                          //seelct para trazer os niveis diferentes do selecionado
                                          $sql = "SELECT * FROM tbl_categoria WHERE cod_categoria <> ".$cod_categoria." ORDER BY categoria";

                                          $select = mysqli_query($conexao,$sql);

                                          while($rscategoria = mysqli_fetch_array($select)){

                                              if($rscategoria['ativo'] != 0){

                                      ?>

                                       <option value="<?php echo($rscategoria['cod_categoria']);?>"><?php echo($rscategoria['categoria']);?></option>


                                      <?php 
                                              }

                                          }

                                      ?>

                                      </select>


                                  </div>
                              </div>


                              <!--AREA DE CADASTRADO DE SUBCATEGORIA-->
                              <div class="form_subcategoria">
                                  <div class="form_texto_usuario">
                                      <label>Subcategoria:</label>
                                  </div>
                                  <div class="form_caixa_usuario">

                                      <!--SELECT DA SUBCATEGORIA-->
                                      <select name="slt_subcategoria" id="sltSubcategorias" class="txtSubcategoria" required>

                                        <!--SE O MODO FOR BUSCAR, IRÁ APARECER NO SELECT O OPTION DO REGISTRO QUE FOI CADASTRADO-->
                                        <?php

                                          if($modo == "buscar"){


                                        ?>
                                        <option value="<?php echo($cod_genero)?>" selected><?php echo($genero)?></option>
                                       

                                        <?php

                                            

                                          }else{


                                        ?>

                                            <option value="" selected>Selecione...</option>

                                        <?php


                                            }

                                            //SELECT QUE TRAZ TODAS AS SUBCATEGORIAS
                                            $sql = "SELECT * FROM tbl_genero WHERE ativo = 1 ";
                                            $select = mysqli_query($conexao, $sql);

                                            while($rsgeneros = mysqli_fetch_array($select)){




                                        ?>
                                          <!--SE NÃO IRÁ APARECER TODAS AS SUBCATEGORIAS-->
                                          <option value="<?php echo($rsgeneros['cod_genero'])?>"><?php echo($rsgeneros['genero'])?></option>

                                        <?php

                                            }
                                        

                                        ?>



                                      </select>
                                  </div>
                              </div>

                              <!--AREA DE CADASTRO DO PRODUTO-->
                              <div class="form_produto">
                                  <div class="form_texto_usuario">
                                      <label>Produto:</label>
                                  </div>
                                  <div class="form_caixa_usuario">


                                      <select name="slt_produto" class="txtProduto" required title="Selecione um item na lista.">
                                      <?php

                                          if($modo == "buscar"){


                                      ?>    
                                          <option value="<?php echo($cod_produto_filme);?>" selected><span><?php echo($produto_filme)?></span></option>

                                      <?php

                                          }else{ 

                                      ?>       

                                          <option value="" selected><span>Selecione...</span></option>


                                      <?php
                                          }

                                          //seelct para trazer os niveis diferentes do selecionado
                                          $sql = "SELECT * FROM tbl_produto_filme WHERE cod_produto_filme <> ".$cod_produto_filme." ORDER BY nome_filme";

                                          $select = mysqli_query($conexao,$sql);

                                          while($rsproduto = mysqli_fetch_array($select)){

                                              if($rsproduto['ativo_filme'] != 0){

                                      ?>

                                                  <option value="<?php echo($rsproduto['cod_produto_filme']);?>"><?php echo($rsproduto['nome_filme']);?></option>


                                      <?php 
                                              }

                                          }

                                      ?>

                                      </select>


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
                                <h4>Subcategoria</h4>
                            </div>
                            <div class="titulo_email">
                                <h4>Categoria</h4>
                            </div>
                            <div class="titulo_email">
                                <h4>Produto</h4>
                            </div>
                          
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                            
                        <?php

                            //SELECT NA TABELA USUARIO E DO NIVEL DO USUARIO
                            $sql = "SELECT tbl_categoria.*,tbl_produto_filme.nome_filme, tbl_genero.genero, tbl_subcategoria.*
                            FROM tbl_categoria INNER JOIN tbl_subcategoria
                            ON tbl_categoria.cod_categoria = tbl_subcategoria.cod_categoria
                            
                            INNER JOIN tbl_genero
                            ON tbl_subcategoria.cod_genero = tbl_genero.cod_genero
                            
                            INNER JOIN tbl_produto_filme
                            ON tbl_subcategoria.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                            
                            ORDER BY tbl_subcategoria.cod_subcategoria DESC";

                            $select = mysqli_query($conexao,$sql);

                            while($rssubcategorias = mysqli_fetch_array($select)){

                            if($rssubcategorias['ativo'] == 0){
                                $img = "img/cancel.png";
                                $legenda = "Desativado";

                            }elseif($rssubcategorias['ativo'] == 1){
                                $img = "img/ok.png";
                                $legenda = "Ativado";

                            }

                        ?>    

                        <!--LINHAS COM OS REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($rssubcategorias['genero']);?></span>
                            </div>
                            <div class="linha_email">
                                <span><?php echo($rssubcategorias['categoria']);?></span>
                            </div>
                            <div class="linha_email">
                                <span><?php echo($rssubcategorias['nome_filme']);?></span>
                            </div>
                            <div class="linha_botao">


                                <figure class="editar">
                                    <a href="subcategoria.php?modo=buscar&id=<?php echo($rssubcategorias['cod_subcategoria'])?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                    </a>
                                </figure>

                                <figure class="excluir">
                                    <a href="subcategoria.php?modo=excluir&id=<?php echo($rssubcategorias['cod_subcategoria'])?>">

                                        <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                    </a>
                                </figure>

                                <!--<figure class="desativar">
                                    <a href="subcategoria.php?ativo=<?php echo($rssubcategorias['ativo'])?>&id=<?php echo($rssubcategorias['cod_subcategoria'])?>&categoria=<?php echo($rssubcategorias['cod_categoria'])?>">
                                        <img src="<?php echo($img)?>" alt="<?php echo($legenda)?>" title="<?php echo($legenda)?>" class="botao">
                                    </a>
                                </figure>-->


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