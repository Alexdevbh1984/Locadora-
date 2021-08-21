<?php

  require_once('db/conexao.php');
  $conexao = conexaoMysql();

  function estatistica($id){
    
    echo("<script>alert($id)<script>");
    
   /* $sql = "INSERT INTO tbl_estatistica (cod_produto_filme) VALUES (".$id.")";
    
    mysqli_query($conexao, $sql);*/
    
    
    
  }
  /*if(isset($_GET['cod_produto_filme'])){

    $id = $_GET['cod_produto_filme'];
    
    
  
  
  
  }

*/




?>