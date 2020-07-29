<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
require_once("../validar/class/class.estoque.php");
$objEstoque = new Estoque();
session_start();
$tabela = $_POST['tabela'];

if (intval($tabela) == 0) {
    $sql = "SELECT d.cod_procedimento AS cod, t.tabela AS tabela, d.descricao AS descricao, concat('R$ ', format(d.valor,2)) AS valor 
    FROM valor_procedimento d
    INNER JOIN desc_tabelas t
    ON d.id_tabela = t.id WHERE t.ativo = 1";
} else {
    $sql = "SELECT d.cod_procedimento AS cod, t.tabela AS tabela, d.descricao AS descricao, concat('R$ ', format(d.valor,2)) AS valor 
    FROM valor_procedimento d
    INNER JOIN desc_tabelas t
    ON d.id_tabela = t.id
    WHERE t.id = $tabela";
}

?>
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
<style>
    th {
        font-family: 'Noto Sans JP', serif;
        font-size: 12px;
        font-style: normal;
    }

    td {
        font-family: 'Noto Sans JP', serif;
        font-size: 10px;
        font-style: normal;
    }
</style>
<div class="table-responsive">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead>
            <tr class="text-center bg-info elevation-1">
                <th style="width: 29.2px;padding-right: 59px;"><b>Cód</b></th>
                <th style="width: 38.2px;padding-right: 65px;"><b>Tabela</b></th>
                <th><b>Procedimento</b></th>
                <th style="width: 38.2px;padding-right: 65px;"><b>Valor</b></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="text-center bg-info elevation-1">
                <th><b>Cód</b></th>
                <th><b>Tabela</b></th>
                <th><b>Procedimento</b></th>
                <th><b>Valor</b></th>
            </tr>
        </tfoot>
        <tbody>
            <?php

            $sql = mysqli_query($link, $sql);

            while ($item = mysqli_fetch_array($sql)) {

            ?>
                <tr class="text-center">
                    <td><?php echo $item['cod']; ?></td>
                    <td><?php echo $item['tabela']; ?></td>
                    <td><?php echo $item['descricao']; ?></td>
                    <td><?php echo $item['valor']; ?></td>
                </tr>
            <?php

            }
            ?>

        </tbody>
    </table>
</div>
<!-- Tabela  -->
<script src="plugins/tabela/plugins/jquery/jquery.min.js"></script>
<script src="plugins/tabela/plugins/node-waves/waves.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.flash.min.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.html5.min.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.print.min.js">
</script>
<script src="plugins/tabela/js/pages/tables/jquery-datatable.js"></script>
<!-- FIM Tabela  -->