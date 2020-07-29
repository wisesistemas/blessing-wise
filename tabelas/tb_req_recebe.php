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
                <th>Pedido</th>
                <th>Unidade</th>
                <th>Data / Hora</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tfoot class="text-center BG-INFO elevation-1">
            <tr>
                <th>Pedido</th>
                <th>Unidade</th>
                <th>Data / Hora</th>
                <th>Ação</th>
            </tr>
        </tfoot>
        <tbody>
        <?php

        if (intval($_POST['adm']) == 0) {
            $sql = "SELECT DISTINCT r.requisicao as registro, u.nome as unidade, r.data as data, r.hora as hora
    FROM estoque_registro_requisicao r 
    INNER JOIN cad_unidade u 
    ON r.unidade = u.id
    WHERE u.id = $_SESSION[usuario_id_unidade]";
        } else
if (intval($_POST['adm']) == 1) {
            $sql = "SELECT DISTINCT r.requisicao as registro, u.nome as unidade, f.familia as familia, r.data as data, r.hora as hora, g.genero as genero,  c.familia as idFamilia, c.genero as idGenero
    FROM estoque_registro_requisicao r 
    INNER JOIN estoque_item_requisicao i 
    ON r.requisicao = i.registro
    INNER JOIN cad_item c 
    ON i.item = c.id
    INNER JOIN cad_unidade u 
    ON r.unidade = u.id
    INNER JOIN cad_familia f 
    ON c.familia = f.id
    INNER JOIN cad_genero g 
    ON c.genero = g.id ";
        }
        $pedido = mysqli_query($link, $sql);
        while ($pedido1 = mysqli_fetch_array($pedido)) {
            $contString = strlen(24);
            if ($contString > 29) {
                $conc = '...';
            } else {
                $conc = '';
            }
        ?>

            <tr class="text-center">

                <td> <?php echo 'REQ' . $pedido1["registro"]; ?> </td>
                <td title="<?php echo $pedido1["unidade"]; ?>">
                    <?php echo  substr($pedido1["unidade"], 0, 30) . $conc;  ?> </td>
                <td>
                    <?php echo date('d/m/Y', strtotime($pedido1["data"])) . " " . date('H:s', strtotime($pedido1["hora"])) . 'h'; ?>
                </td>
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