<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');

class Faturamento extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }


    function cadFatura($competencia, $valor, $empresa, $cnpj, $obs, $usu, $unidade, $qtd_exames, $oss, $dt_envio){
        $sql = 'INSERT INTO `fat_receber`(`id`, `competencia`, `oss`, `valor`, `empresa`, `cnpj`, `unidade`,`obs`, `dt_cad`, `usu_cad`, `qtd_exame`, `dt_envio`) VALUES 
        (
            NULL,
            "'.$competencia.'",
            "'.$oss.'",
            "'.$valor.'",
            "'.$empresa.'",
            "'.$cnpj.'",
            "'.$unidade.'",
            "'.$obs.'",
            CURRENT_TIMESTAMP(),
            '.$usu.',
            '.$qtd_exames.',
            "'.$dt_envio.'"
        )';
        $sql = mysqli_query( $this->link, $sql );
        return mysqli_insert_id($this->link);
    }

    function cadFaturaItem($fat_receber,$valor, $dt_inicial, $dt_final, $nf, $arq){
        $sql = 'INSERT INTO `fat_receber_item`(`id`, `fat_receber`, `dt_inicial`, `dt_final`, `nf`, `valor` ,`arq`, `atua`) VALUES 
        (
            NULL,
            "'.$fat_receber.'",
            "'.$dt_inicial.'",
            "'.$dt_final.'",
            "'.$nf.'",
            "'.$valor.'",
            "'.$arq.'",
            CURRENT_TIMESTAMP()
        )';
        $sql = mysqli_query( $this->link, $sql );
        return 1;
    }

    function registraPagamento($item, $pg_valor, $pg_data, $pg_obs, $cad_usu, $pg_tipo, $arq){
        $sql = 'INSERT INTO `reg_pagamentos_nf`(`id`, `item`, `pg_valor`, `pg_data`, `pg_obs`, `cad_usu`, `pg_tipo`, `arq`) VALUES 
        (
            NULL,
            "'.$item.'",
            "'.$pg_valor.'",
            "'.$pg_data.'",
            "'.$pg_obs.'",
            "'.$cad_usu.'",
            "'.$pg_tipo.'",
            "'.$arq.'"
        )';
        $sql = mysqli_query( $this->link, $sql );
        $sql = "UPDATE `fat_receber_item` SET `status` = '2'  WHERE id = $item";
        $sql = mysqli_query( $this->link, $sql );
        return 1;
    }

    function regPagamentoValorTotal($id){
        $sql = "SELECT round(SUM(reg_pagamentos_nf.pg_valor),2) as total FROM `reg_pagamentos_nf` where item = $id";
        $total = mysqli_fetch_array(mysqli_query( $this->link, $sql ) );
        return $total['total'];
    }

    function regPamentoQuantidadeParcelasPagasClassDB($id){
        $sql = "SELECT COUNT(id) as total FROM `reg_pagamentos_nf` where item = $id";
        $total = mysqli_fetch_array(mysqli_query( $this->link, $sql ) );
        return $total['total'];
    }

    function datasDePagamento($id){
        $sql = "SELECT group_concat(DATE_FORMAT(pg_data, '%d/%m/%Y') SEPARATOR ' | ') AS dts 
        FROM reg_pagamentos_nf WHERE item = $id";
        $total = mysqli_fetch_array(mysqli_query( $this->link, $sql ) );
        return $total['dts'];
    }

    function baixa($id, $rgValorTotal){
        $sql = "UPDATE `fat_receber_item` SET `valor_pg` = '$rgValorTotal', `status` = '3'  WHERE id = $id";
        $sql = mysqli_query( $this->link, $sql );
    }

    function excluirNf($id, $desc){
        $sql = 'UPDATE `fat_receber_item` SET `obs_exclusao` = "'.$desc.'", `status` = "4"  WHERE id = '.$id;
        $sql = mysqli_query( $this->link, $sql );
    }

}
