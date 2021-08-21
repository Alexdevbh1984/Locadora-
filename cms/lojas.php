<?php
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $nome_loja = null;
    $cidade = null;
    $endereco = null;
    $telefone = null;
    $mapa = null;
    $nome_foto = null;
    $botao = "Salvar";
    $cod_estado = 0;
    $cod_cidade = 0;
    $modo = null;

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUINDO REGISTROS
        if($modo == 'excluir'){
            $foto_excluir = $_GET['foto'];
            
            $sql = "DELETE FROM tbl_lojas WHERE cod_loja = ".addslashes($codigo)."";
            
            mysqli_query($conexao, $sql);
            
            $foto = "arquivos/".$foto_excluir;
            unlink($foto);
          
            echo("<script> window.location.href = 'lojas.php' </script>");
            
        //FAZENDO A BUSCA DE REGISTROS NO BANCO DE DADOS    
        }elseif($modo == 'buscar'){
            
            /*SELECT PARA BUSCAR AS LOJAS JUNTO COM A CIDADE E O ESTADO*/
            $sql = "SELECT tbl_lojas.*, tbl_cidades.*, tbl_estados.sigla_estado
                    FROM tbl_estados INNER JOIN tbl_cidades
                    ON tbl_estados.cod_estado = tbl_cidades.cod_estado
                    INNER JOIN tbl_lojas
                    ON tbl_cidades.cod_cidade = tbl_lojas.cod_cidade
                    WHERE cod_loja =".addslashes($codigo);
            
            $select = mysqli_query($conexao, $sql);
            
            if($rsloja = mysqli_fetch_array($select)){
                
                
                $nome_loja = $rsloja['nome_loja'];
                $endereco = $rsloja['endereco'];
                $telefone = $rsloja['telefone'];
                $nome_foto = $rsloja['foto']; 
                                  
                $cod_cidade = $rsloja['cod_cidade'];
                $cidade = $rsloja['cidade'];
                
                $cod_estado = $rsloja['cod_estado'];
                $estado = $rsloja['sigla_estado'];
                
                $_SESSION['nome_foto'] = $nome_foto;
               

            }
            
                $botao = 'Editar';
            
            
        }
        
         
    }
    

    if(isset($_POST['btnEnviar'])){
        
        $nome_loja = $_POST['txt_nome_loja'];
        $cidade = $_POST['slt_cidades'];
        $endereco = $_POST['txt_logradouro'];
        $telefone = $_POST['txt_telefone'];
        
        $diretorio = "arquivos/";//DIRETORIOP
        $arquivos_permitidos = array(".jpeg",".png",".jpg");//ARQUIVOS PERMITIDOS
        
        $arquivo = $_FILES['fle_foto']['name'];//NOME DO ARQUIVO
        
        $tamanho_arquivo = $_FILES['fle_foto']['size'];//TAMANHO DO ARQUIVO
        
        $tamanho_arquivo = round($tamanho_arquivo/1024);//TAMANHO ARREDONDADO
        
        $extensao_arquivo = strrchr($arquivo, ".");//PEGANDO SÓ A EXTENSÃO DO ARQUIVO
        
        $nome_arquivo = pathinfo($arquivo, PATHINFO_FILENAME);//NOME DO ARQUIVO
        
        $arquivo_criptografado = md5(uniqid(time()).$nome_arquivo);//CRIPTOGRAFANDO O NOME DO ARQUIVO
        
        $foto = $arquivo_criptografado . $extensao_arquivo;//CONCATENANDO A FOTO E O A EXTENSÃO
        
      /*SE ESTIVER VAZIO VAI ATUALIZAR E INSERIR COM A FOTO*/
        if(!empty($_FILES['fle_foto']['name'])){
          
          /*VERIFICANDO A EXTENSÃO PERMITIDA*/
            if(in_array($extensao_arquivo, $arquivos_permitidos)){

              /*VERIFICANDO O TAMANHO DO ARQUIVO*/
              if($tamanho_arquivo <= 5000){

                  $arquivo_temp = $_FILES['fle_foto']['tmp_name'];

                  /*MOVENDO O ARQUIVO PARA O SERVIDOR*/
                  if(move_uploaded_file($arquivo_temp, $diretorio.$foto)){

                      /*INSERIR VALORES*/
                      if($_POST['btnEnviar'] == "Salvar"){

                          $sql = "INSERT INTO tbl_lojas (nome_loja, endereco, telefone,foto,cod_cidade)
                                  VALUES ('".addslashes($nome_loja)."','".addslashes($endereco)."','".addslashes($telefone)."','".addslashes($foto)."', ".addslashes($cidade).")";


                        mysqli_query($conexao,$sql);

                      /*ATUALIZAR VALORES*/
                      }elseif($_POST['btnEnviar'] == "Editar"){


                          $sql = "UPDATE tbl_lojas SET nome_loja = '".addslashes($nome_loja)."', endereco = '".addslashes($endereco)."', telefone = '".addslashes($telefone)."', cod_cidade = ".addslashes($cidade).", foto='".addslashes($foto)."' WHERE cod_loja =".$_SESSION['idregistro'];

                        if(mysqli_query($conexao,$sql)){

                          unlink('arquivos/'.$_SESSION['nome_foto']);

                        }

                      }


                          header('location:lojas.php');



                  }else{

                      echo("<script> alert('Erro ao enviar o arquivo para o servidor!!!') </script>");

                  }


              }else{

                  echo("<script> alert('Tamanho de arquivo inválido!!)</script>");
              }

          }else{

              echo("<script> alert('Extensão de arquivo inválido!') </script>");
          }
          
        //SE O ARQUIVO DE FOTO ESTIVER VAZIO ELE VAI ATUALIZAR SEM A FOTO
        }elseif(empty($_FILES['fle_foto']['name'])){
          
              /*INSERIR VALORES*/
              if($_POST['btnEnviar'] == "Salvar"){

                echo("<script> alert('Escolha uma foto') </script>");


              /*ATUALIZAR VALORES SEM A FOTO*/
              }elseif($_POST['btnEnviar'] == "Editar"){


                  $sql = "UPDATE tbl_lojas SET nome_loja = '".addslashes($nome_loja)."', endereco = '".addslashes($endereco)."', telefone = '".addslashes($telefone)."', cod_cidade = ".addslashes($cidade)." WHERE cod_loja =".$_SESSION['idregistro'];
                
                 /* var_dump($sql);*/

                  mysqli_query($conexao,$sql);
                  header('location:lojas.php');


              }


        }

        
    }

    /*LÓGICA DO ATIVAR E DESATIVAR*/
    if(isset($_GET['ativo'])){
        
        $ativo = $_GET['ativo'];
        $codigo = $_GET['id'];
        
        //ATIVAR REGISTRO
        if($ativo == 0){
            
            $sql = "UPDATE tbl_lojas SET ativo = 1 WHERE cod_loja=".$codigo;
        
        //DESATIVAR REGISTRO
        }elseif($ativo == 1){
            
            $sql = "UPDATE tbl_lojas SET ativo = 0 WHERE cod_loja=".$codigo;
        }
        
        if(mysqli_query($conexao,$sql)){
            
            header('location:lojas.php');
            
        }else{
            
            echo("<script> alert('Erro no Cadastro!!') </script>");
        }
        
        
    }


