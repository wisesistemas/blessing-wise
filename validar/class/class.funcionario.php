<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');

class Funcionario extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

   /* Cadastro de funcionario */
   function cadastroFuncionario($cpf,$pis,$matricula,$nome,$nasc,$empresa,$unidade,$funcao,$escala,$banco,$agencia,$conta,$digito,$situacao,$senha){
      echo$sql = "INSERT INTO `cad_funcionario`(`id`, `cpf`, `pis`, `matricula`, `nome`, `nasc`, `empresa`, `unidade`, `funcao`, `escala`, `banco`, `agencia`, `conta`, `digito`, `situacao`, `senha`, `rh`) VALUES 
      (
        NULL,
        '$cpf',
        $pis,
        '$matricula',
        '$nome',
        '$nasc',
        '$empresa',
        '$unidade',
        '$funcao',
        '$escala',
        $banco,
        $agencia,
        $conta,
        $digito,
        '$situacao',
        '$senha',
        '0'
      )";
      $sql = mysqli_query($this->link, $sql);
      if( intval($sql) == 1){
            return "1";
      }else{
            return '0';
      }
   }

   /* Atualização de funcionario */
   function atualizaFuncionario($id, $cpf,$pis,$matricula,$nome,$nasc,$empresa,$unidade,$funcao,$escala,$banco,$agencia,$conta,$digito,$situacao,$senha){
       if( $senha == null ){
        echo$sql = "UPDATE `cad_funcionario` SET 
        `cpf`= '$cpf',
        `pis`= $pis,
        `matricula`= '$matricula',
        `nome`= '$nome',
        `nasc`= '$nasc',
        `empresa`= '$empresa',
        `unidade`= '$unidade',
        `funcao`= '$funcao',
        `escala`= '$escala',
        `banco`= $banco,
        `agencia`= $agencia,
        `conta`= $conta,
        `digito`= $digito,
        `situacao`= $situacao
         WHERE id = $id";
       }else{
        echo$sql = "UPDATE `cad_funcionario` SET 
        `cpf`= '$cpf',
        `pis`= $pis,
        `matricula`= '$matricula',
        `nome`= '$nome',
        `nasc`= '$nasc',
        `empresa`= '$empresa',
        `unidade`= '$unidade',
        `funcao`= '$funcao',
        `escala`= '$escala',
        `banco`= $banco,
        `agencia`= $agencia,
        `conta`= $conta,
        `digito`= $digito,
        `situacao`= $situacao,
        `senha`= '$senha'
         WHERE id = $id";
       }

       $sql = mysqli_query($this->link, $sql);
        if( intval($sql) == 1){
            return "1";
        }else{
            return '0';
        }
       
    }

}
