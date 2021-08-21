<?php

function conexaoMysql (){
    
    /*
        mysqli_connect() - bibliotecaa de conexão com BD MYSQL,
        vigente até o php()

        mysqli() - biblioteca de conexão com BD mysql,
        vigente as versões atuais

        PDO - biblioteca de conexão com BD mysql mais utilizado em projetos de Orientados a Objetos

    */
  
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);

    //variável que vai receber a conexão com o banco de dados
    $conexao = null;

    /*variáveis para estabeler a conexão com o banco de dados*/
    $server = "localhost"; /*essa váriavel é colocado o servidor em que será hospedado/no caso aqui é localhost(em outro pode ser o IP do servidor)*/
    
    $user = "root"; /*usuario do banco de dados*/
    
    $password ="";
    $database = "db_locadora";

    /*essa é a ordem utilizada*/
    $conexao = mysqli_connect($server,$user,$password,$database);

    //retornando a conexão para utilizar em todas as páginas
    return $conexao;
    
}



?>