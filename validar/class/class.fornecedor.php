<?php
 require_once("db.class.php");

class Fornecedor extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    /* Busca todos ps fornecedores por like em nome*/
    function fonecedoresPorNome(){
        $sql = "SELECT * FROM cad_fornecedor";
        $sql = mysqli_query($this->link, $sql);
        $cursos = array();
        while ( $fornecedor = mysqli_fetch_array( $sql )) {
            $cursos[] = "$fornecedor[nome]";
        }
        return  $cursos;
    }

    /* Busca todos ps fornecedores por like em CNPJ*/
    function fonecedoresPorCnpj(){
        $sql = "SELECT * FROM cad_fornecedor";
        $sql = mysqli_query($this->link, $sql);
        $cursos = array();
        while ( $fornecedor = mysqli_fetch_array( $sql )) {
            $cursos[] = "$fornecedor[cnpj]";
        }
        return  $cursos;
    }

    /* Busca fornecedor por cnpj */
    function fonecedoresPorCnpjUnico( $cnpj ){
        $sql = "SELECT * FROM cad_fornecedor WHERE cnpj = '$cnpj' ";
        $sql1 = mysqli_fetch_array( mysqli_query($this->link, $sql) );
        $forn = [
            "nome" => $sql1['nome'],
            "cnpj" => $sql1['cnpj'],
            "es" => $sql1['es'],
            "uf" => $sql1['uf'],
        ];
      
        return  $forn;
    }

     /* Busca fornecedor por Nome */
    function fonecedoresPorNomeUnico( $nome ){
        $sql = "SELECT * FROM cad_fornecedor WHERE nome = '$nome' ";
        $sql1 = mysqli_fetch_array( mysqli_query($this->link, $sql) );
        $forn = [
            "nome" => $sql1['nome'],
            "cnpj" => $sql1['cnpj'],
            "es" => $sql1['es'],
            "uf" => $sql1['uf'],
        ];
      
        return  $forn;
    }

    /* Verifica se existe Fonecedor, caso não: o mesmo é cadastrado */
    function verificaEcadastraFornecedor( $nome, $cnpj, $es, $uf){
        $sql = "SELECT COUNT(id) as existe FROM `cad_fornecedor` WHERE cnpj = '$cnpj'";
        $res = mysqli_fetch_array( mysqli_query($this->link, $sql) );
        if($res['existe'] == 0){
            $sql = "INSERT INTO `cad_fornecedor`(`id`, `nome`, `cnpj`, `es`, `uf`, `ativo`) VALUES 
            (
                NULL,
                '$nome',
                '$cnpj',
                '$es',
                '$uf',
                '1'
            )";
            mysqli_query($this->link, $sql);
        }
    }


}
