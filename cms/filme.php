<?php

  session_start();

  /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
  if(!isset($_SESSION['login'])){

    session_destroy();
    header('location:../index.php');

  }
  
  require_once('../funcoes.php');
  require_once('../db/conexao.php');
  $conexao = conexaoMysql();
  
  //VARIAVEIS DECLARADAS
  $nome_filme = null;
  $lancamento = null;
  $preco = null;
  $tempo = null;
  $descricao = null;
  $cod_classificacao = 0;
  $img = "img/cancel.png";
  $legenda = "Desativado";

  $botao = "Salvar";

//APERTANDO O BOTÃO DDE ENVIAR OU  EDITAR
  if(isset($_POST['btnEnviar'])){
      
      //PEGANDO TODAS AS INFORMAÇÕES DOS IMPUT
      $nome_filme = $_POST['txt_nome_filme'];
      $tempo = $_POST['txt_tempo'];
      $preco = precoBancoDados($_POST['txt_preco']);
      $descricao = $_POST['txt_descricao'];
      $classificacao = $_POST['slt_classificacao'];
      $lancamento = dataBancoDados($_POST['txt_lancamento']);

      $arquivos_permitidos = array(".jpg", ".jpeg", ".png");
      $diretorio = "arquivos/";

      $arquivo = $_FILES['fle_foto']['name'];//FOTO DO FILME
      $arquivo_slider = $_FILES['fle_foto_slider']['name'];//FOTO DO SLIDER DO FILME

      $tamanho_arquivo = $_FILES['fle_foto']['size'];//TAMANHO DA FOTO DO FILME
      $tamanho_arquivo_slider = $_FILES['fle_foto_slider']['size'];//TAMANHO DA FOTO DO SLIDER DO FILME

      $tamanho_arquivo = round($tamanho_arquivo/1024);//TRANSFORMANDO O TAMANHO DA FOTO DO FILME
      $tamanho_arquivo_slider = round($tamanho_arquivo_slider/1024);//TRANSFORMANDO O TAMANHO DA FOTO DO SLIDER DO FILME

      $extensao_arquivo = strrchr($arquivo, ".");//PEGANDO SÓ A EXTENSÃO DA FOTO DO FILME
      $extensao_arquivo_slider = strrchr($arquivo_slider, ".");//PEGANDO SÓ A EXTENSÃO DA FOTO DO FLISER DO FILME

      $nome_arquivo = pathinfo($arquivo, PATHINFO_FILENAME);//PEGANDO SÓ O NOME DA FOTO DO FILME
      $nome_arquivo_slider = pathinfo($arquivo_slider, PATHINFO_FILENAME);//PEGANDO SÓ O NOME DA FOTO DO SLIDER DO FILME

      $arquivo_criptografado = md5(uniqid(time()).$nome_arquivo);//CRIPTOGRAFANDO O NOME DA FOTO DO FILME
      $arquivo_criptografado_slider = md5(uniqid(time()).$nome_arquivo_slider);//CRIPTOGRAFANDO O NOME DA FOTO DO SLIDER DO FILME

      $foto = $arquivo_criptografado . $extensao_arquivo;//CONCATENANDO O NOME DO FILME CRIPTOGRAFADO COM A EXTENSÃO
      $foto_slider = $arquivo_criptografado_slider . $extensao_arquivo_slider;///CONCATENANDO O NOME DA FOTO DO SLIDER DO FILME CRIPTOGRAFADO COM A EXTENSÃO
     
    
      //SE O INPUT DA FOTO DO FILME E DO SLIDER NÃO ESTIVEREM VAZIOS, IRÁ SALVAR E EDITAR COM A FOTO DO FILME E A FOTO DO SLIDER
      if(!empty($_FILES['fle_foto']['name']) && !empty($_FILES['fle_foto_slider']['name'])){
        
        //VERIFICANDO SE AS EXTENSÕES DOS ARQUIVOS SÃO APROVADAS
        if(in_array($extensao_arquivo, $arquivos_permitidos) && in_array($extensao_arquivo_slider, $arquivos_permitidos)){

          //VERIFICANDO SE A FOTO MEDE MENOS QUE 5000MB
          if($tamanho_arquivo <= 5000 && $tamanho_arquivo_slider <= 5000){

              $arquivo_temp = $_FILES['fle_foto']['tmp_name'];
              $arquivo_temp_slider = $_FILES['fle_foto_slider']['tmp_name'];

              //MOVENDO PARA O SERVIDOR OS DOIS ARQUIVOS
              if(move_uploaded_file($arquivo_temp, $diretorio.$foto) && move_uploaded_file($arquivo_temp_slider, $diretorio.$foto_slider) ){
                
                  //INSERINDO REGISTRO NO BANCO
                  if($_POST['btnEnviar'] == "Salvar"){
                    
                    
                      $sql = "INSERT INTO tbl_produto_filme (nome_filme, tempo, preco, lancamento, descricao, cod_classificacao, foto, imagem_slide) VALUES (
                              '".addslashes($nome_filme)."', '".addslashes($tempo)."', ".addslashes($preco).",'".addslashes($lancamento)."','".addslashes($descricao)."', ".addslashes($classificacao).", '".addslashes($foto)."', '".addslashes($foto_slider)."')";


                      //var_dump($sql);
                    
                      mysqli_query($conexao,$sql);

                  }elseif($_POST['btnEnviar'] == "Editar"){
                    
                    //ATUALIZANDO REGISTRO NO BANCO
                    $sql = "UPDATE tbl_produto_filme SET nome_filme = '".addslashes($nome_filme)."', tempo = '".addslashes($tempo)."', preco = ".addslashes($preco).", lancamento = '".addslashes($lancamento)."', descricao = '".addslashes($descricao)."', cod_classificacao = ".addslashes($classificacao).", foto = '".addslashes($foto)."', imagem_slide = '".addslashes($foto_slider)."' WHERE cod_produto_filme =".$_SESSION['idregistro'];
                    
                      //var_dump($sql);
                    
                   if(mysqli_query($conexao,$sql)){

                      unlink("arquivos/".$_SESSION['nome_foto_filme']);
                      unlink("arquivos/".$_SESSION['nome_slide_filme']);
                    }
                    
                  }
                  
                  header('location:filme.php');


              }else{

                  echo("<script> alert('Erro ao enviar o arquivo para o servidor!!!') </script>");

              }

          }else{

              echo("<script> alert('Tamanho de arquivo inválido!!)</script>");

          }


        }else{

            echo("<script> alert('Extensão de arquivo inválido!') </script>");
        }
        
        
      //SE O INPUT DA FOTO DO FILME NÃO ESTIVER VAZIO IRÁ SALVAR E EDITAR SÓ COM A FOTO DO SILME
      }elseif(!empty($_FILES['fle_foto']['name'])){
        
        //VERIFICANDO SE A EXTENSÃO DO ARQUIVO É ACEITA
        if(in_array($extensao_arquivo, $arquivos_permitidos)){

          
          //VERIFICANDO SE A FOTO MEDE MENOS QUE 5000MB
          if($tamanho_arquivo <= 5000){

              $arquivo_temp = $_FILES['fle_foto']['tmp_name'];

              //MOVENDO PARA O SERVIDOR O ARQUIVO
              if(move_uploaded_file($arquivo_temp, $diretorio.$foto)){
                
                
                  //INSERINDO REGISTRO NO BANCO
                  if($_POST['btnEnviar'] == "Salvar"){
                    
                    
                      $sql = "INSERT INTO tbl_produto_filme (nome_filme, tempo, preco, lancamento, descricao, cod_classificacao, foto) VALUES (
                              '".addslashes($nome_filme)."', '".addslashes($tempo)."', ".addslashes($preco).",'".addslashes($lancamento)."','".addslashes($descricao)."', ".addslashes($classificacao).", '".addslashes($foto)."')";


                    //var_dump($sql);
                    
                      mysqli_query($conexao,$sql);

                  }elseif($_POST['btnEnviar'] == "Editar"){
                    
                    //ATUALIZANDO REGISTRO NO BANCO
                    $sql = "UPDATE tbl_produto_filme SET nome_filme = '".addslashes($nome_filme)."', tempo = '".addslashes($tempo)."', preco = ".addslashes($preco).", lancamento = '".addslashes($lancamento)."', descricao = '".addslashes($descricao)."', cod_classificacao = ".addslashes($classificacao).", foto = '".addslashes($foto)."' WHERE cod_produto_filme =".$_SESSION['idregistro'];


                    //var_dump($:sql);
                    
                      if(mysqli_query($conexao,$sql)){

                        unlink("arquivos/".$_SESSION['nome_foto_filme']);
                      }
                    
                  }
                  
                  header('location:filme.php');


              }else{

                  echo("<script> alert('Erro ao enviar o arquivo para o servidor!!!') </script>");

              }

          }else{

              echo("<script> alert('Tamanho de arquivo inválido!!)</script>");

          }


        }else{

            echo("<script> alert('Extensão de arquivo inválido!') </script>");
        }
        
      //SE O INPUT DA FOTO DO SLIDER DO FILME NÃO ESTIVER VAIZO IRÁ EDITAR SOMENTE A FOTO DO SLIDER DO FILME
      }elseif(!empty($_FILES['fle_foto_slider']['name'])){
        
        //VERIFICANDO SE A EXTENSÃO DO ARQUIVO É ACEITA
        if(in_array($extensao_arquivo_slider, $arquivos_permitidos)){

          //VERIFICANDO SE A FOTO MEDE MENOS QUE 5000MB
          if($tamanho_arquivo_slider <= 5000){

              $arquivo_temp_slider = $_FILES['fle_foto_slider']['tmp_name'];

              //MOVENDO PARA O SERVIDOR O ARQUIVO
              if(move_uploaded_file($arquivo_temp_slider, $diretorio.$foto_slider)){
                
                if($_POST['btnEnviar'] == "Editar"){
                    
                  //ATUALIZANDO REGISTRO NO SERVIDOR
                    $sql = "UPDATE tbl_produto_filme SET nome_filme = '".addslashes($nome_filme)."', tempo = '".addslashes($tempo)."', preco = ".addslashes($preco).", lancamento = '".addslashes($lancamento)."', descricao = '".addslashes($descricao)."', cod_classificacao = ".addslashes($classificacao).", imagem_slide = '".addslashes($foto_slider)."' WHERE cod_produto_filme =".$_SESSION['idregistro'];


                  //var_dump($sql);
                    
                      if(mysqli_query($conexao,$sql)){

                        unlink("arquivos/".$_SESSION['nome_slide_filme']);
                      }
                    
                  }
                  
                  header('location:filme.php');


              }else{

                  echo("<script> alert('Erro ao enviar o arquivo para o servidor!!!') </script>");

              }

          }else{

              echo("<script> alert('Tamanho de arquivo inválido!!)</script>");

          }


        }else{

            echo("<script> alert('Extensão de arquivo inválido!') </script>");
        }
    
      //SE NENHUM DOS INPUT TIVEREM FOTO, ENTÃO IRÁ EDITAR O REGISTRO SE A FOTO
      }else{
        
        
                
          if($_POST['btnEnviar'] == "Editar"){

              $sql = "UPDATE tbl_produto_filme SET nome_filme = '".addslashes($nome_filme)."', tempo = '".addslashes($tempo)."', preco = ".addslashes($preco).", lancamento = '".addslashes($lancamento)."', descricao = '".addslashes($descricao)."', cod_classificacao = ".addslashes($classificacao)." WHERE cod_produto_filme =".$_SESSION['idregistro'];
            
              


            //var_dump($sql);

                mysqli_query($conexao,$sql);

                header('location:filme.php');
            }

      }


  }

