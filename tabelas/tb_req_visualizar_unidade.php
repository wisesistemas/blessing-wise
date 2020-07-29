<?php
    require_once('../validar/class/db.class.php');
    $objDb = new db();
    $link = $objDb->conecta_mysql();
?>
<div class="container my-2">
    <div class="table-responsive my-2">
        <table class="table table-sm table-striped dataTable js-exportable">
            <thead class="text-center BG-INFO">
                <tr>
                    <th><strong>Pedido</strong></th>
                    <th><strong>Unidade</strong></th>
                    <th><strong>Categoria <i class='fas fa-arrow-right' style="font-size: 8px;"></i> Grupo</strong></th>
                    <th><strong>Data / Hora</strong></th>
                    <th><strong>Ação</strong></th>
                </tr>
            </thead>
            <tfoot class="text-center BG-INFO">
                <tr>
                    <th><strong>Pedido</strong></th>
                    <th><strong>Unidade</strong></th>
                    <th><strong>Categoria <i class='fas fa-arrow-right' style="font-size: 8px;"></i> Grupo</strong></th>
                    <th><strong>Data / Hora</strong></th>
                    <th><strong>Ação</strong></th>
                </tr>
            </tfoot>
            <?php

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
ON c.genero = g.id 
WHERE u.id = $_SESSION[usuario_id_unidade]";




$pedido = mysqli_query( $link, $sql );
while ( $pedido1 = mysqli_fetch_array( $pedido ) ){
    $contString = strlen(24);
    if($contString > 29){
        $conc = '...';
    }else{
        $conc = '';
    }
 ?>

            <tr class="text-center">

                <td> <small> <?php echo 'REQ'.$pedido1["registro"]; ?> </td>
                <td title="<?php echo $pedido1["unidade"];?>"> <small>
                        <?php echo  substr($pedido1["unidade"], 0, 30).$conc;  ?> </td>
                <td> <small>
                        <?php echo $pedido1["familia"]." <i class='fas fa-arrow-right' style='font-size: 10px;'></i> ".$pedido1["genero"];  ?>
                </td>
                <td> <small>
                        <?php echo date('d/m/Y',strtotime($pedido1["data"]))." ". date('h:s',strtotime($pedido1["hora"])).'h'; ?>
                </td>
                <td>
                    <button data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn.btn-app-sm btn-light cancelar_requisicao"
                        value="pedido=<?php echo $pedido1['registro']."&familia=$pedido1[idFamilia]&genero=$pedido1[idGenero]" ;?>">
                            <i class="fas fa-eye" style="font-size: 18px; color: #29088A"></i>
      
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