<?php
session_start();
if( !isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade']) ){
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;



$sqlUnidade = "SELECT * FROM cad_unidade";
$sqlUnidade = mysqli_query($link, $sqlUnidade);
//--Query assunto
$sqlAssunto = "SELECT * FROM `ch_assunto` where sistema = 1 ORDER BY `ch_assunto`.`assunto` ASC";
$sqlAssunto = mysqli_query($link, $sqlAssunto);

$validador = md5("wise123456");
$_SESSION['token_cad_funcionario'] = $validador;
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>


    <?php require_once('head.php'); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>
    <?php require_once('head.php'); ?>
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- TABELA -->
    <link href="plugins/tabela/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="plugins/tabela/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="plugins/tabela/css/style1.css" rel="stylesheet">
    <!-- FIM TABELA -->
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- mask -->
    <script src="plugins/mask/jquery.mask.js"></script>




    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 6000
            });

            $(".cmd_chamado").addClass('menu-open');
            $(".cmd_chamado_adm").addClass('menu-open');
            $("#cmd_adm_todos_chamados").addClass('fas fa-circle nav-icon');

            $("#contato").mask("(00) 0-0000-0000");

            carregarTabela();
            resetSelect('novo');

            function resetSelect(_nome){
                $('.chosen-select').val('').select2({
                    placeholder: _nome,
    allowClear: true
                });
            }

            $("#cad_item").click(function() {
                
                $('#modal_cad_item').modal("show");
                $(".select2-selection__rendered").text('...');
                $("#nome").val('');
                $("#contato").val('');
                $("#descricao").val('');
                resetSelect('...');
                
            });

            function carregarTabela() {
                $.ajax({
                    url: "tabelas/tb_cmd_amd_todos_chamados.php",
                    type: "POST",
                    success: function(data) {
                        $("#tb_cad_item").html(data);
                        $("#loading1").hide();
                    }
                });
            }



            $(document).on('click', '.editar', function() {
                var chamado = $(this).attr('value');
                $('#modal_vw_chamado').modal("show");
                $.ajax({
                    url: "tabelas/tb_cmd_adm_visualizar.php",
                    type: "POST",
                    data: {
                        chamado: chamado
                    },
                    success: function(data) {
                        console.log(data);
                        $("#chamado").html(data);
                    }
                });
            })

            $(document).on('click', '.envia', function(event) {
                var form = $('#form_atual')[0];
                var file_atual = new FormData(form);
                event.preventDefault();
                $.ajax({
                    url: "validar/validar.php?id=27&tipo=1",
                    type: "POST",
                    processData: false,
                        contentType: false,
                        cache: false,
       
                    data:
                        file_atual
                      
                    ,    
                    success: function(data) {
                        console.log(data);
                        $('#modal_vw_chamado').modal("hide");
                        if( data == "erro_menor_2"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: Quantidade de Caracteres Insuficiente!!'
                            })
                        }else
                        if(data == "erro1"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: extensão de Arquivo Não Permitido!!'
                            })
                        }else
                        if(data == "erro2"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: Tamanho do Arquivo Não Permitido!!'
                            })
                        }else
                        if(data == 1){
                            Toast.fire({
                                icon: 'success',
                                title: 'Registro Atualizado!!'
                            })
                        }
                        carregarTabela();
                    }
                });
            })

            $(document).on('click', '.finalizar', function(event) {
                var form = $('#form_atual')[0];
                var file_atual = new FormData(form);
                event.preventDefault();
                $.ajax({
                    url: "validar/validar.php?id=27&tipo=0",
                    type: "POST",
                    processData: false,
                        contentType: false,
                        cache: false,
       
                    data:
                        file_atual
                      
                    ,    
                    success: function(data) {
                        console.log(data);
                        $('#modal_vw_chamado').modal("hide");
                        if( data == "erro_menor_2"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: Quantidade de Caracteres Insuficiente!!'
                            })
                        }else
                        if(data == "erro1"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: extensão de Arquivo Não Permitido!!'
                            })
                        }else
                        if(data == "erro2"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: Tamanho do Arquivo Não Permitido!!'
                            })
                        }else
                        if(data == 1){
                            Toast.fire({
                                icon: 'success',
                                title: 'Registro Atualizado!!'
                            })
                        }
                        carregarTabela();
                    }
                });
            })
            

            $('#form').validate({
                rules: {
                    nome: {
                        required: true,
                        maxlength: 20,
                    },
                    contato: {
                        required: true,
                        minlength: 16,
                    },
                    unidade: {
                        required: true
                    },
                    assunto: {
                        required: true
                    },
                    descricao: {
                        required: true,
                        maxlength: 500,
                        minlength: 10
                    },
                },
                messages: {
                    nome: {
                        required: "Campo Obrigatório!",
                        maxlength: "Máximo Caracteres Permitido: 20!",
                    },
                    contato: {
                        required: "Campo Obrigatório!",
                        minlength: "Número Inválido!",
                    },
                    unidade: {
                        required: "Campo Obrigatório!"
                    },
                    assunto: {
                        required: "Campo Obrigatório!"
                    },
                    descricao: {
                        required: "Campo Obrigatório!",
                        maxlength: "Máximo Caracteres Permitido: 500!",
                        minlength: "Mínimo Caracteres Permitido: 10!"
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    var form = $('#form')[0];
                    var data = new FormData(form);
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "validar/validar.php?id=26",
                        beforeSend: function() {

                        },
                        complete: function() {

                        },
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            console.log(data);
                            $('#modal_cad_item').modal("hide");
                            if( data == "erro_menor_2"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: Quantidade de Caracteres Insuficiente!!'
                            })
                        }else
                        if(data == "erro1"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: extensão de Arquivo Não Permitido!!'
                            })
                        }else
                        if(data == "erro2"){
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização Inválida: Tamanho do Arquivo Não Permitido!!'
                            })
                        }else
                        if(data > 0){
                            Toast.fire({
                                icon: 'success',
                                title: 'Chamado Realizado com Sucesso, Sob Número: '+data
                            })
                        }
                        carregarTabela();

                        }
                    });
                }
            });

        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">


    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando dados...</div>
        </div>

        <?php require_once('menu_superior.php'); ?>
        <?php require_once('menu_lataral.php'); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><strong>Gerenciamento de Chamado - TI:</strong>
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Gerenciamento de Chamado - TI:</strong></li>
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
                        <div class="card-header">
                            <h3 class="card-title">Gerenciamento de Chamado - TI:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                            </div>
                        </div>
                        <div class="container col-12 text-right my-2 ">
                            <a class="btn btn-app" id="cad_item">
                                <i class="fas fa-plus-square"></i> Novo
                            </a>
                        </div>
                        
                        <!-- /.card-header -->
                        <div class="col-md-12">
                            <div id="tb_cad_item"></div>
                        </div>


                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
                        <!-- ./wrapper -->


                        <?php require_once('footer.php'); ?>
                        <!-- Toastr -->
                        <script src="plugins/toastr/toastr.min.js"></script>


                        <!-- Large modal  AVATAR-->
                        <div class="modal fade" id="modal_cad_item">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">WISE - SISEMAS</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">



                                        <div class="callout callout-info ">
                                            <label style="font-size: 18px;">Novo Chamado:</label>
                                            <form id="form">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="nome">Responsavel Pelo Chamado</label>
                                                        <input type="text" name="nome" class="form-control form-control-ms" id="nome">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="contato">Contato</label>
                                                        <input type="text" name="contato" class="form-control form-control-ms" id="contato">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="unidade">Unidade</label>
                                                        <select name="unidade" class="chosen-select form-control" tabindex="3" id="unidade">
                                                            <option value="" id="empresa_select">...</option>
                                                            <?php while ($unidade = mysqli_fetch_array($sqlUnidade)) { ?>
                                                                <option value="<?php echo $unidade['id'] ?>"> <?php echo $unidade['nome'] ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="files">Escolha o Arquivo com extensão <strong>JPG</strong> ou <strong>PNG</strong>.</label>
                                                        <input type="file" name="files" class="form-control-file" id="files" accept="image/*" capture="camera">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="assunto">Assunto</label>
                                                        <select name="assunto" class="chosen-select form-control " tabindex="3" id="assunto">
                                                            <option value="">...</option>
                                                            <?php while ($assunto = mysqli_fetch_array($sqlAssunto)) { ?>
                                                                <option value="<?php echo $assunto['id'] ?>"> <?php echo $assunto['assunto'] ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="descricao">Assunto</label>
                                                        <textarea class="form-control" placeholder="Descreva o Motivo do Chamado" id="descricao" name="descricao" rows="6"></textarea>
                                                    </div>


                                                </div>
                                                <div class="form-row label-form">
                                                    <div class="col-12 text-right">

                                                        <button type="submit" class="btn btn-app text-success" id="btn-salvar" style="display: show">
                                                            <i class="fas fa-plus-square"></i>
                                                            Salvar
                                                        </button>

                                                        <a class="btn btn-app" id="btn-salvando" style="display: none">
                                                            <div class="spinner-border text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div><br>Salvando...
                                                        </a>

                                                    </div>
                                                </div>
                                                <input type="hidden" name="validador" value="<?php echo $validador; ?>">
                                                <input type="hidden" name="tipo" value="2">

                                            </form>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                </div>
                            </div>
                        </div>
                        <!-- FIM Large modal  AVATAR-->

                        <!-- Large modal  AVATAR-->
                        <div class="modal fade" id="modal_vw_chamado">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h4 class="modal-title">WISE - SISEMAS</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <div id="chamado"></div>
                                    </div>
                                    <!-- /.modal -->
                                    <div class="card-footer text-right text-info" style="display: block;">
                                        <small>WISE - SISTEMAS</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIM Large modal  AVATAR-->


                        <!-- Select2 -->
                        <script src="plugins/select2/js/select2.full.min.js"></script>
                        <!-- jquery-validation -->
                        <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
                        <script src="plugins/jquery-validation/additional-methods.min.js"></script>
                        <!-- mask -->
                        <script src="plugins/mask/jquery.mask.js"></script>
                        <script>

                        </script>

</body>

</html>