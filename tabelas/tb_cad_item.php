<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
session_start();
?>
    <div class="table-responsive my-2">
        <table class="table table-sm table-striped dataTable js-exportable table-bordered">
            <thead class="text-center BG-INFO elevation-1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Familia</th>
                    <th>Genero</th>
                    <th>Referencia</th>
                    <th>Marca</th>
                    <th>UNI</th>
                    <th>VER</th>
                </tr>
            </thead>
            <tfoot class="text-center BG-INFO elevation-1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Familia</th>
                    <th>Genero</th>
                    <th>Referencia</th>
                    <th>Marca</th>
                    <th>UNI</th>
                    <th>VER</th>
                </tr>
            </tfoot>
            <?php

            $sql = "SELECT c.id as id, 
            c.nome as nome, 
            f.familia AS familia,
            g.genero AS genero,
            c.referencia as referencia, 
            c.marca as marca, 
            m.medida as uni,
            c.modelo AS modelo,
            c.und_compra AS uni_compra,
            c.und_estoque AS uni_estoque,
            c.validade AS validade,
            c.armazenamento AS armazenamento,
            c.solicitar_ref AS ref_obrigatoria,
            c.familia AS id_familia,
            c.genero AS id_genero,
            c.ativo AS ativo
            FROM cad_item c 
            INNER JOIN cad_medida m
            ON c.und_estoque = m.id
            INNER JOIN cad_familia f 
            ON c.familia = f.id
            INNER JOIN cad_genero g 
            ON c.genero = g.id ORDER BY c.id";




            $sql = mysqli_query($link, $sql);
            while ($item = mysqli_fetch_array($sql)) {
                $contString = strlen($item["nome"]);
                if ($contString > 29) {
                    $conc = '...';
                } else {
                    $conc = '';
                }
            ?>

            <tr class="text-center">

                <td><?php echo $item["id"]; ?></td>
                <td title="<?php echo$item["nome"]; ?>"><?php echo $item["nome"]; ?></td>
                <td><?php echo $item["familia"]; ?></td>
                <td><?php echo $item["genero"]; ?></td>
                <td><?php echo $item["referencia"]; ?></td>
                <td><?php echo $item["marca"]; ?></td>
                <td><?php echo $item["uni"]; ?></td>
                <td>

                    <button data-toggle="tooltip" data-placement="top" title="Visualizar"
                        class="btn.btn-app-sm btn-light editar elevation-1"
                        value="<?php echo
                        $item['id']
                        .'*'
                        .$item['nome']
                        .'*'
                        .$item['referencia']
                        .'*'
                        .$item['modelo']
                        .'*'
                        .$item['id_familia']
                        .'*'
                        .$item['id_genero']
                        .'*'
                        .$item['marca']
                        .'*'
                        .$item['uni_compra']
                        .'*'
                        .$item['uni_estoque']
                        .'*'
                        .$item['validade']
                        .'*'
                        .$item['armazenamento']
                        .'*'
                        .$item['ref_obrigatoria']
                        .'*'
                        .$item['ativo'];?>">
                        <i class="fas fa-eye" style="font-size: 22px; color: #29088A"></i>

                    </button>

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