<?php
  session_start();

 
  require_once('../validar/class/db.class.php');
  $objDb = new db();
  $link = $objDb->conecta_mysql();

 require_once('../validar/class/class.financeiro.php');
 $conta = new Financeiro();


  !empty(   $_POST['status']        )   ? $status       = implode(',', $_POST['status'])    : $status       = '';
  !empty(   $_POST['venciInicio']   )   ? $venciInicio  = $_POST['venciInicio']             : $venciInicio  = '0000-00-00';
  !empty(   $_POST['venciFim']      )   ? $venciFim     = $_POST['venciFim']                : $venciFim     = '9999-12-31';
  !empty(   $_POST['valorMenor']    )   ? $valorMenor   = $_POST['valorMenor']              : $valorMenor   = '0';
  !empty(   $_POST['valorMaior']    )   ? $valorMaior   = $_POST['valorMaior']              : $valorMaior   = '99999999999';
  !empty(   $_POST['fornecedor']    )   ? $fornecedor   = implode(',', $_POST['fornecedor']): $fornecedor   = null;
  !empty(   $_POST['unidade']        )  ? $unidade      = implode(',', $_POST['unidade'])   : $unidade      = null;
  !empty(   $_POST['categoria']    )   ? $categoria   = implode(',', $_POST['categoria']): $categoria   = null;

  $fornecedor   == null ? $fornecedor = 'f.id > 0' : $fornecedor = "f.id in($fornecedor)";
  $status   == null ? $status = 'i.status > 0' : $status = "i.status in($status)";
  $unidade   == null ? $unidades = 'g.id > 0' : $unidades = "g.id in($unidade)";
  $categoria   == null ? $categoria = 'p.id > 0' : $categoria = "p.id in($categoria)";

    $sql = "SELECT i.id as id, f.nome as fornecedor, p.nome AS categoria, g.nome as unidade, i.dt_vencimento as vencimento, 
    i.ordem as parcela, i.valor as valor, i.status as status, c.detalhe AS obs
    FROM conta_pagar_item i 
    INNER JOIN conta_pagar c 
    ON i.conta_pagar = c.id
    INNER JOIN cad_fornecedor f 
    ON c.fornecedor = f.id
    INNER JOIN cad_unidade g 
    ON c.cad_unidade = g.id
    INNER JOIN plano_contas p
    ON c.categoria = p.id
    WHERE 
    ( i.dt_vencimento BETWEEN ' $venciInicio' and '$venciFim' ) 
    && 
    (   i.valor > '$valorMenor' && i.valor < '$valorMaior' )
    &&
    $fornecedor
    &&
    $status
    &&
    $categoria 
    &&
    $unidades 
    ORDER BY i. dt_vencimento DESC";
 
    
 
?>


                                <table class="table table-sm table-striped dataTable tabela-pagamento table-bordered">
                                    <thead>
                                        <tr class="text-center text-sm bg-info elevation-1">
                                            <th>#</th>
                                            <th>Fornecedor</th>
                                            <th>Unidade</th> 
                                            <th>Categoria</th> 
                                            <th>Vencimento</th>
                                            <th>Parcela</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                    </thead>
                                    <tfoot>
                                        <tr class="text-center text-sm bg-info elevation-1">
                                            <th>#</th>
                                            <th>Fornecedor</th>
                                            <th>Unidade</th>
                                            <th>Categoria</th> 
                                            <th>Vencimento</th>
                                            <th>Parcela</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $valorTotal = 0;
                                            $registroTotal = 0;
                                            $sql = mysqli_query( $link, $sql );

                                            while( $item = mysqli_fetch_array( $sql ) ) {

                                                $valorTotal     = $valorTotal + $item['valor'];
                                                $registroTotal  = $registroTotal + 1;
                                                if( $item['status'] == 1 ){
                                                    $status = "PENDENTE";
                                                }else
                                                if( $item['status'] == 5 ){
                                                    $status = "FINALIZADO";
                                                }else
                                                if( $item['status'] == 6 ){
                                                    $status = "EXCLUIDO";
                                                }
                                                $rgValorTotal       = $conta->regPagamentoValorTotalSemClassDB($item['id']);
                                                $rgQtdPagamentos    = $conta->regPamentoQuantidadeParcelasPagasClassDB($item['id']);
                                                $valor = number_format($item['valor'], 2, ',', '.');

                                        ?>
                                        <tr class="text-center text-sm editar" id="<?php echo $item['id'].'*'.$item['status']; ?>">
                                            <td><?php echo$item['id']; ?> </td>
                                            <td><?php echo$item['fornecedor']; ?> </td>
                                            <td><?php echo$item['unidade']; ?> </td>
                                            <td><?php echo$item['categoria']; ?> </td>
                                            <td><?php echo date('d/m/Y', strtotime( $item['vencimento'] ) ); ?></td>
                                            <td><?php echo$item['parcela']; ?> </td>
                                            <td><?php echo 'R$ ' . number_format($item['valor'], 2, ',', '.'); ?> </td>
                                            <td><?php echo$status; ?> </td>      
                                        </tr>
                                        
                                        <?php
                                            }
                                        ?>
                                        <!-- <tr class="text-center">
                                            <td>TOTAL</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php /* echo $registroTotal; */ ?></td>
                                            <td><?php /* echo 'R$ ' . number_format($valorTotal, 2, ',', '.'); */ ?></td>
                                            <td></td>
                                        </tr> -->
                                    </tbody>
                                </table>
 
            

<!-- Tabela  -->
<script src="plugins/tabela/plugins/jquery/jquery.js"></script>
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
<script>
    $(document).ready( function(){
        $("#total").attr('readonly', 'readonly');
        $("#total").val(" R$: <?php echo number_format($valorTotal, 2, ',', '.'); ?>");
        $("#totalRegistro").val(" <?php echo $registroTotal; ?>");  
    })

    $(function () {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-br-pre": function ( a ) {
            if (a == null || a == "") {
                return 0;
            }
            var brDatea = a.split('/');
            return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
        },
    
        "date-br-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
    
        "date-br-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });
    //Exportable table
    $('.tabela-pagamento').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columnDefs: [
            {
                type: 'date-br', 
                targets: 4
            }
        ],
    });
});
</script>