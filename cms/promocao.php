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

    $botao = "Salvar";

    $porcentagem = null;
    $cod_produto_filme = 0;
    $img = "img/cancel.png";
    $legenda = "Desativado";

    $modo = null;


    if(isset($_POST['btnEnviar'])){
        
        $porcentagem = precoBancoDados($_POST['txt_promocao']);
        $filme = $_POST['slt_filmes'];
        
      //VERIFICANDO SE O PRODUTO QU ESTÁ SENDO CADSTRADO JÁ EXISTE
        $sql = "SELECT * FROM tbl_promocao WHERE cod_produto_filme =".$filme;
        $select = mysqli_query($conexao, $sql);
        
        if(mysqli_num_rows($select) == 0){
            //INSERINDO REGISTROS NO BANCO DE DADOS
            if($_POST['btnEnviar'] == "Salvar"){



             $sql = "INSERT INTO tbl_promocao (porcentagem, cod_produto_filme) VALUES
                (".$porcentagem.",".$filme.")";


            //ATUALIZANDO REGISTROS NO BANCO DE DAODS    
            }elseif($_POST['btnEnviar'] == "Editar"){



            $sql = "UPDATE tbl_promocao SET porcentagem = ".addslashes($porcentagem).", cod_produto_filme = ".addslashes($filme)." 
              WHERE cod_promocao =".$_SESSION['idregistro'];



            }


            if(mysqli_query($conexao,$sql)){
                header('location:promocao.php');
            }else{
                echo("<script> alert('Erro no cadastro!') </script>");
            }
        }else{
          echo("<script>alert('Esse produto já possui cadastro com promoção')</script>");
        }  
        
        
    }



    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //ECLUINDO REGISTROS NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_promocao WHERE cod_promocao =".addslashes($codigo);
            
            mysqli_query($conexao,$sql);
        
          
        //FAZENDO BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            //SELECT NA TABELA PROMKCAO E FILME
            $sql = "SELECT tbl_promocao.*, tbl_produto_filme.nome_filme 
                    FROM tbl_promocao INNER JOIN tbl_produto_filme
                    ON tbl_promocao.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                    WHERE cod_promocao = ".addslashes($codigo);
            
            $select = mysqli_query($conexao,$sql);
            
            
            if($rspromo = mysqli_fetch_array($select)){
                
                $porcentagem = precoBrasileiro($rspromo['porcentagem']);
                
                $produto_filme = $rspromo['nome_filme'];
                $cod_produto_filme = $rspromo['cod_produto_filme'];

                $botao = "Editar";
            }
            
            
        }
        
        
        
        
    }

    //LÓGICA ATIVAR/DESATIVAR
    if(isset($_GET['ativo'])){
      
      $codigo = $_GET['id'];
      $produto = $_GET['produto'];
      $ativo = $_GET['ativo'];
      
      //ATIVANDO REGISTRO
      if($ativo == 0){
        
        //select para saber a quantidade de registros que o filme já foi cadastrado com promoção
        $sql = "SELECT COUNT(*) AS quantidade FROM tbl_promocao WHERE cod_produto_filme = ".$produto." AND ativo = 1 ";
          
        $select = mysqli_query($conexao, $sql);

        if($rsativafilme = mysqli_fetch_array($select)){

            //se a quantidade fo igual a zero, pode ativar
          if($rsativafilme['quantidade'] == 0){

            $sql = "UPDATE tbl_promocao SET ativo = 1 WHERE cod_promocao=".$codigo;
            
            if(mysqli_query($conexao,$sql)){
              header('location:promocao.php');
            }else{
              echo("<script> alert('Erro no cadastro!') </script>");
            }

          
          }else{
            
            //se o retorno de linhas for diferente de zero ele vai ativar o registro e desativar os outrosa registros
            $sql = "UPDATE tbl_promocao SET ativo = 1 WHERE cod_promocao=".$codigo;
            mysqli_query($conexao, $sql);
            
            $sql = "UPDATE tbl_promocao SET ativo = 0 WHERE cod_produto_filme = ".$produto." AND cod_promocao <> ".$codigo;
            
            if(mysqli_query($conexao,$sql)){
              header('location:promocao.php');
            }else{
              echo("<script> alert('Erro no cadastro!') </script>");
            }

          }

        }
        
        
        
      //DESATIVANDO REGISTRO 
      }elseif($ativo == 1){
        
          $sql = "UPDATE tbl_promocao SET ativo = 0 WHERE cod_promocao=".$codigo;
        
        
       if(mysqli_query($conexao,$sql)){
            header('location:promocao.php');
        }else{
            echo("<script> alert('Erro no cadastro!') </script>");
        }
        
        
      }
      
      
      
      
      
    }
  


?>