//SE A VARIAVEL MODO EXISTIR NA URL
  if(isset($_GET['modo'])){
      $modo = $_GET['modo'];
      $codigo = $_GET['id'];

      //GUARDANDO NA VARIÁVEL DE SESSÃO O CÓDIGO
      $_SESSION['idregistro'] = $codigo;

      //SE MODO FOR INGUAL A EXCLUIR
      if($modo == 'excluir'){

          $foto_excluir = $_GET['foto'];
          $imagem_excluir = $_GET['imagem_slide'];

          //EXCLUINDO REGISTRO, FOI USADO UMA TRIGEER PARA REALIZAR A EXCLUSÃO DO FILME
          $sql = "DELETE FROM tbl_produto_filme WHERE cod_produto_filme = ".addslashes($codigo)." ";
          mysqli_query($conexao, $sql);

          //PEGANDO O CAMINHO DA FOTO
          $foto = "arquivos/".$foto_excluir;
          $imagem = "arquivos/".$imagem_excluir;
          //APAGANDO AS FOTOS DO SERVIDOR  
          unlink($foto);
          unlink($imagem);

          header('location:filme.php');

      //SE MODO FOR IGUAL A BUSCAR
      }elseif($modo == 'buscar'){

          //SELECT RESPONSÁVEL PELA BUSCA DO REGISTRO
          $sql = "SELECT tbl_produto_filme.*, tbl_classificacao.*
                  FROM tbl_produto_filme INNER JOIN tbl_classificacao
                  ON tbl_produto_filme.cod_classificacao = tbl_classificacao.cod_classificacao
                  WHERE tbl_produto_filme.cod_produto_filme =".$codigo;

          $select = mysqli_query($conexao,$sql);

          //PEGANDO TODAS AS INFORMAÇÕES DO REGISTRO DO FILME
          if($rsbuscafilme = mysqli_fetch_array($select)){

              $nome_filme = $rsbuscafilme['nome_filme'];
              $tempo = $rsbuscafilme['tempo'];
              $preco = precoBrasileiro($rsbuscafilme['preco']);
              $descricao = $rsbuscafilme['descricao'];
              $lancamento = dataBrasileiro($rsbuscafilme['lancamento']);
              $cod_classificacao = $rsbuscafilme['cod_classificacao'];
              $classificacao = $rsbuscafilme['classificacao'];

              $foto = $rsbuscafilme['foto'];
              $imagem_slide = $rsbuscafilme['imagem_slide'];
              $botao = "Editar";
            
              $_SESSION['nome_foto_filme'] = $foto;
              $_SESSION['nome_slide_filme'] = $imagem_slide;
            


          }


      }

  }

