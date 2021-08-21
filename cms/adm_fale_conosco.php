<?php


    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }else if($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 4){
      
    

      /*FAZENDO A CONEXAO*/
      require_once('../db/conexao.php');
      $conexao = conexaoMysql();

      /*Se modo existir o registro será excluído*/
      if(isset($_GET['modo'])){

          $modo = $_GET['modo'];
          $codigo = $_GET['id'];

          if($modo == 'excluir'){

              $sql = "DELETE FROM tbl_fale_conosco WHERE codigo= ".$codigo." ";

              mysqli_query($conexao,$sql);

          }


      }


    


?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Adm. Fale Conosco</title>
        <link href="css/adm_fale_conosco.css" type="text/css" rel="stylesheet">
        <link href="css/modal_fale_conosco.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="../js/jquery.js"></script>
        <script src="../js/modal.js"></script>
      <meta charset="utf-8">
        
    </head>
    <body>
        
      <!--MODAL-->
        <div id="container">
            <div id="modal" class="center">
                
            </div>
        </div>
        
        <!--CONTEÚDO-->
        <div id="caixa_conteudo">
          
          
            <div id="conteudo" class="center">
              
                <!--AREA DO MENU DE ADM.-->  
                <?php include('cabecalho_menu_adm.php')?>
                
                <div class="itens">
                    <div class="titulo_contatos"><h2>Contatos</h2></div>
                    
                  <!--TABELA COM OS REGISTROS-->
                    <div class="tabela center">
                      
                        <!--AREA DO TITULO DA COLUNA-->
                        <div class="linha_titulo">
                            <div class="titulo_form">
                                <h4>Nome</h4>
                            </div>
                            <div class="titulo_form">
                                <h4>Email</h4>
                            </div>
                            <div class="titulo_celular">
                                <h4>Celular</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                        
                        <?php
                            
                            /*SELECT QUE TRAZ OS REGISTROS ENVIADOS ATRAVÉS DO FALE CONOSCO*/
                            $sql = "SELECT * FROM tbl_fale_conosco ORDER BY codigo DESC";
                                
                            $select=mysqli_query($conexao,$sql);
                            
                            while($rscontatos = mysqli_fetch_array($select)){
                                
                                //BERIFICANDO SE É FEMININO OU MASCULINO
                                if($rscontatos['sexo']=='F'){
                                    $sexo = 'Feminino';
                                }else{
                                    $sexo = 'Masculino';
                                }
                        
                        
                        ?>
                            <!--LINHAS COM OS REGISTROS-->
                            <div class="informacoes_form">
                                
                                <div class="linha_informacao">
                                    <span><?php echo($rscontatos['nome']);?></span>
                                </div>
                                <div class="linha_informacao">
                                    <span><?php echo($rscontatos['email']);?></span>
                                </div>
                                <div class="linha_celular">
                                    <span><?php echo($rscontatos['celular']);?></span>
                                </div>
                                <div class="linha_botao">
                                    
                                    <figure class="visualizar">
                                        <img src="img/ic_search.png" alt="Visualizar" title="Visualizar" class="botao visualizar" onclick="visualizarDados(<?php echo($rscontatos['codigo']);?>)">
                                    </figure>


                                    <a href="adm_fale_conosco.php?modo=excluir&id=<?php echo($rscontatos['codigo']);?>" onclick="return confirm('Deseja excluir esse contato?');">
                                        
                                        <figure class="excluir">
                                            
                                            <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao">
                                            
                                        </figure></a>

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

<?php

}else{
      
      header('location:index.php');
 }

?>