<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/promocao.css" type="text/css" rel="stylesheet">
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
                    <div class="titulo_promocao"><h2>Cadastro de Promoção</h2></div>
                    <div class="cadastro_promocao center">
                      
                        <!--FORMULARIO-->
                        <form action="promocao.php" method="post" name="frm_promocao">
                            
                            <!--AREA DE CADASTRO DO DESCONTO-->
                            <div class="form_promocao">
                                <div class="form_texto_promocao">
                                    <label>Desconto:</label>
                                </div>
                                <div class="form_caixa_promocao">
                                    <input name="txt_promocao" id="txt-preco" class="txtPromocao" type="text" value="<?php echo($porcentagem)?>" onkeypress="return ValidarPromocao(event);" onkeyup="return mascaraPreco()" required placeholder="ex.: 10,00" maxlength="6" autocomplete="off">
                                </div>
                              
                            </div>
                            <span class="porcentagem">%</span>
                          
                            <!--AREA DE CADASTRO DOS FILMES-->
                            <div class="form_filmes">
                                <div class="form_texto_promocao">
                                    <label>Filmes:</label>
                                </div>
                                <div class="form_caixa_promocao">
                                    
                                    <select name="slt_filmes" class="txtFilmes" required>
                                        <?php
                                        
                                            if($modo == 'buscar'){
                                                
                                            
                                        ?>
                                      
                                      
                                        <option value="<?php echo($cod_produto_filme)?>"><?php echo($produto_filme)?></option>
                                        <?php
                                        
                                            }else{
                                        
                                        ?>
                                        
                                        <option value="">Selecione...</option>
                                        
                                      
                                      <?php
                                            }
                                                
                                        /*SELECT PARA PEGAR TODOS OS FILMES DIFERENTES DO CODIGO QUE ESTA NA URL*/
                                        $sql = "SELECT * FROM tbl_produto_filme WHERE cod_produto_filme <>".$cod_produto_filme." ORDER BY nome_filme";
              
                                         
                
                                        $select = mysqli_query($conexao,$sql);
              
                                        while($rsfilmes = mysqli_fetch_array($select)){
                                          
                                        
                                      
                                      
                                      ?>
                                      
                                        <option value="<?php echo($rsfilmes['cod_produto_filme'])?>"><span><?php echo($rsfilmes['nome_filme'])?></span></option>
                                      
                                      <?php
                                      
                                        }
                                      
                                      ?>
                                      
                                    </select>
                                </div>
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
                                <h4>%</h4>
                            </div>
                            <div class="titulo_filme">
                                <h4>Filme</h4>
                            </div>
                            <div class="titulo_preco_inicio">
                                <h4>Inicial</h4>
                            </div>
                            <div class="titulo_preco_fim">
                                <h4>Final</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                        <?php
                          
                            /*SELECT COM O CÁLCULO DA PORCENTAGEM, TRAZENDO O VALOR DO FILME E O VALOR FINAL DO FILME CALCULADO*/
                        
                          $sql = "SELECT tbl_produto_filme.*, tbl_promocao.*,
                                  (SELECT round(tbl_produto_filme.preco-(tbl_produto_filme.preco*tbl_promocao.porcentagem/100),2)) AS preco_final
                                  FROM tbl_produto_filme INNER JOIN tbl_promocao
                                  ON tbl_produto_filme.cod_produto_filme=tbl_promocao.cod_produto_filme";
              
                          $select = mysqli_query($conexao,$sql);
              
                          while($rspromocao = mysqli_fetch_array($select)){
                            
                            $preco = str_replace(".",",", $rspromocao['preco']);
                            $precoFinal = str_replace(".",",", $rspromocao['preco_final']);
                            $porcentagem = str_replace(".",",", $rspromocao['porcentagem']);
                            
                            if($rspromocao['ativo'] == 1){
                              
                              $img = "img/ok.png";
                              $legenda = "Ativado";
                              
                              
                            }elseif($rspromocao['ativo'] == 0){
                              
                              $img = "img/cancel.png";
                              $legenda = "Desativado";
                              
                            }
                            
                            
                        ?>
                      
                      
                        <!--LINHAS COM OS REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($porcentagem)?></span>
                            </div>
                            <div class="linha_filme">
                                <span><?php echo($rspromocao['nome_filme'])?></span>
                            </div>
                            <div class="linha_preco_inicio">
                                <span><?php echo("R$".$preco)?></span>
                            </div>
                            <div class="linha_preco_fim">
                                <span><?php echo("R$".$precoFinal)?></span>
                            </div>
                            <div class="linha_botao">

                                <figure class="editar">
                                    
                                    <a href="promocao.php?modo=buscar&id=<?php echo($rspromocao['cod_promocao'])?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao">
                                    </a>    
                                         
                                </figure>

                                <figure class="excluir">
                                    
                                    <a href="promocao.php?modo=excluir&id=<?php echo($rspromocao['cod_promocao'])?>">
                                        <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">
                                    
                                    </a>
                                </figure>

                                <figure class="desativar">
                                    
                                    <a href="promocao.php?ativo=<?php echo($rspromocao['ativo'])?>&id=<?php echo($rspromocao['cod_promocao'])?>&produto=<?php echo($rspromocao['cod_produto_filme'])?>">
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