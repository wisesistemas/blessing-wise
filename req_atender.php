<?php
session_start();
if( !isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade']) ){
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset( $_SESSION['itens_insert_estoque'] );
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;
require_once("validar/class/class.estoque.php");
$objEstoque = new Estoque();

if(intval($_GET['id']) == 0){/* Se for reimpressão de item em espera (pendente) não atualiza */
    $confirmarFolhaSeparacao = 0;
    $folha = 3;
}else{/* pendentes */
    $confirmarFolhaSeparacao = 1;
    $folha = 1;
}


?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>
    
    
    <?php require_once('head.php'); ?>
     <!-- TABELA -->
     <link href="plugins/tabela/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="plugins/tabela/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="plugins/tabela/css/style1.css" rel="stylesheet">
    <!-- FIM TABELA -->



    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
     <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    

    

    <script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 6000
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        <?php
            echo " $('.atender_pedido').addClass('menu-open');";
            if(intval($_GET['id']) == 0){/* Se for reimpressão de item em espera (pendente) não atualiza */
                $confirmarFolhaSeparacao = 0;
                echo "$('#em-espera').addClass('fas fa-circle nav-icon');";
            }else{
                $confirmarFolhaSeparacao = 1;
                echo "$('.$_GET[class]').addClass('fas fa-circle nav-icon');";
            }
        ?>
       
        /* $(".atender_pedido").addClass('menu-open');
         */

        
        carregarTabela();

       
        $(document).on('click', '.excluir', function() {
            var item = $(this).attr('id');
            $.ajax({
                url: "validar/validar.php?id=2",
                type: "POST",
                data: {
                    item: item
                },
                success: function(data) {
                    console.log(data);
                    $("#button").show();
                    getTabela();
                }
            });
        })

        $("#inserir").click(function() {
            var form = $("#form").serialize();
            $.ajax({
                url: "validar/validar.php?id=1",
                type: "POST",
                data: form,
                success: function(data) {
                    if (data == 1) {
                        $("#button").show();
                        getTabela();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Registro sem item: Por favor, selecione um item para o registro!'
                        })
                    }

                }
            });
        })

        $("#cancelar").click(function() {
            $.ajax({
                url: "validar/validar.php?id=2&prmt=2",
                success: function(data) {
                    $("#tb_itens").hide(data);
                    $("#button").hide();
                }
            });
        })

        

        function getTabela() {
            $.ajax({
                url: "tabelas/tb_itens_estoque.php",
                success: function(data) {
                    $("#tb_itens").html(data).show();
                }
            })
        }

        $("#loading1").hide();

       $(document).on('click','.folha_impressao', function(){
           var id = $(this).attr("value");
           $("#envia").text("Confirmar Impressão").val('2_'+id);
           $("#loading1").show();
           $("#envia_form").hide();
           $("#envia").show();
            $.ajax({
                url: "impressao/visualizar_impressão.php",
                type: "POST",
                data: {id: id, tipo: <?php  echo$folha;?>},
                success: function(data) {
                    $("#loading1").hide();
                    $("#modal-xl").modal("show");
                    $("#tb_itens").html(data);
                }
            })
       })

       $(document).on('click','.cancelar_requisicao', function(){
           var id = $(this).attr("value");
           $("#envia").text("Cancelar Requisição").val("1_"+id);
           $("#loading1").show();
           $("#envia_form").hide();
           $("#envia").show();
            $.ajax({
                url: "tabelas/tb_req_cancelar.php",
                type: "POST",
                data: {id: id, tipo: <?php echo$confirmarFolhaSeparacao;?>},
                success: function(data) {
                    $("#loading1").hide();
                    $("#modal-xl").modal("show");
                    $("#tb_itens").html(data);
                }
            })
       })

       $(document).on('click','.atender_requisicao', function(){
            var id = $(this).attr("value");
           $("#envia").text("Atender Requisição").val("3_"+id);
           $("#loading1").show();
           $("#envia_form").show();
           $("#envia").hide();
            $.ajax({
                url: "tabelas/tb_req_atender_itens.php",
                type: "POST",
                beforeSend: function(){
                   
                },
                data: {id: id, pendente: <?php echo$confirmarFolhaSeparacao; ?>},
                success: function(data) {
                    $("#loading1").hide();
                    $("#modal-xl").modal("show");
                    $("#tb_itens").html(data);
                }
            })
       })

       $(document).on('click','#envia', function(){
           var id = $(this).attr("value");
           var obs = $("#obs").val();
           $("#loading1").show();
            $.ajax({
                url: "validar/validar.php?id=4",
                type: "POST",
                beforeSend: function(){
                    $("#modal-xl").modal("hide");
                },
                data: {id: id, token: '<?php echo$token?>', obs: obs, confirmarImpressao: <?php echo$confirmarFolhaSeparacao; ?>},
                success: function(data) {
                    if( data == 1 ){
                        $("#loading1").hide();
                        Toast.fire({
                        icon: 'success',
                        title: 'WISE SISTEMAS: <br> Registros realizado com sucesso!!'
                        });
                        carregarTabela();
                    }
                }
            })
       })
     
     
       $("#formDialogOrdemServicoBuscaTopo").submit(function(){
            var obs = $("#obs").val();
            var req = $("#req").val();
            $("#loading1").show();
            $.ajax({
                url: "validar/validar.php?id=4",
                type: "POST",
                beforeSend: function(){
                    $("#modal-xl").modal("hide");
                },
                data: {req, id: "3_pedido=&familia=&genero=", form1: $("#formDialogOrdemServicoBuscaTopo").serialize(), token: '<?php echo$token?>', obs: obs},
                success: function(data) {
                    console.log(data);
                    $("#loading1").hide();
                    Toast.fire({
                        icon: 'success',
                        title: 'WISE SISTEMAS: <br> Registros realizado com sucesso!!'
                    });
                    carregarTabela();
                }
            })
            return false;
        });
        

       function carregarTabela(){
        $.ajax({
            url: "tabelas/tb_req_atender.php",
            type: "POST",
            data: {
                id: <?php echo$_GET['id'];?>
            },
            success: function(data) {
                $("#tb_atender_req").html(data);
            }
        });
       }

    
        
    });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed text-sm">


    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando itens...</div>
        </div>

        <?php require_once('menu_superior.php'); ?>
        <?php require_once('menu_lataral.php'); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid elevation">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><strong>Atender Solicitação:</strong>
                                </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Atender Solicitação:</strong></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
         
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid ">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default elevation-1">
                        <div class="card-header ">
                            <h3 class="card-title">Atender solicitação:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>

                            </div>
                        </div>

<!-- /.card-header -->
    <div class="col-sm-12 my-2">
        <div id="tb_atender_req"></div>
    </div>


    <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
    </div>
                        <!-- ./wrapper -->
                         <!-- Large modal -->
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">WISE - SISTEMA</h4>
        <button type="button" class="close elevation-1" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
    <form id="formDialogOrdemServicoBuscaTopo">
        <div id="tb_itens"></div>

    </div>
    <div class="modal-footer justify-content-between" >
        <button type="button" class="btn btn-default elevation-1" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-danger elevation-1" id="envia_form" value="">Enviar Requisição</button>
    </form>
        <button type="button" class="btn btn-danger elevation-1" id="envia" value=""></button>
    </div>
</div>
     </div>
</div>
<!-- fim modal -->

                        <?php require_once('footer.php'); ?>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
                        
</body>

</html>