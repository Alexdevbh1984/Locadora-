<option value="" selected><span>Selecione...</span></option>
<?php

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    if(isset($_POST['codigo'])){
      
      $id = $_POST['codigo'];
      
      //select para saber as cidades que estão no codigo do estado
      $sql = "SELECT * FROM tbl_cidades WHERE cod_estado =".$id;
      
      
      $select = mysqli_query($conexao, $sql);
      
      while($rscidades = mysqli_fetch_array($select)){
        
        $codigo = $rscidades['cod_cidade']; 
        $cidade = $rscidades['cidade'];
        
        //echo com os options com o codigos e as cidades
        echo("<option value='$codigo'>$cidade</option>");
        
      }
      
      
    }elseif(isset($_POST['id_categoria'])){
      
      $id = $_POST['id_categoria'];
      
      $sql = "SELECT tbl_subcategoria.*, tbl_genero.genero 
      FROM tbl_subcategoria INNER JOIN tbl_genero
      ON tbl_subcategoria.cod_genero = tbl_genero.cod_genero
      WHERE tbl_subcategoria.cod_categoria =".$id." AND tbl_genero.ativo = 1 GROUP BY cod_genero";
      
      $select = mysqli_query($conexao, $sql);
      
      while($rssubcategoria = mysqli_fetch_array($select)){
        
        $id = $rssubcategoria['cod_genero'];
        $subcategoria = $rssubcategoria['genero'];
          
          echo("<option value='$id'>$subcategoria</option>");
        
      }
      
      
    }






?>
