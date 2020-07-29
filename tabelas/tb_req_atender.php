<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
?>
<div class="table-responsive-sm">
    <div class="table-responsive my-2 ">
        <table class="table table-sm table-striped dataTable js-exportable table-bordered">
            <thead class="text-center">
                <tr class="bg-info elevation-1">
                    <th>Pedido</th>
                    <th>Unidade</th>
                    <th>Categoria <i class='fas fa-arrow-right' style="font-size: 8px;"></i> Grupo</th>
                    <th>Data / Hora</th>
                    <th>Duração</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tfoot class="text-center">
                <tr class="bg-info elevation-1">
                    <th>Pedido</th>
                    <th>Unidade</th>
                    <th>Categoria <i class='fas fa-arrow-right' style="font-size: 8px;"></i> Grupo</th>
                    <th>Data / Hora</th>
                    <th>Duração</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
            <?php
            $familia = $_POST['id'];

            if (2 == intval($familia)  || 1 == intval($familia)) {
                $sql = "SELECT DISTINCT r.requisicao as registro, u.nome as unidade, f.familia as familia, r.data as data, r.hora as hora, f.familia as genero,
  c.familia as idFamilia, c.familia as idGenero
FROM estoque_registro_requisicao r 
INNER JOIN estoque_item_requisicao i 
ON r.requisicao = i.registro
INNER JOIN cad_item c 
ON i.item = c.id
INNER JOIN cad_unidade u 
ON r.unidade = u.id
INNER JOIN cad_familia f 
ON c.familia = f.id
where i.status in(550, 552 ,560) && f.id = $familia ORDER BY `registro` ASC";
            } else
if (0 == intval($familia)) {
                $sql = "SELECT DISTINCT r.requisicao as registro, u.nome as unidade, f.familia as familia, r.data as data, r.hora as hora, f.familia as genero,
    c.familia as idFamilia, c.familia as idGenero
  FROM estoque_registro_requisicao r 
  INNER JOIN estoque_item_requisicao i 
  ON r.requisicao = i.registro
  INNER JOIN cad_item c 
  ON i.item = c.id
  INNER JOIN cad_unidade u 
  ON r.unidade = u.id
  INNER JOIN cad_familia f 
  ON c.familia = f.id
  where i.status = 555 ORDER BY `registro` ASC";
            } else {
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
where i.status in(550, 552 ,560) && f.id = $familia ORDER BY `registro` ASC";
            }

            $pedido = mysqli_query($link, $sql);
            while ($pedido1 = mysqli_fetch_array($pedido)) {
                $contString = strlen(24);
                if ($contString > 29) {
                    $conc = '...';
                } else {
                    $conc = '';
                }
                $data_inicial = $pedido1["data"];
                $data_final = date("Y-m-d");
                $diferenca = strtotime($data_final) - strtotime($data_inicial);
                $dias = floor($diferenca / (60 * 60 * 24));
            ?>

                <tr class="text-center">

                    <td> <?php echo 'REQ' . $pedido1["registro"]; ?> </td>
                    <td title="<?php echo $pedido1["unidade"]; ?>">
                        <?php echo  substr($pedido1["unidade"], 0, 30) . $conc;  ?> </td>
                    <td> <?php echo $pedido1["familia"] . " <i class='fas fa-arrow-right' style='font-size: 10px;'></i> " . $pedido1["genero"];  ?> </td>
                    <td>
                        <?php echo date('d/m/Y', strtotime($pedido1["data"])) . " " . date('h:s', strtotime($pedido1["hora"])) . 'h'; ?>
                    </td>
                    <td>
                        <?php echo $dias . ' Dias'; ?>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-12">

                                <div class="row justify-content-around">
                                    <div></div>
                                    <a data-toggle="tooltip" data-placement="top" title="Folha De Impressão" class="folha_impressao elevation-1" value="impressao/folha_separacao.php?req=<?php echo $pedido1['registro'] . "&familia=$pedido1[idFamilia]&genero=$pedido1[idGenero]"; ?>">
                                        <i class="fas fa-print " style="font-size: 25px; color: Goldenrod"></i>
                                    </a> 

                                    <a data-toggle="tooltip" data-placement="top" title="Atender Pedido" class="atender_requisicao elevation-1" value="pedido=<?php echo $pedido1['registro'] . "&familia=$pedido1[idFamilia]&genero=$pedido1[idGenero]"; ?>">
                                        <i class="fas fa-clipboard-list" style="font-size: 25px; color: Teal"></i>
                                    </a> 
                                    
                                    <a data-toggle="tooltip" data-placement="top" title="Cancelar Pedido" class="cancelar_requisicao elevation-1" value="pedido=<?php echo $pedido1['registro'] . "&familia=$pedido1[idFamilia]&genero=$pedido1[idGenero]"; ?>">
                                        <i class="fas fa-times" style="font-size: 25px; color: red"></i>
                                    </a>
                                    <div></div>
                                </div>
                            </div>
                        </div>


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