<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
require_once("../validar/class/class.estoque.php");
$objEstoque = new Estoque();
session_start();
$unidade = $_POST['unidade'];
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
                    <th>ID</th>
                    <th>ITEM</th>
                    <th>REFERENCIA</th>
                    <th>MARCA</th>
                    <th>FAMILIA</th>
                    <th>GENERO</th>
                    <th>SALDO</th>
                </tr>
            </thead>
            <tfoot>
                <tr class="text-center bg-info elevation-1">
                    <th>ID</th>
                    <th>ITEM</th>
                    <th>REFERENCIA</th>
                    <th>MARCA</th>
                    <th>FAMILIA</th>
                    <th>GENERO</th>
                    <th>SALDO</th>
                </tr>
            </tfoot>
            <tbody>
                <?php

                $sql = " SELECT * FROM vw_rel_item_unidade WHERE id_unidade = $unidade";

                $sql = mysqli_query($link, $sql);

                while ($item = mysqli_fetch_array($sql)) {

                ?>
                    <tr class="text-center" >
                        <td><?php echo$item['id'];?></td>
                        <td><?php echo$item['nome'];?></td>
                        <td><?php echo$item['referencia'];?></td>
                        <td><?php echo$item['marca']; ?></td>
                        <td><?php echo$item['familia']; ?></td>
                        <td><?php echo$item['genero']; ?></td>
                        <td><?php echo$item['saldo']; ?></td>
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