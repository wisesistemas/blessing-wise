<?php
 require_once("db.class.php");

class ItemEstoque extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    /* Função retorna o nome do item parametrizado | Refatorar*/
    function nomeItemParametrizado( $item ){
        $sql = "SELECT 
        id, 
        nome, 
        IF(LENGTH(cad_item.referencia) > 0,CONCAT('- ',cad_item.referencia), '') AS referencia,
        IF(LENGTH(cad_item.marca) > 0,CONCAT('- ',cad_item.marca), '') AS marca
        FROM cad_item
        where id = $item";
        $sqlItem = mysqli_fetch_array( mysqli_query( $this->link, $sql ) );
        return $sqlItem['nome'] . '  ' . $sqlItem['referencia'] . '  ' . $sqlItem['marca'];
        /* $item =  $sql1 ); */

      /*   if( $item['referencia'] ){
            $referencia = "Ref:".$item['referencia']."";
        }else{
            $referencia = '';
        }

        if( $item['marca'] ){
            $marca = "Marca:".$item['marca']."";
        }else{
            $marca = '';
        }


        if( $item['uni'] ){
            $uni = "Uni:".$item['uni']."";
        }else{
            $uni = '';
        }

        $nome = $item['nome']." <strong>|</strong> $referencia $marca $uni"; */
    }

    function requeridoReferencia($itemId){
        $sql = "SELECT solicitar_ref FROM cad_item WHERE id = $itemId";
        $res = mysqli_fetch_array( mysqli_query( $this->link, $sql ) );
        return $res['solicitar_ref'];
    }

    function fonecedores(){
        $sql = "SELECT * FROM cad_fornecedor";
        $sql = mysqli_query($this->link, $sql);
        $cursos = array();
        while ( $fornecedor = mysqli_fetch_array( $sql )) {
            $cursos[] = "$fornecedor[nome]";
        }
        return  $cursos;
    }

    function cadItem($nome,$ref,$marca,$modelo,$familia,$genero,$armazenamento,$unidade_compra,$unidade_medida,$validade,$solicitar_ref,$ativo){
       $sql = "INSERT INTO `cad_item`(`id`, `nome`, `referencia`, `marca`, `modelo`, `familia`, `genero`, `armazenamento`, `especie`, `und_compra`, `und_estoque`, `fator`, `lote`, `validade`, `solicitar_ref`, `ativo`) VALUES 
        (
            NULL,
            '$nome',
            '$ref',
            '$marca',
            '$modelo',
            '$familia',
            '$genero',
            '$armazenamento',
            '*',
            '$unidade_compra',
            '$unidade_medida',
            '*',
            '*',
            '$validade',
            '$solicitar_ref',
            '$ativo'
        )";
        $sql = mysqli_query($this->link, $sql);
        if( intval($sql) == 1){
            echo "1";
        }else{
            echo '0';
        }
    }

    function atualizaItem($id,$nome,$ref,$marca,$modelo,$familia,$genero,$armazenamento,$unidade_compra,$unidade_medida,$validade,$solicitar_ref,$ativo){
        $sql = "UPDATE `cad_item` SET 
        `nome`='$nome',
        `referencia`='$ref',
        `marca`='$marca',
        `modelo`='$modelo',
        `familia`='$familia',
        `genero`='$genero',
        `armazenamento`='$armazenamento',
        `und_compra`='$unidade_compra',
        `und_estoque`='$unidade_medida',
        `validade`='$validade',
        `solicitar_ref`='$solicitar_ref',
        `ativo`='$ativo' 
        WHERE id = $id";
        $sql = mysqli_query($this->link, $sql);
        if( intval($sql) == 1){
            echo "1";
        }else{
            echo '0';
        }
    }

}
/* 
$estoque = new Estoque();
$estoque->saldoAtual(24,1); */