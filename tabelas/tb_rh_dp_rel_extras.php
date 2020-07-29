<?php
  session_start();

  require_once('../validar/class/db.class.php');
  $objDb = new db();
  $link = $objDb->conecta_mysql();
    /* DADOS DO FORMULÁRIO */  
    !empty(   $_POST['dataMinima']   )   ? $dataMinima  = $_POST['dataMinima']  : $dataMinima   = '0000-00-00';
    !empty(   $_POST['dataMaxima']   )   ? $dataMaxima  = $_POST['dataMaxima']  : $dataMaxima   = '9999-12-31';
    !empty(   $_POST['valorMenor']   )   ? $valorMenor  = $_POST['valorMenor']  : $valorMenor   = '0';   
    !empty(   $_POST['valorMaior']   )   ? $valorMaior  = $_POST['valorMaior']  : $valorMaior   = '99999999999';

    !empty(   $_POST['unidade']      )   ? $unidade     = implode(',', $_POST['unidade'])       : $unidade      = '';
    !empty(   $_POST['colaborador']  )   ? $colaborador = implode(',', $_POST['colaborador'])   : $colaborador  = '';
    !empty(   $_POST['sub']          )   ? $sub         = implode(',', $_POST['sub'])           : $sub          = '';
    !empty(   $_POST['motivo']       )   ? $motivo      = implode(",", $_POST['motivo'])        : $motivo       = '';
    !empty(   $_POST['status']       )   ? $status      = implode(',', $_POST['status'])        : $status       = '';

    $unidade        == null ? $unidade      = 'c.id > 0'            : $unidade     = "c.id in($unidade)";
    $colaborador    == null ? $colaborador  = 'b.id > 0'            : $colaborador = "b.id in($colaborador)";
    $sub            == null ? $sub          = ''                    : $sub         = "&& a.substituido in($sub)";
    $motivo         == null ? $motivo       = ''                    : $motivo      = "&& a.motivo in($motivo)";
    $status         == null ? $status       = 's.id > 0'            : $status      = "s.id in($status)";

$sql = "SELECT  a.id as 'id_extra',  REPLACE( a.data, '/', '-') as 'data', b.nome as 'funcionario', c.nome as 'unidade', d.funcao as 'funcao', e.escala as 'escala', e.tipo as 'tipo', a.status as 'status', a.substituido as substituido, a.motivo as motivo, a.valor_pagar as valor, s.status as status, a.hora_ent as entrada, a.hora_sai as saida
FROM `extras` a
INNER JOIN `cad_funcionario` b 
ON a.nome = b.cpf
INNER JOIN `cad_unidade` c 
on a.unidade_extra = c.id
INNER JOIN `cad_funcao` d 
on a.funcao_extra = d.id
INNER JOIN `cad_escala` e 
on a.escala_extra = e.id
INNER JOIN `valor_extras` f 
on a.funcao_extra = f.id_funcao
INNER JOIN status s 
ON a.status = s.id 
WHERE 
( a.data BETWEEN '$dataMinima' AND '$dataMaxima' ) 
&& a.valor_pagar >=  $valorMenor
&& a.valor_pagar <=  $valorMaior
&& $unidade
&& $colaborador 
&& $status
$sub  
$motivo
";
  
?>

 <!-- TABELA -->
 <link href="tabela/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="tabela/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
        <link href="tabela/css/style.css" rel="stylesheet">
        <!-- FIM TABELA -->
<div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-responsive">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Data</th>
                                            <th>Funcionário</th>
                                            <th>Unidade</th>
                                            <th>Função</th>
                                            <th>Escala</th>
                                            <th>Entrada</th>
                                            <th>Saida</th>
                                            <th>Substituido</th>
                                            <th>Motivo</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Ação</th>

                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Data</th>
                                            <th>Funcionário</th>
                                            <th>Unidade</th>
                                            <th>Função</th>
                                            <th>Escala</th>
                                            <th>Entrada</th>
                                            <th>Saida</th>
                                            <th>Substituido</th>
                                            <th>Motivo</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Ação</th>

                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                            

                                            $sql = mysqli_query( $link, $sql );

                                            while( $extras = mysqli_fetch_array( $sql ) ) {
                                        ?>
                                        <tr>
                                            <td><small><?php echo $extras["id_extra"] ?></td>
                                            <td><small><?php echo $extras["data"] ?></td>
                                            <td><small><?php echo $extras["funcionario"] ?></td>
                                            <td><small><?php echo $extras["unidade"] ?></td>
                                            <td><small><?php echo $extras["funcao"] ?></td>
                                            <td><small><?php echo $extras["escala"] ." - ". $extras["tipo"] ?></td>
                                            <td><small><?php echo $extras["entrada"] ?></td>
                                            <td><small><?php echo $extras["saida"]?></td>
                                            <td><small><?php  

                                               
                                             if( empty($extras['substituido']) ){
                                                
                                            }else{
                                                $sqlSub = "SELECT * FROM cad_funcionario where cpf = '$extras[substituido]'" ;
                                                $sqlSub = mysqli_query( $link, $sqlSub );
                                                $sqlSub = mysqli_fetch_array( $sqlSub );
                                                echo $sqlSub['nome'];
                                            } 
                                           
                                            


                                            ?></td>
                                            <td><small><?php echo $extras["motivo"]; ?></td>
                                            <td><small><?php echo $extras["valor"]; ?></td>
                                            <td><small><?php echo $extras["status"]; ?></td>
                                            <td><a class="btn btn-outline-dark" href="detalhes_extra.php?query_extras=<?php echo $extras["id_extra"]; ?>">Detalhes</a></td>
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