?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/lojas.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
        <script src="../js/valida.js"></script>
        <script src="js/funcoes.js"></script>
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                
                <div class="itens center">
                    <!--<div id="menu_cadastro">
                    
                        <a href="cidades.php">
                            <div class="menu menu_opcoes">

                                  Cidades

                            </div>
                        </a>  
                    
                    </div>-->
                    
                  
                        <div class="titulo_lojas center"><h2>Cadastro de Lojas</h2></div>
                        <div class="cadastro_lojas center">
                          
                            <!--FORMULARIO-->
                            <form action="lojas.php" method="post" name="frm_lojas" enctype="multipart/form-data">
                                
                                <!--AREA DE CADASTRO DO NOME-->
                                <div class="form_nome">
                                    <div class="form_texto_usuario">
                                        <label>Nome da Loja:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_nome_loja" class="txtNomeLojas" type="text" value="<?php echo($nome_loja)?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
                                    </div>
                                </div>
                              
                                <!--AREA DE CADASTRO DO TELEFONE-->
                                <div class="form_cep">
                                    <div class="form_texto_usuario">
                                        <label>Telefone:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_telefone" class="txtCep" id="txt-telefone" value="<?php echo($telefone)?>" type="text" onkeypress="return ValidarLetra(event);" onkeyup="return mascaraTelefone()" pattern="^[(]\d{2}[)]\d{4}-\d{4}$" placeholder="(00)0000-0000" required autocomplete="off" maxlength="20">
                                    </div>
                                </div>
                                
                                    <!--AREA DE CADASTRO DO ESTADO-->
                                    <div class="form_estados">
                                        <div class="form_texto_usuario">
                                            <label>UF:</label>
                                        </div>
                                        <div class="form_caixa_usuario">

                                            <select name="slt_estados" id="sltEstados" class="txtEstados" change required >
                                                <?php

                                                    if($modo == 'buscar'){

                                                ?>

                                                <option value="<?php echo($cod_estado)?>" selected><span><?php echo($estado)?></span></option>


                                                <?php

                                                    }else{
                                                ?>

                                                <option value="" selected ><span>...</span></option>

                                                <?php
                                                    }

                                                    /*SELECT PARA PEGAR TODOS OS ESTADOS DIFERENTES DO CODIGO QUE ESTA NA URL*/
                                                    $sql = "SELECT * FROM tbl_estados WHERE cod_estado <> ".$cod_estado." ORDER BY sigla_estado";
                                                    $select = mysqli_query($conexao, $sql);

                                                    while($rsestados = mysqli_fetch_array($select)){



                                                ?>


                                                <option value="<?php echo($rsestados['cod_estado'])?>"><?php echo($rsestados['sigla_estado'])?></option>
                                                <?php

                                                    }

                                                ?>


                                            </select>


                                        </div>
                                    </div>
                                <!--AREA DE CADASTRO DA CIDADE-->
                                <div class="form_cidades">
                                    <div class="form_texto_usuario">
                                        <label>Cidade:</label>
                                    </div>
                                    <div class="form_caixa_usuario">


                                        <select name="slt_cidades" id="sltCidades" class="txtCidades" required>

                                            <?php

                                                if($modo == 'buscar'){

                                            ?>


                                            <option value="<?php echo($cod_cidade)?>" selected><span><?php echo($cidade)?></span></option>
                                            <?php
                                              /*SELECT PARA PEGAR TODOS OS CIDADES DIFERENTES DO CODIGO QUE ESTA NA URL E PEGAR AS CIDADES DO ESTADO QUE FOI BUSCADO*/
                                                $sql = "SELECT * FROM tbl_cidades WHERE cod_cidade <> ".$cod_cidade." AND cod_estado = ".$cod_estado." ORDER BY cidade";
                                                $select = mysqli_query($conexao, $sql);

                                                 while($rscidades = mysqli_fetch_array($select)){
                                                  
                                                  
                                            ?>
                                              <option value="<?php echo($rscidades['cod_cidade'])?>"><?php echo($rscidades['cidade'])?></option>


                                            <?php
                                                  }
                                              
                                              }else{


                                            ?>
                                             <option value="" selected><span>Selecione...</span></option>
                                          
                                            <?php
                                          
                                                }
                                            ?>

                                        </select>


                                    </div>
                                </div>

                                <!--AREA DE CADASTRO DO ENDERECO-->
                                <div class="form_logradouro">
                                    <div class="form_texto_usuario">
                                        <label>Logradouro:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_logradouro" class="txtLogradouro" type="text" value="<?php echo($endereco)?>" required placeholder="ex.: Rua Brasileirinha, 12" maxlength="255" autocomplete="off">
                                    </div>
                                </div>
                                
                                <!--AREA DE CADASTRO DO MAPA-->
                                <!--<div class="form_mapa">
                                    <div class="form_texto_usuario">
                                        <label class="label_mapa">Link do Mapa:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_mapa" class="txtMapa" type="url" value="" onkeypress="return ValidarNumero(event);" required maxlength="255" autocomplete="off">
                                    </div>
                                </div>-->
                                
                                <!--AREA DE CADASTRO DA FOTO-->
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

                                <?php
                                    /*AO TENTAR EDITAR, A FOTO APARECERÁ NESSA DIV, LGO FOI FEITA ESSA LÓGICA*/

                                    if(isset($nome_foto)){

                                ?>

                                    <div class="caixa_foto">
                                        <img src="arquivos/<?php echo($nome_foto);?>"  alt="<?php echo($nome_loja)?>" title="<?php echo($nome_loja)?>" class="foto">
                                    </div>
                                <?php

                                    }
                                ?>


                                <div class="botao_form">
                                    <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao);?>">
                                </div>
                            </form>
                        </div>
                        
                        <!--TABELA DOS REGISTROS-->
                        <div class="tabela center">
                            <div class="linha_titulo">
                                <div class="titulo_form">
                                    <h4>Nome</h4>
                                </div>
                                <div class="titulo_email">
                                    <h4>Endereço</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>

                                <?php

                                    /*SELECT PARA PEGAR TODAS AS LOJAS, AS CIDADES E OS ESTADOS*/
                                    $sql = "SELECT tbl_lojas.*, concat_ws(' - ', tbl_lojas.endereco, tbl_cidades.cidade, tbl_estados.sigla_estado) AS enderecoCompleto 
                                    FROM tbl_estados INNER JOIN tbl_cidades
                                    ON tbl_estados.cod_estado = tbl_cidades.cod_estado 
                                    INNER JOIN tbl_lojas
                                    ON tbl_cidades.cod_cidade = tbl_lojas.cod_cidade";


                                    $select = mysqli_query($conexao, $sql);



                                    while($rslojas = mysqli_fetch_array($select)){


                                        if($rslojas['ativo'] == 1){

                                            $img = "img/ok.png";
                                            $legenda = "Ativado";

                                        }elseif($rslojas['ativo'] == 0){

                                            $img = "img/cancel.png";
                                            $legenda = "Desativado";
                                        }

                                ?>
                                <!--LINHAS COM OS REGISTROS-->
                                <div class="informacoes_form">
                                    <div class="linha_informacao">
                                        <span><?php echo($rslojas['nome_loja'])?></span>
                                    </div>
                                    <div class="linha_email">
                                        <span><?php echo($rslojas['enderecoCompleto'])?></span>
                                    </div>
                                    <div class="linha_botao">

                                        <figure class="editar">
                                            <a href="lojas.php?modo=buscar&id=<?php echo($rslojas['cod_loja'])?>">
                                                <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                            </a>
                                        </figure>

                                        <figure class="excluir">
                                            <a href="lojas.php?modo=excluir&id=<?php echo($rslojas['cod_loja']);?>&foto=<?php echo($rslojas['foto'])?>">

                                                <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                            </a>
                                        </figure>

                                        <figure class="desativar">
                                            <a href="lojas.php?ativo=<?php echo($rslojas['ativo'])?>&id=<?php echo($rslojas['cod_loja'])?>">
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
        <script src="../js/expressaoRegular.js"></script>

    </body>
</html>