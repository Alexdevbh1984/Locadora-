<div id="jssor_1">
    
    <!--SLIDER-->
    <div data-u="slides" class="slider" >
        <?php
          
          require_once('db/conexao.php');
          $conexao = conexaoMysql();
      
      
          $sql = "SELECT tbl_produto_filme.*, tbl_categoria.* 
                  FROM tbl_produto_filme INNER JOIN tbl_subcategoria
                  ON tbl_subcategoria.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                  INNER JOIN tbl_categoria
                  ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria
                  WHERE ativo_filme = 1 AND tbl_categoria.ativo = 1 AND imagem_slide IS NOT NULL GROUP BY tbl_produto_filme.cod_produto_filme ORDER BY RAND() LIMIT 6";
      
          $select = mysqli_query($conexao, $sql);
          
          while($rsslider = mysqli_fetch_array($select)){
            
            
          $imagem_slider = $rsslider['imagem_slide'];
          $nome_filme = $rsslider['nome_filme'];
      
      
        ?>
        
        <!--IMAGENS PARA SER COLOCADAS NO SLIDER-->
        <div>
            <img data-u="image" src="cms/arquivos/<?php echo($imagem_slider)?>" alt="<?php echo($nome_filme)?>" title="<?php echo($nome_filme)?>"/>
            <div data-ts="flat" class="img_slider" data-p="1360" >   
            </div>
        </div>
        <?php
      
          }
      
        ?> 
        
    </div>
    
    <!--SETAS DE NAVEGACAO-->
    <!-- Bullet Navigator -->
    <div data-u="navigator" class="jssorb052" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
        <div data-u="prototype" class="i">
            <svg viewbox="0 0 16000 16000">
                <circle class="b" cx="8000" cy="8000" r="5800"></circle>
            </svg>
        </div>
    </div>
    
    <!-- Arrow Navigator -->
    <div data-u="arrowleft" class="jssora053" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
        <svg viewbox="0 0 16000 16000">
            <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
        </svg>
    </div>
    <div data-u="arrowright" class="jssora053" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
        <svg viewbox="0 0 16000 16000">
            <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
        </svg>
    </div>
</div>

<script>jssor_1_slider_init();</script>
