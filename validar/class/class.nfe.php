<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');
class Nfe extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    function cadNfe($modelo, $serie, $codigo, $dt_emissao, $dt_entrada, $valor_total, $chave, $v_xml, $destinatario, $fornecedor, $arq, $itens){
        $sql = "INSERT INTO `cad_nfe`(`id`, `modelo`, `serie`, `codigo`, `dt_emissao`, `dt_entrada`, `valor_total`, `chave`, `v_xml`, `destinatario`, `fornecedor`, `arq`) VALUES 
        (
            NULL,
            '$modelo',
            '$serie',
            '$codigo',
            '$dt_emissao',
            '$dt_entrada',
            '$valor_total',
            '$chave',
            '$v_xml',
            '$destinatario',
            '$fornecedor',
            '$arq'
        )";  
        $sql = mysqli_query($this->link, $sql);
        $nfe = mysqli_insert_id( $this->link );
        foreach ($itens as $key => $value) {
          
        $sqlItem = "INSERT INTO `cad_nfe_item`(`id`, `nfe`, `item_cod_fornecedor`, `item_nome_fornecedor`, `item_wise`, `qtd`, `uni`, `valor_uni`) VALUES 
        (
            NULL,
            '$nfe',
            '".$value[$key]['item_forn_cod']."',
            '".$value[$key]['item_forn_nome']."',
            '$key',
            '".$value[$key]['item_qtd']."',
            '".$value[$key]['item_forn_uni']."',
            '".str_replace('R$:','',str_replace(',','.',$value[$key]['valor_unit']))."'
        )";
        
         $sql = mysqli_query($this->link, $sqlItem);
        }
       
    }

    /* Verifica se a nfe já foi com o mesmo fornecedor */
    function verificaNfeVSfonecedor( $nfe, $fornecedor ){
        $sql = "SELECT COUNT(id) AS existe FROM cad_nfe WHERE cad_nfe.codigo = '$nfe' AND cad_nfe.fornecedor = '$fornecedor'";
        $res = mysqli_fetch_array( mysqli_query($this->link, $sql) );
        if( intval($res['existe']) > 0 ){
            return 1;
        }else{
            return 0;
        }
    }
    

}
