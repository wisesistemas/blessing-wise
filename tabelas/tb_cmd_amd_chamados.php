<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
require_once('../validar/class/class.chamado.php');
$objChamado = new Chamado();
$link = $objDb->conecta_mysql();
session_start();

?>
<div class="table-responsive">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead>
            <tr class="text-center BG-INFO elevation-1">
                <th>#</th>
                <th>SOLICITANTE</th>
                <th>ASSUNTO</th>
                <th>UNIDADE</th>
                <th>INICIO</th>
                <th>MENSAGEM</th>
                <th>STATUS</th>
                <th>AÇÃO</th>
        </thead>
        <tfoot>
            <tr class="text-center BG-INFO elevation-1">
                <th>#</th>
                <th>SOLICITANTE</th>
                <th>ASSUNTO</th>
                <th>UNIDADE</th>
                <th>INICIO</th>
                <th>MENSAGEM</th>
                <th>STATUS</th>
                <th>AÇÃO</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $sql = "SELECT c.id as chamado, uni.nome as unidade, c.data as inicio, sta.status as status, ass.assunto as assunto, u.nome as usu
                    FROM ch_chamado c 
                    INNER JOIN usuarios u 
                    ON c.usu = u.id 
                    INNER JOIN cad_unidade uni 
                    ON c.unidade = uni.id 
                    INNER JOIN ch_assunto ass 
                    ON c.assunto = ass.id 
                    INNER JOIN ch_status sta 
                    ON c.status = sta.id 
                    where sta.id in(1,20,40,45)";



            $sql = mysqli_query($link, $sql);



            while ($chamados = mysqli_fetch_array($sql)) {


               if(intval($objChamado->ultimaNotificacao($chamados['chamado'])) == 1){
                   $notificacao = "<div class='text-success' >Enviado</div>";
               }else{
                $notificacao = "<div class='text-danger' >Nova Mensagem<div>";
               }

            ?>
                    <tr class="text-center">
                        <td> <?php echo $chamados['chamado']; ?> </td>
                        <td> <?php echo $chamados['usu']; ?> </td>
                        <td> <?php echo $chamados['assunto']; ?> </td>
                        <td> <?php echo $chamados['unidade']; ?> </td>
                        <td> <?php echo date('d/m/Y', strtotime($chamados['inicio'])); ?> </td>
                        <td> <?php echo $notificacao; ?> </td>
                        <td> <?php echo $chamados['status']; ?> </td>
                        <td> <button data-toggle="tooltip" data-placement="top" title="Visualizar"
                        class="btn.btn-app-sm btn-light editar elevation-1"
                        value="<?php echo$chamados['chamado']; ?>">
                        <i class="fas fa-eye" style="font-size: 22px; color: #29088A"></i>

                    </button> </td>
                    </tr>
            <?php
                
            }
            ?>

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