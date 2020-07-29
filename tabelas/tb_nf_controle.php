<?php
  session_start();

 
  require_once('../validar/class/db.class.php');
  $objDb = new db();
  $link = $objDb->conecta_mysql();

 require_once('../validar/class/class.financeiro.php');
 $conta = new Financeiro();

 require_once('../validar/class/class.faturamento.php');
 $objFaturamento = new Faturamento();


  !empty(   $_POST['status']        )   ? $status       = implode(',', $_POST['status'])    : $status       = '';
  !empty(   $_POST['venciInicio']   )   ? $venciInicio  = $_POST['venciInicio']             : $venciInicio  = '0000-00-00';
  !empty(   $_POST['venciFim']      )   ? $venciFim     = $_POST['venciFim']                : $venciFim     = '9999-12-31';
  !empty(   $_POST['valorMenor']    )   ? $valorMenor   = $_POST['valorMenor']              : $valorMenor   = '0';
  !empty(   $_POST['valorMaior']    )   ? $valorMaior   = $_POST['valorMaior']              : $valorMaior   = '99999999999';
  !empty(   $_POST['unidade']    )   ? $unidade   = implode(',', $_POST['unidade']): $unidade   = null;

  $_POST['categoria']  == 'null'      ? $categoria   = 'p.id > 0' : $categoria    = 'p.id = '.$_POST['categoria'];
  
  $unidade   == null ? $unidade = 'u.id > 0' : $unidade = "u.id in($unidade)";
  $status   == null ? $status = 'i.status > 0' : $status = "i.status in($status)";

    $sql = "SELECT i.id AS id, i.nf AS nf, f.empresa AS empresa, f.cnpj AS cnpj, u.nome AS unidade, i.valor AS valor, i.dt_inicial AS dt_inicial, i.dt_final AS dt_final,
    CONCAT(c.mes,'/',c.ano) AS competencia, i.`status` AS `status`, o.nome as oss,  DATE_FORMAT(f.dt_envio, '%d/%/m%Y') as envio
    FROM `fat_receber` as f 
    INNER JOIN `fat_receber_item` as i 
    ON f.id = i.fat_receber
    INNER JOIN `cad_unidade` as u 
    ON f.unidade = u.id
    INNER JOIN `custo_competencia` as c 
    ON f.competencia = c.id
    INNER JOIN cad_oss as o 
    ON f.oss = o.id WHERE $unidade AND $status";

 
    
 
?>


                                <table class="table table-sm table-striped dataTable tabela-pagamento table-bordered">
                                    <thead>
                                        <tr class="text-center text-sm bg-info elevation-1">
                                            <th>#</th>
                                            <th>OSS</th> 
                                            <th>Unidade</th> 
                                            <th>DT Recebimentos</th>
                                            <th>NF</th>
                                            <th>Valor</th>
                                            <th>Pago</th>
                                            <th>Competencia</th>
                                            <th>Envio</th>
                                            <th>Status</th>
                                    </thead>
                                    <tfoot>
                                        <tr class="text-center text-sm bg-info elevation-1">
                                            <th>#</th>
                                            <th>OSS</th> 
                                            <th>Unidade</th> 
                                            <th>DT Recebimentos</th>
                                            <th>NF</th>
                                            <th>Valor</th>
                                            <th>Pago</th>
                                            <th>Competencia</th>
                                            <th>Envio</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $valorTotal = 0;
                                            $registroTotal = 0;
                                            $sql = mysqli_query( $link, $sql );

                                            while( $item = mysqli_fetch_array( $sql ) ) {

                                                if( $item['status'] == 1 ){
                                                    $status = "Cadastrado";
                                                }else
                                                if( $item['status'] == 2 ){
                                                    $status = "Em Andamento";
                                                }else
                                                if( $item['status'] == 3 ){
                                                    $status = "Finalizado";
                                                }else
                                                if( $item['status'] == 4 ){
                                                    $status = "Excluido";
                                                }
                                            

                                        ?>
                                        <tr class="text-center text-sm editar" id="<?php echo $item['id'].'*'.$item['status']; ?>">
                                            <td><?php echo$item['id']; ?></td>
                                            <td><?php echo$item['oss']; ?></td>
                                            <td><?php echo$item['unidade']; ?></td>
                                            <td><?php echo$objFaturamento->datasDePagamento($item['id']); ?></td>
                                            <td><?php echo$item['nf']; ?></td>
                                            <td><?php echo 'R$&nbsp'.number_format($item['valor'], 2, ',', '.'); ?> </td>
                                            <td><?php echo 'R$&nbsp'.number_format($objFaturamento->regPagamentoValorTotal($item['id']), 2, ',', '.'); ?>  </td>
                                            <td><?php echo$item['competencia']; ?> </td>
                                            <td><?php echo$item['envio']; ?></td>
                                            <td><?php echo $status; ?> </td>
                                        </tr>
                                        
                                        <?php
                                            }
                                        ?>

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