//SE A VARIÁVEL ATIVO_FILME EXISTE NA URL
  if(isset($_GET['ativo_filme'])){

    //PEGARÁ O ID DO REGISTRO E SE ELE ESTÁ ATIVO OU NÃO, GUARDANDO NA VARIÁVEL
    $id = $_GET['id'];
    $ativo = $_GET['ativo_filme'];
    
    //SE O REGSITRO TIVER O ATIVO IGUAL A 0, IRÁ ATIVAR O REGISTRO COM 1
    if($ativo == 0){
      
      $sql = "UPDATE tbl_produto_filme SET ativo_filme = 1 WHERE cod_produto_filme =".$id;
      
      mysqli_query($conexao,$sql);
      
    //SE O REGSITRO TIVER O ATIVO IGUAL A 1, IRÁ DESATIVAR O REGISTRO COM 0 
    }elseif($ativo == 1){
      
      $sql = "UPDATE tbl_produto_filme SET ativo_filme = 0 WHERE cod_produto_filme =".$id;
      
      mysqli_query($conexao,$sql);
      
      
    }
    
    header("location:filme.php");
    
  }


?>


<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/filme.css" type="text/css" rel="stylesheet">
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
                  
                    <!--AREA RESPONSÁVEL PELO CADASTRO DO FILME-->
                    <div class="cadastro">
                        <div class="titulo_filme"><h2>Cadastro de Filmes</h2></div>
                        <div class="cadastro_filme center">
                          
                          <!--FORMULÁRIO-->
                            <form action="filme.php" method="post" name="frm_filme" enctype="multipart/form-data">
                              
                                
                                <?php
  
                                    if(isset($nome_foto)){


                                ?>
                                    <div class="caixa_foto center">
                                        <img src="arquivos/<?php echo($rsator['foto'])?>" class="foto">
                                    </div>
                                <?php

                                    }

                                ?>

                                <!--AREA DO NOME DO FILME-->
                                <div class="form_nome_completo">
                                    <div class="form_texto_usuario">
                                        <label>Nome Filme:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_nome_filme" class="txtNomeCompleto" type="text" value="<?php echo($nome_filme)?>" required autocomplete="off" maxlength="255">
                                    </div>
                                </div>

                                <!--AREA DO PREÇO DO FILME-->
                                <div class="form_nome_artistico">
                                    <div class="form_texto_usuario">
                                        <label>Preco:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_preco" id="txt-nome-artistico" class="txtNomeArtistico" type="text" value="<?php echo($preco)?>" required autocomplete="off" onkeypress="return ValidarPromocao(event)">
                                    </div>
                                </div>

                                <!--AREA DE LANÇAMENTO DO FILME-->
                                <div class="form_data_nasc">
                                    <div class="form_texto_usuario">
                                        <label>Lançamento:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_lancamento" class="txtDataNasc" id="txt-data-nasc" type="type" onkeypress="return ValidarLetra(event)" onkeyup="return mascaraDataNascimento();" value="<?php echo($lancamento)?>" placeholder="00/00/0000" required autocomplete="off">
                                    </div>
                                </div>

                                <!--AREA DOS MINUTOS DO FILME-->
                                <div class="form_tempo">
                                    <div class="form_texto_usuario">
                                        <label>Minutos:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_tempo" class="txtTempo" id="txt-tempo" value="<?php echo($tempo)?>" type="text" onkeypress="return ValidarLetra(event)" required autocomplete="off" maxlength="20">
                                    </div>
                                </div>
                              
                                <!--AREA DE CLASSIFICAÇÃO DO FILME-->
                                <div class="form_classificacao">
                                    <div class="form_texto_usuario">
                                        <label>Classificacao:</label>
                                    </div>
                                    <div class="form_caixa_usuario">


                                        <!--SE FOR O MODO BUSCAR, ITÁ APARECER SELECIONADO NO SELECT O QUE ESTÁ REGSITRADO NO BANCO-->
                                        <select name="slt_classificacao" class="txtClassificacao" required>
                                            <?php

                                                if($modo == 'buscar'){


                                            ?>

                                            <option value="<?php echo($cod_classificacao)?>" selected><span><?php echo($classificacao)?></span></option>

                                            <?php

                                                }else{

                                            ?>
                                            <!--PRIMEIRO OPTION AO SER ATUALIZADO A TELA-->
                                            <option value="" selected><span>Selecione...</span></option>

                                            <?php
                                                    }

                                                    $sql = "SELECT * FROM tbl_classificacao";
                                                    $select = mysqli_query($conexao,$sql);

                                                    while($rsclassificacao = mysqli_fetch_array($select)){



                                                ?>

                                                <option value="<?php echo($rsclassificacao['cod_classificacao'])?>"><?php echo($rsclassificacao['classificacao'])?></option>

                                                <?php

                                                    }

                                                ?>


                                        </select>


                                    </div>
                                </div>

                                <!--AREA DA FOTO DO FILME-->
                                <div class="form_foto">
                                    <div class="form_texto_usuario">
                                        <label>Foto Filme:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                      
                                      <!--VERIFICA SE O BOTAO ESTIVER COMO SALVAR, SERÁ OBRIGATORIO A ESCOLA DA FOTO, SE O BOTAO FOR ESITAR, NÃO SERÁ OBRIGATÓRIO-->
                                      <?php
                                      
                                        if($botao == "Salvar"){
                                      ?>
                                      
                                          <input name="fle_foto" class="txtFoto" title="Selecione uma imagem" type="file" accept="image/*" required>
                                      <?php
                                      
                                        }else{
                                      
                                      ?>
                                          <input name="fle_foto" class="txtFoto" title="Selecione uma imagem" type="file" accept="image/*">
                                      
                                      <?php
                                        }
                                      
                                      
                                      ?>
                                      
                                      
                                    </div>
                                </div>
                              
                                <!--AREA DA FOTO DO SLIDER DO FILME-->
                                <div class="form_foto">
                                    <div class="form_texto_usuario">
                                        <label>Foto Slider:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="fle_foto_slider" class="txtFoto" title="Selecione uma imagem" type="file" accept="image/*">
                                    </div>
                                </div>

                                <!--AREA DE DESCRÇÃO DO FILME-->
                                <div class="form_biografia">
                                    <div class="form_texto_usuario">
                                        <label>Descrição:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <textarea name="txt_descricao" class="txtBiografiaAtor" type="text" required><?php echo($descricao)?></textarea>
                                    </div>
                                </div>

                                <!--AREA DO BOTÃO DO FORMULÁRIO-->
                                <div class="botao_form">
                                    <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao)?>">
                                </div>
                            </form>
                        </div>
                      
                        <!--AREA DA TABELA COM OS REGISTROS QUE FORAM CADASTRADOS-->
                        <div class="tabela center">
                          
                          <!--TITULOS DAS COLUNAS-->
                          <div class="linha_titulo">
                              <div class="titulo_form">
                                  <h4>Nome</h4>
                              </div>
                              <div class="titulo_data">
                                  <h4>Lançamento</h4>
                              </div>
                              <div class="titulo_destaque">
                                  <h4>Preço</h4>
                              </div>
                              <div class="titulo_opcoes">
                                  <h4>Opções</h4>
                              </div>

                          </div>
                        <?php
                        
                            //SELECT PARA TRAZER TODOS OS PRODUTOS
                            $sql = "SELECT * FROM tbl_produto_filme";
                            $select = mysqli_query($conexao,$sql);
                                       
                            while($rsfilme = mysqli_fetch_array($select)){
                            
                            //USANDO A FUNÇÃO PARA TRANSFORMAR A DATA DE LANÇAMENTO EM FORMATO BRASILEIRO
                            $lancamento = dataBrasileiro($rsfilme['lancamento']);
                            //USANDO A FUNÇÃO PARA TRANSFORMAR O PRÇEO EM FORMATO BRASILEIRO  
                            $preco = precoBrasileiro($rsfilme['preco']);
                            //PEGANDO O VALOR  ATIVO
                            $ativo_img = $rsfilme['ativo_filme'];
                              
                              //SE O REGSITRO ESTIVER COMO 0 CADASTRADO, ENTÃO DESATIVADO A FOTO SERÁ DE UM X
                              if($ativo_img == 0){
                                
                                $img = "img/cancel.png";
                                $legenda = "Desativado";
                                
                              //SE O REGSITRO ESTIVER COMO 1 CADASTRADO, ENTÃO ATIVADO A FOTO SERÁ DE UM OK 
                              }elseif($ativo_img == 1){
                                
                                $img = "img/ok.png";
                                $legenda = "Ativado";
                              }
                              
                        
                        ?>
                            
                            <!--AREA DA TABELA COM AS INFORMAÇÕES DE CADA REGISTRO-->
                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rsfilme['nome_filme'])?></span>
                                </div>
                                <div class="linha_data">
                                    <span><?php echo($lancamento)?></span>
                                </div>
                                <div class="linha_destaque">
                                    <span><?php echo("R$".$preco)?></span>
                                </div>
                              
                                <!--AREA DA TABELA COM OS BOTÕES-->
                                <div class="linha_botao">
                                    <!--BOTÃO DE EDITAR, COM O MODO BUSCAR E COM O ID DO REGISTRO-->
                                    <figure class="editar">
                                        <a href="filme.php?modo=buscar&id=<?php echo($rsfilme['cod_produto_filme'])?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                        </a>
                                    </figure>
                                  
                                    <!--BOTÃO DE EXCLUIR, COM O MODO EXCLUIR, COM O ID DO REGISTRO E COM A FOTO DO FILME E A FOTO DO SLIDE DO FILME-->
                                    <figure class="excluir">
                                        <a href="filme.php?modo=excluir&id=<?php echo($rsfilme['cod_produto_filme'])?>&foto=<?php echo($rsfilme['foto'])?>&imagem_slide=<?php echo($rsfilme['imagem_slide'])?>">

                                            <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                        </a>
                                    </figure>

                                    <!--BOTÃO DE DESATIVAR E ATIVAR O FILME-->
                                    <figure class="desativar">
                                        <a href="filme.php?ativo_filme=<?php echo($rsfilme['ativo_filme'])?>&id=<?php echo($rsfilme['cod_produto_filme'])?>">
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
        <script src="../js/expressaoRegular.js"></script>
    </body>
</html>