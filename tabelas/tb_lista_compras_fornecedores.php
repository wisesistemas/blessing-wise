<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
session_start();
?>

<div class="table-responsive">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead class="text-center BG-INFO elevation-1">
            <tr>
                <th>Fornecedor</th>
                <th>QTD Item</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tfoot class="text-center BG-INFO elevation-1">
            <tr>
                <th>Fornecedor</th>
                <th>QTD Item</th>
                <th>Ação</th>
            </tr>
        </tfoot>
        <tbody>
        <?php


            $sql = "SELECT * FROM vw_lista_compra_por_fornecedor";

        $pedido = mysqli_query($link, $sql);
        while ($pedido1 = mysqli_fetch_array($pedido)) {
           
        ?>

            <tr class="text-center">

                <td><?php echo$pedido1["nome_fornecedor"]; ?></td>
                <td><?php echo$pedido1["qtd_item"];  ?> </td>
                <td>

                    <button data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn.btn-app-sm btn-light visualizar_requisicao elevation-1" value="pedido=<?php echo $pedido1['registro']; ?>">
                        <i class="fas fa-eye" style="font-size: 22px; color: #29088A"></i>

                    </button>
                    <?php if (intval($_POST['adm']) == 0) { ?>
                        |
                        <button data-toggle="tooltip" data-placement="top" title="Receber" class="btn.btn-app-sm btn-light receber_requisicao elevation-1" value="pedido=<?php echo $pedido1['registro']; ?>">
                            <i class="fab fa-creative-commons-remix" style="font-size: 22px; color: green"></i>

                        </button>
                        |
                        <button data-toggle="tooltip" data-placement="top" title="Retificar" class="btn.btn-app-sm btn-light retificar_requisicao elevation-1" value="pedido=<?php echo $pedido1['registro']; ?>">
                            <i class="fab fa-creative-commons-share" style="font-size: 22px; color: red"></i>

                        </button>
                    <?php } ?>
                </td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
</div>
<!-- Tabela  -->
<script src="plugins/tabela/plugins/jquery/jquery.js"></script>
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
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>