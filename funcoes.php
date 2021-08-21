<?php

  /*define("DIRETORIO", "arquivos/");
  define("ARQUIVOS_PERMITIDOS", [".jpg", ".jpeg", ".png"]);*/


  function precoBrasileiro ($preco){
    
    $precoBrasileiro = str_replace(".",",",$preco);
    
    
    
    return $precoBrasileiro;
    
    
  }

  function precoBancoDados ($precoBD){
    
    $precoBD = str_replace(",",".",$precoBD);
    
    
    
    return $precoBD;
    
    
  }

  function dataBrasileiro ($dataBrasil){
    
    $data = explode("-", $dataBrasil);
    $dataResult = $data[2]."/".$data[1]."/".$data[0];
      
      
    return $dataResult;

  } 

  function dataBancoDados ($dataBD){
    
    $data = explode("/", $dataBD);
    $dataResult = $data[2]."-".$data[1]."-".$data[0];
      
      
    return $dataResult;

  } 

/*
  function duracaoFilmeSite ($tempo){
    
    $tempoFilme = explode(":", $tempo);
    $tempoResult = $tempoFilme[0]."h ".$tempoFilme[1]."m";
      
      
    return $tempoResult;

  } 
*/







?>