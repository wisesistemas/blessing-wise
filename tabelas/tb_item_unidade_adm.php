<?php session_start(); 
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();

/* DADOS DO FORMULÁRIO */
!empty($_POST['dt_inicial'])   ? $dataMinima  = $_POST['dt_inicial']  : $dataMinima   = '0000-00-00';
!empty($_POST['dt_final'])   ? $dataMaxima  = $_POST['dt_final']  : $dataMaxima   = '9999-12-31';

!empty($_POST['item'])   ? $item     = implode(',', $_POST['item'])       : $item      = '';
$item        == null ? $item      = ' i.id > 0'            : $item     = " i.id in($item)";






$sql = "SELECT i.id AS id_item, u.nome AS unidade, i.nome AS item, 
SUM(rr.qtd_slt) AS solicitado, SUM(rr.qtd_env) as enviado, SUM(rr.qtd_ent) AS recebido
FROM estoque_registro_requisicao AS r 
INNER JOIN estoque_item_requisicao AS rr
ON r.requisicao = rr.registro
INNER JOIN cad_unidade AS u 
ON r.unidade = u.id
INNER JOIN cad_item as i
ON rr.item = i.id 
WHERE $item AND r.`data` BETWEEN '$dataMinima' AND '$dataMaxima'
GROUP BY u.id, i.nome";
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

<div class="table-responsive-sm my-2">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead>
            <tr class="text-center text-sm bg-info elevation-1">
                <th scope="col" data-toggle="tooltip" data-placement="top" title="ID do item.">ID</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Nome do item.">NOME ITEM</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Unidade.">UNIDADE</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade total solicitado.">SOLICITADO</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade total enviado.">ENVIADO</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade total recebido.">RECEBIDO</th>
            </tr>
        </thead>
        <tfoot>
                <tr class="text-center bg-info elevation-1">
                    <th scope="col" data-toggle="tooltip" data-placement="top" title="ID do item.">ID</th>
                    <th scope="col" data-toggle="tooltip" data-placement="top" title="Nome do item.">NOME ITEM</th>
                    <th scope="col" data-toggle="tooltip" data-placement="top" title="Unidade.">UNIDADE</th>
                    <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade total solicitado.">SOLICITADO</th>
                    <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade total enviado.">ENVIADO</th>
                    <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade total recebido.">RECEBIDO</th>
                </tr>
            </tfoot>
        <tbody>
            <?php 
            $sql = mysqli_query( $link, $sql );
            while( $res = mysqli_fetch_array( $sql) ) {  ?>
            <tr class="text-center text-sm">
                <th scope="row" title="ID do item."><?php echo$res['id_item'];?></th>
                <td title="Nome do item."><?php echo$res['item'];?></td>
                <td title="Unidade."><?php echo$res['unidade'];?></td>
                <td title="Quantidade total solicitado."><?php echo$res['solicitado'];?></td>
                <td title="Quantidade total enviado."><?php echo$res['enviado'];?></td>
                <td title="Quantidade total recebido."><?php echo$res['recebido'];?></td>
            </tr>
            <?php } ?>
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
<script>
    $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

</script>