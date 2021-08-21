<?php

  /*INICIO DE VARIAVEL DE SESSAO*/
  session_start();

  /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

  /*CHAMANDO A PÁGINA DE FUNÇÕES*/
  require_once('../funcoes.php');

  /*CONEXÃO*/
  require_once('../db/conexao.php');
  $conexao = conexaoMysql();
  
  //VÁRIAVEIS DECLARADAS
  $nome_artistico = null;
  $nome_completo = null;
  $nacionalidade = null;
  $cidade = null;
  $biografia = null;
  $dt_nasc = null;
  $nome_foto = null;
  $cod_estado = 0;
  $cod_estado_civil = 0;
  $img = "img/cancel.png";
  $legenda = "Desativado";
  $modo = null;

  $botao = "Salvar";

  //AO APERTAR O BOTÃO ENVIOAR IRÁ EXECUTAR ESSE PROCESSO
  if(isset($_POST['btnEnviar'])){

      $nome_artistico = $_POST['txt_nome_artistico'];
      $nome_completo = $_POST['txt_nome_completo'];
      $estado_civil = $_POST['slt_estado_civil'];
      $biografia = $_POST['txt_biografia_ator'];
      $dt_nasc = dataBancoDados($_POST['txt_data_nascimento']);

      
      $arquivos_permitidos = array(".jpg", ".jpeg", ".png"); //TIPOR DE EXTENSÕES PERMITIDAS
      $diretorio = "arquivos/"; //DIRETORIO PARA O SERVIDOR

      $arquivo = $_FILES['fle_foto']['name'];

      $tamanho_arquivo = $_FILES['fle_foto']['size']; // PEGANDO O TAMANHO DO ARQUIVO

      $tamanho_arquivo = round($tamanho_arquivo/1024); //TRANSFORMANDO O TAMANHO DO ARQUIVO

      $extensao_arquivo = strrchr($arquivo, "."); //SEPARANDO A EXTENSÃO DO ARQUIVO

      $nome_arquivo = pathinfo($arquivo, PATHINFO_FILENAME); //SEPARANDO O NOME DO ARUQIVO

      $arquivo_criptografado = md5(uniqid(time()).$nome_arquivo); //CRIPTOGRAFANDO O NOME DO ARQUIVO

      $foto = $arquivo_criptografado . $extensao_arquivo; //CONCATENANDO O NOME DO ARQUIVO COM A EXTENSÃO

      if(!empty($_FILES['fle_foto']['name'])){
        
          if(in_array($extensao_arquivo, $arquivos_permitidos)){

            if($tamanho_arquivo <= 5000){

                $arquivo_temp = $_FILES['fle_foto']['tmp_name'];

                if(move_uploaded_file($arquivo_temp, $diretorio.$foto)){
                  
                  
                    // INSERINDO O REGISYTRO NO BANCO
                    if($_POST['btnEnviar'] == "Salvar"){


                        $sql = "INSERT INTO tbl_ator (nome_artistico, nome_completo, data_nascimento,biografia, cod_estado_civil, foto) VALUES (
                                '".addslashes($nome_artistico)."', '".addslashes($nome_completo)."', '".addslashes($dt_nasc)."','".addslashes($biografia)."', '".addslashes($estado_civil)."', '".addslashes($foto)."')";


                        var_dump($sql);

                        mysqli_query($conexao,$sql);

                    
                    //ATUALIZANDO O REGISTRO NO BANCO
                    }elseif($_POST['btnEnviar'] == "Editar"){

                        $sql = "UPDATE tbl_ator SET nome_artistico = '".addslashes($nome_artistico)."',
                                nome_completo = '".addslashes($nome_completo)."', data_nascimento = '".addslashes($dt_nasc)."',
                                biografia = '".addslashes($biografia)."', cod_estado_civil = '".addslashes($estado_civil)."',
                                foto = '".addslashes($foto)."' WHERE cod_ator =".$_SESSION['idregistro'];

                        if(mysqli_query($conexao,$sql)){

                          unlink("arquivos/".$_SESSION['nome_foto']);
                        }
                    }


                    header('location:ator.php');


                }else{

                    echo("<script> alert('Erro ao enviar o arquivo para o servidor!!!') </script>");

                }

            }else{

                echo("<script> alert('Tamanho de arquivo inválido!!)</script>");

            }


        }else{

            echo("<script> alert('Extensão de arquivo inválido!') </script>");
        }
       
      /*SE O INPUT FILE ESTIVER VAZIO VAI ATUALIZAR SEM A FOTO*/
      }elseif(empty($_FILES['fle_foto']['name'])){
        
        
        /*INSERIR VALORES*/
          if($_POST['btnEnviar'] == "Salvar"){

            echo("<script> alert('Escolha uma foto') </script>");

        //ATUALIZANDO O REGISTRO SEM A FOTO
          }else{
            
               $sql = "UPDATE tbl_ator SET nome_artistico = '".addslashes($nome_artistico)."',
                      nome_completo = '".addslashes($nome_completo)."', data_nascimento = '".addslashes($dt_nasc)."',
                      biografia = '".addslashes($biografia)."', cod_estado_civil = '".addslashes($estado_civil)."' WHERE cod_ator =".$_SESSION['idregistro'];

              mysqli_query($conexao,$sql);
              header('location:ator.php');
            
            }
      }


  }

  if(isset($_GET['modo'])){
      $modo = $_GET['modo'];
      $codigo = $_GET['id'];

      $_SESSION['idregistro'] = $codigo;

      //EXCLUINDO REGISTRO DO BANCO
      if($modo == 'excluir'){

          $foto_excluir = $_GET['foto'];
        
 
              //FOI USADO UMA TRIGEER PARA REALIZAR A EXCLUSÃO DO FILME
              $sql = "DELETE FROM tbl_ator WHERE cod_ator = ".addslashes($codigo)." ";
        
              
              mysqli_query($conexao, $sql);
              $foto = "arquivos/".$foto_excluir;
              unlink($foto);

              /*header('location:ator.php');*/
          echo("<script> window.location.href = 'ator.php' </script>");


      // FAZENDO UMA BUSCA DE UMA REGISTRO NO BANCO
      }elseif($modo == 'buscar'){
          
          /*SELECT DE BUSCA DO ATORES JUNTO COM O SEU ESTADO CIVIL*/
          $sql = "SELECT tbl_ator.*, tbl_estado_civil.estado_civil
                  FROM tbl_ator INNER JOIN tbl_estado_civil
                  ON tbl_ator.cod_estado_civil = tbl_estado_civil.cod_estado_civil
                  WHERE tbl_ator.cod_ator =".addslashes($codigo);



          $select = mysqli_query($conexao,$sql);
        
          /*TRASNFORMANDO EM UM ARRAY O REGISTRO RETORNADO*/
          if($rsator = mysqli_fetch_array($select)){

              $nome_completo = $rsator['nome_completo'];
              $nome_artistico = $rsator['nome_artistico'];
              $biografia = $rsator['biografia'];
              $dt_nasc = dataBrasileiro($rsator['data_nascimento']);

              $cod_estado_civil = $rsator['cod_estado_civil'];
              $estado_civil = $rsator['estado_civil'];

              $nome_foto = $rsator['foto'];
            
              $_SESSION['nome_foto'] = $nome_foto;
            
            $botao = "Editar";


          }


      }

  }

  
  if(isset($_GET['ativo_ator_mes'])){

    $id = $_GET['id'];
    $ativo = $_GET['ativo_ator_mes'];

    
    /*ATIVANDO O ATOR PARA DESTAQUE*/
    if($ativo == 0){
      
      /*ATIVANDO REGISTRO*/
      $sql = "UPDATE tbl_ator SET ativo_ator_mes = 1 WHERE cod_ator =".$id;
      
      mysqli_query($conexao,$sql);
      
        
      /*DESATIVANDO OS REGISTROS DIFERENTES DO QUE ESTÁ ATIVADO*/
      $sql = "UPDATE tbl_ator SET ativo_ator_mes = 0 WHERE cod_ator <> ".$id;

      if(mysqli_query($conexao,$sql)){

        header('location:ator.php');

      }else{

        echo("Erro");

      }
        
        
      
      
    }
    
  }


