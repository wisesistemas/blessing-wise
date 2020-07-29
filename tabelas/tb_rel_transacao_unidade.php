<?php session_start(); 
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
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
<div class="card ">
    <div class="card-header">
        <h3 class="card-title">Relatório transação estoque dos ultimos 100 registros:</h3>
    </div>
<div class="table-responsive-sm my-1">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead>
            <tr class="text-center text-sm bg-info elevation-1">
                <th scope="col" data-toggle="tooltip" data-placement="top" title="ID do item.">ID</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Nome do item.">NOME ITEM</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade a ser inserida.">USUARIO</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Saldo atual do estoque.">DATA</th>
                <th scope="col">TRANSAÇÃO</th>
                <th scope="col">DESCRIÇÃO</th>
                <th scope="col">QTD</th>
            </tr>
        </thead>
        <tfoot>
                <tr class="text-center bg-info elevation-1">
                <th scope="col" data-toggle="tooltip" data-placement="top" title="ID do item.">ID</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Nome do item.">NOME ITEM</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade a ser inserida.">USUARIO</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Saldo atual do estoque.">DATA</th>
                <th scope="col">TRANSAÇÃO</th>
                <th scope="col">DESCRIÇÃO</th>
                <th scope="col">QTD</th>
                </tr>
            </tfoot>
        <tbody>
            <?php 
            $sql = "SELECT i.nome AS nome_item, 
            u.nome AS unidade,
            usu.nome AS nome_usuario, 
            es.`data` AS `data`, 
            tipo.nome AS tipo, 
            tipo.obs AS tipo_obs,
            es.qtd_informado AS qtd_inserido,
            i.id AS id_item
            FROM estoque AS es
            INNER JOIN usuarios as usu
            ON es.usuario = usu.id 
            INNER JOIN estoque_tipo_transacoes AS tipo
            ON es.tipo = tipo.tipo
            INNER JOIN cad_item AS i 
            ON es.item = i.id
            INNER JOIN cad_unidade AS u
            ON es.unidade = u.id
            WHERE es.unidade = $_POST[unidade] AND `data` != '0000-00-00'  ORDER BY es.id DESC limit 100 ";
            $sql = mysqli_query( $link, $sql );
            while( $res = mysqli_fetch_array( $sql) ) {  ?>
            <tr class="text-center text-sm">
                <th scope="row" title="ID do item"><?php echo$res['id_item'];?></th>
                <td title="Nome do item!"><?php echo$res['nome_item'];?></td>
                <td title="Usuário!"><?php echo$res['nome_usuario'];?></td>
                <td title="Data!"><?php echo date("d/m/Y", strtotime($res['data']));;?></td>
                <td title="Transação!"><?php echo$res['tipo'];?></td>
                <td title="Descrição!"><?php echo$res['tipo_obs'];?></td>
                <td title="Quantidade!"><?php echo$res['qtd_inserido'];?></td>
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