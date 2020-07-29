<?php
require_once("db.class.php");
date_default_timezone_set('America/Sao_Paulo');

class Compra extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    function listaCrompra($item,$unidade){
        $sql = "DELETE  FROM `item_pedido_compra` where unidade = $unidade AND status = 1";
        mysqli_query($this->link, $sql);
        $sql = "SELECT  lista FROM `item_pedido_compra` ORDER BY id DESC";
        $lista = mysqli_fetch_array(mysqli_query($this->link, $sql));
        $lista = intval($lista['lista']) + 1;
        foreach ($item as $key => $value) {
            echo$sql = "INSERT INTO `item_pedido_compra`(`id`, `lista`, `item`, `unidade`, `qtd`, `dt_c`, `qtd_compra`) VALUES 
            (
                NULL,
                ' $lista',
                '$key',
                '$unidade',
                '$value[qtd_insert]',
                NULL,
                NULL
            )";
            mysqli_query($this->link, $sql);
        } 
    }

    function carregaListaCompraInicial($unidade){
        $sql = "SELECT i.id as id_item, i.nome as nome, l.qtd as qtd, l .id as id_reg
        FROM item_pedido_compra as l 
        INNER JOIN cad_item as i ON l.item = i.id 
        where unidade = $unidade AND status = 1";
        $sql = mysqli_query($this->link, $sql);
        unset( $_SESSION['itens_insert_estoque']);
        while( $item =  mysqli_fetch_array($sql) ) {
            $_SESSION['itens_insert_estoque'][$item['id_item']] = array(
                "nome" => "$item[nome]",
                "qtd_insert" => "$item[qtd]",
                "qtd_atual" => "0",
                "id_reg" => "$item[id_reg]"

            ); 
        }
    }

    function criaListaDeCompraFornecedor($idLista,$fornecedor,$qtd,$valor_unitario,$uniade,$dt_entrega){
        $sql = "UPDATE `item_pedido_compra` SET 
        `qtd_compra`= '$qtd',
        `fornecedor`= '$fornecedor',
        `valor_unitario`= '$valor_unitario',
        `unidade_entrega`= '$uniade',
        `dt_entrega` = '$dt_entrega',
        `status`= '4'
        WHERE id = $idLista";
        $sql = mysqli_query($this->link, $sql);
    }

    function cancelaItemPEdido($idLista){
        $sql = "UPDATE `item_pedido_compra` SET `status` = '0' WHERE id = $idLista";
        $sql = mysqli_query($this->link, $sql);
    }

    function gerarItemParaCompras(){
        $sql = "UPDATE `item_pedido_compra` SET `status` = 2 WHERE `status` = 1";
        $sql = mysqli_query($this->link, $sql);
    }
 
}
