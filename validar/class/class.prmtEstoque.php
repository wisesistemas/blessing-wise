<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');
class PrmtEstoque extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    /* Ativa item na unidade*/
    function ativaItemRelacionamento( $unidade, $item, $usuario ){
        $sql = "UPDATE item_unidade SET `ativo` = 1 WHERE unidade = $unidade AND item = $item";
        $sql = mysqli_query( $this->link, $sql );
        if( mysqli_affected_rows($this->link) <= 0 ){
            $sql = "INSERT INTO `item_unidade`(`id`, `item`, `unidade`, `ativo`, `usuario`, `data_h`, `saldo`) VALUES 
            (
                NULL,
                '$item',
                '$unidade',
                '1',
                '$usuario',
                NULL,
                '0'
            )";
            $sql = mysqli_query( $this->link, $sql );
        }
    }

    /* Desativa item na unidade*/
    function desativaItemRelacionamento( $unidade, $item ){
       $sql = "UPDATE item_unidade SET `ativo` = 0 WHERE unidade = $unidade AND item = $item";
       $sql = mysqli_query( $this->link, $sql );
    }

   

}

/* $requisicao = new Requisicao();
echo $requisicao->cadastraRequisicao(); */