?>


<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/ator.css" type="text/css" rel="stylesheet">
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
                
                <!--MENU LATERAL PARA CADASTRO DE ELEMENTOS RELACIONADOS AO ATOR-->
                <div class="itens">
                    <div id="menu_cadastro">
                        <a href="filme.php">
                            <div class="menu menu_filme">

                                 Filme

                            </div>
                        </a> 
                    
                        <a href="ator.php">
                            <div class="menu menu_opcoes">

                                 Ator

                            </div>
                        </a> 
                        <!--<a href="estado_civil.php">

                            <div class="menu menu_opcoes">
                                Estado Civil
                            </div>
                        </a>-->
                        <a href="profissao.php">
                            <div class="menu menu_opcoes">

                                  Profissão

                            </div>
                        </a>  


                        <div class="menu menu_opcoes">

                             Relação
                            <div class="sub_menu">
                              <a href="ator_profissao.php">
                                  <div class="sub_itens">
                                      Profissão e Ator
                                  </div>
                                </a>
                                <a href="filme_ator.php">
                                  <div class="sub_itens">
                                      Ator e Filme
                                  </div>
                              </a>
                                
                            </div>

                        </div>
                        
                    </div>
                    <div class="cadastro">
                        <div class="titulo_ator"><h2>Cadastro de Atores</h2></div>
                        <div class="cadastro_ator center">
                          
                            <!--FORMULÁRIO-->
                            <form action="ator.php" method="post" name="frm_ator_mes" enctype="multipart/form-data">

                                <?php
                                    if(isset($nome_foto)){



                                ?>
                                    <div class="caixa_foto center">
                                        <img src="arquivos/<?php echo($rsator['foto'])?>" class="foto">
                                    </div>
                                <?php

                                    }

                                ?>

                                <!--NOME COMPLETO DO ATOR-->
                                <div class="form_nome_completo">
                                    <div class="form_texto_usuario">
                                        <label>Nome Completo:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_nome_completo" class="txtNomeCompleto" type="text" value="<?php echo($nome_completo)?>" onkeypress="return ValidarNumero(event);" required maxlength="200" autocomplete="off">
                                    </div>
                                </div>

                                <!--NOME ARTISTICO DO ATOR-->
                                <div class="form_nome_artistico">
                                    <div class="form_texto_usuario">
                                        <label>Nome Artistico:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_nome_artistico" class="txtNomeArtistico" type="text" value="<?php echo($nome_artistico)?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
                                    </div>
                                </div>

                                <!--DATA DE NASCIMENTO DO ATOR-->
                                <div class="form_data_nasc">
                                    <div class="form_texto_usuario">
                                        <label>Data Nascimento:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_data_nascimento" class="txtDataNasc" id="txt-data-nasc" type="type" onkeypress="return ValidarLetra(event)" onkeyup="return mascaraDataNascimento();" value="<?php echo($dt_nasc)?>" pattern="^\d{2}[/]\d{2}[/]\d{4}$"  placeholder="00/00/0000" required autocomplete="off">
                                    </div>
                                </div>

                                
                                    <!--ESTADO CIVIL DO ATOR-->
                                    <div class="form_estado_civil">
                                        <div class="form_texto_usuario">
                                            <label>Estado Civil:</label>
                                        </div>
                                        <div class="form_caixa_usuario">


                                            <!--LÓGICA DO SELECT PARA QUE TRAGA CASO SEJA UMA BUSCA OU NÃO NO BANCO-->
                                            <select name="slt_estado_civil" class="txtEstadoCivil" required>
                                                <?php

                                                    if($modo == 'buscar'){


                                                ?>

                                                <option value="<?php echo($cod_estado_civil)?>" selected><span><?php echo($estado_civil)?></span></option>

                                                <?php

                                                    }else{

                                                ?>

                                                <option value="" selected><span>Selecione...</span></option>

                                                <?php
                                                    }

                                                        /*SELECT TRAZENDO OS REGISTROS DOS ESTADOS CIVIS*/
                                                        $sql = "SELECT * FROM tbl_estado_civil";
                                                        $select = mysqli_query($conexao,$sql);

                                                        while($rsestadocivil = mysqli_fetch_array($select)){



                                                    ?>

                                                    <option value="<?php echo($rsestadocivil['cod_estado_civil'])?>"><?php echo($rsestadocivil['estado_civil'])?></option>

                                                    <?php

                                                        }

                                                    ?>


                                            </select>


                                        </div>
                                    </div>
                                <!--AREA DE ESCOLHA DA FOTO DO ATOR-->
                                <div class="form_foto">
                                    <div class="form_texto_usuario">
                                        <label>Foto:</label>
                                    </div>
                                  
                                    <div class="form_caixa_usuario">
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

                                <!--BIOGRAFIA DO ATOR-->
                                <div class="form_biografia">
                                    <div class="form_texto_usuario">
                                        <label>Biografia:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <textarea name="txt_biografia_ator" class="txtBiografiaAtor" type="text" required><?php echo($biografia)?></textarea>
                                    </div>
                                </div>

                                <div class="botao_form">
                                    <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao)?>">
                                </div>
                            </form>
                        </div>
                        
                        <!--TABELA COM OS REGISTROS-->
                        <div class="tabela center">
                          
                            <!--TITULOS DAS COLUNAS-->
                            <div class="linha_titulo">
                                <div class="titulo_form">
                                    <h4>Nome</h4>
                                </div>
                                <div class="titulo_data">
                                    <h4>Nascimento</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>
                                <div class="titulo_destaque">
                                    <h4>Destaque</h4>
                                </div>

                            </div>
                            <?php

                                /*SELECT COM TODOS OS REGISTROS DOS ATORES*/
                                $sql = "SELECT * FROM tbl_ator ORDER BY nome_artistico desc";
                                $select = mysqli_query($conexao,$sql);

                                while($rsatores = mysqli_fetch_array($select)){

                                $data_nascimento = explode("-", $rsatores['data_nascimento']);
                                $dt_nasc = $data_nascimento[2]."/".$data_nascimento[1]."/".$data_nascimento[0];

                                  if($rsatores['ativo_ator_mes'] == 1){

                                    $img = "img/ok.png";
                                    $legenda = "Ativado";

                                  }elseif($rsatores['ativo_ator_mes'] == 0){

                                    $img = "img/cancel.png";
                                    $legenda = "Desativado";

                                  }


                            ?>

                                <!--LINHAS DOS REGISTROS-->
                                <div class="informacoes_form">
                                    <div class="linha_informacao">
                                        <span><?php echo($rsatores['nome_artistico'])?></span>
                                    </div>
                                    <div class="linha_data">
                                        <span><?php echo($dt_nasc)?></span>
                                    </div>
                                    <div class="linha_botao">

                                        <figure class="editar">
                                            <a href="ator.php?modo=buscar&id=<?php echo($rsatores['cod_ator'])?>">
                                                <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                            </a>
                                        </figure>

                                        <figure class="excluir">
                                            <a href="ator.php?modo=excluir&id=<?php echo($rsatores['cod_ator'])?>&foto=<?php echo($rsatores['foto'])?>">

                                                <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                            </a>
                                        </figure>

                                    </div>
                                    <div class="linha_destaque">
                                        <figure class="destaque center">
                                            <a href="ator.php?ativo_ator_mes=<?php echo($rsatores['ativo_ator_mes'])?>&id=<?php echo($rsatores['cod_ator'])?>">
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