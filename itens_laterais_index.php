<?php

    require_once('db/conexao.php');
    $conexao = conexaoMysql();

    require_once('funcoes.php');

?>


<!--ITENS DO LADO ESQUERDO-->
<div id="caixa_itens">
    <form action="index.php" name="frm_pesquia" method="post" id="caixa_pesquisa">

      <input name="txt_pesquisa" type="text" class="pesquisa" value="" placeholder="Pesquisar">
      <input type="submit" name="btnPesquisa" class="pesquisa_botao" value="">

    </form>
    <div id="item">

        <?php

          $sql = "SELECT tbl_categoria.*, tbl_genero.*, GROUP_CONCAT(DISTINCT tbl_genero.genero SEPARATOR ', ') AS genero_categoria
          FROM tbl_categoria INNER JOIN tbl_subcategoria
          ON tbl_categoria.cod_categoria = tbl_subcategoria.cod_categoria
          INNER JOIN tbl_genero
          ON tbl_subcategoria.cod_genero = tbl_genero.cod_genero
          INNER JOIN tbl_produto_filme
          ON tbl_subcategoria.cod_produto_filme = tbl_produto_filme.cod_produto_filme
          WHERE tbl_categoria.ativo = 1 GROUP BY tbl_categoria.categoria";

          $select = mysqli_query($conexao, $sql);

          while($rscategoria = mysqli_fetch_array($select)){
            
            $cod_categoria = $rscategoria['cod_categoria'];
            $categoria = $rscategoria['categoria'];

        ?>


        <div class="itens_db">
          <?php echo("<a href='index.php?categoria&cod_categoria=$cod_categoria'>$categoria </a>")?>
          
            <div class="sub_menu_db">

                  <?php 

                    $sql2 = " SELECT tbl_subcategoria.*, tbl_genero.*
                              FROM tbl_subcategoria INNER JOIN tbl_genero
                              ON tbl_subcategoria.cod_genero = tbl_genero.cod_genero
                              WHERE tbl_subcategoria.cod_categoria =".$cod_categoria." AND tbl_genero.ativo = 1 GROUP BY tbl_genero.cod_genero";
            
                    $select2 = mysqli_query($conexao, $sql2);
            
                    while($rssub = mysqli_fetch_array($select2)){
                      
                      $genero = $rssub['genero'];
                      $cod_genero = $rssub['cod_genero'];
                      $categoriaSub = $rssub['cod_categoria'];

                  ?>
              
                    <a href="index.php?subcategoria&cod_subcategoria=<?php echo($cod_genero)?>&categoriaSub=<?php echo($categoriaSub)?>">
                      <div class="sub_itens center"> 
                        <?php echo($genero);?>
                      </div>
                    </a>
              
                <?php

                
                    }

              ?>

            </div>
          
        </div>
        <?php

            }
        ?>

    </div>
</div>