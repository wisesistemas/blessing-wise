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

$sqlFamilia = "SELECT * FROM cad_Familia";
$sqlFamilia = mysqli_query($link, $sqlFamilia);
$sqlGenero = "SELECT * FROM cad_genero";
$sqlGenero = mysqli_query($link, $sqlGenero);
$sqlarmazenamento = "SELECT * FROM cad_armazenamento";
$sqlarmazenamento = mysqli_query($link, $sqlarmazenamento);
$sqlMedida = "SELECT * FROM cad_medida";
$sqlMedida = mysqli_query($link, $sqlMedida);
$sqlMedidaCompra = "SELECT * FROM cad_medida";
$sqlMedidaCompra = mysqli_query($link, $sqlMedidaCompra);

$validador = md5("wise123456");
$_SESSION['token_cad_item'] = $validador;
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
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
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





    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 6000
            });

            $(".parametrizacao").addClass('menu-open');
            $(".cadastros").addClass('menu-open');
            $("#prmt_cad_item").addClass('fas fa-circle nav-icon');

             carregarTabela();

            $("#loading1").hide();

            $("#cad_item").click(function() {
                $("#form").show();
                $("#btn-salvar").show();
                $("#btn-salvando").hide();
                $("#id_item").val('NULL');
                $("#id_item").val('');
                $("#nome").val('');
                $("#ref").val('');
                $("#modelo").val('');
                $("#familia").val('');
                $("#genero").val('');
                $("#marca").val('');
                $("#uni_campra").val('');
                $("#uni_estoque").val('');
                $("#control_validade").val('');
                $("#armazenamento").val('');
                $("#solicitar_ref").val('');
                $('#modal_cad_item').modal("show")
            });

            function carregarTabela() {
                $.ajax({
                    url: "tabelas/tb_cad_item.php",
                    type: "POST",
                    success: function(data) {
                        $("#tb_cad_item").html(data);
                    }
                });
            }
           
      

            $(document).on('click','.editar', function(){
                $("#btn-salvar").show();
                $("#btn-salvando").hide();
                $("#form").show();
                var id = $(this).attr('value').split('*');
                $("#id_item").val(id[0]);
                $("#nome").val(id[1]);
                $("#ref").val(id[2]);
                $("#modelo").val(id[3]);
                $("#familia").val(id[4]);
                $("#genero").val(id[5]);
                $("#marca").val(id[6]);
                $("#uni_campra").val(id[7]);
                $("#uni_estoque").val(id[8]);
                $("#control_validade").val(id[9]);
                $("#armazenamento").val(id[10]);
                $("#solicitar_ref").val(id[11]);
                if( id[12] == 1){
                    $("#ativo").prop('checked', true);
                }else{
                    $("#ativo").prop('checked', false);
                }
                $('#modal_cad_item').modal("show")
            })


            $('#form').validate({
                rules: {
                    nome: {
                        required: true,
                    },

                },
                messages: {
                    nome: {
                        required: "Obrigatório",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    var form = $(form).serialize();
                    $("#btn-salvar").hide();
                    $("#btn-salvando").show();
                    $.ajax({
                        url: "validar/validar.php?id=18",
                        type: "POST",
                        beforeSend: function(){
                            $("#form").hide();
                        },
                        data: form,
                        success: function(data){
                            console.log(data);
                            $('#modal_cad_item').modal("hide");
                            carregarTabela();
                            Toast.fire({
                                icon: 'success',
                                title: 'Registro realizado com sucesso!!'
                            })
                        }
                    })
                }
            });

        });
    </script>
    <style>
        .form-control {
            height: 24px;
            font-size: 14px;
        }

        label {
            margin-bottom: 0px;
        }

        .label-form {
            padding-top: 15px;
        }

        .select-form {
            padding-bottom: 1px;
            padding-top: 1px;
        }
    </style>
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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><strong>Consultar requisição:</strong>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Consultar requisição:</strong></li>
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
                        <h3 class="card-title">Consultar requisição:</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                        </div>
                    </div>
                    <div class="container col-12 text-right my-2 ">
                        <a class="btn btn-app" id="cad_item">
                            <i class="fas fa-plus-square"></i> Novo Item
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div id="tb_cad_item"></div>


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



                                    <div class="callout callout-info">
                                        <label style="font-size: 18px;">Cadastro de item:</label>
                                        <form id="form">
                                            <div class="form-row label-form">
                                                <div class="col-6">
                                                    <label for="nome">Nome:</label>
                                                    <input type="text" class="form-control form-control-sm col-12" placeholder="Nome item" name="nome" id="nome" required>
                                                </div>
                                                <div class="col-3">
                                                    <label for="ref">Referencia:</label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Referencia" name="ref" id="ref">
                                                </div>
                                                <div class="col-3">
                                                    <label for="modelo">Modelo:</label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Modelo" name="modelo" id="modelo">
                                                </div>
                                            </div>
                                            <div class="form-row label-form">
                                                <div class="col-3">
                                                    <label for="familia">Familia:</label>
                                                    <select class="form-control form-control-sm select-form" name="familia" id="familia">
                                                        <option value="">...</option>
                                                        <?php while ($familia = mysqli_fetch_array($sqlFamilia)) { ?>
                                                            <option value="<?php echo$familia['id']; ?>"><?php echo $familia['familia']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <label for="genero">Genero:</label>
                                                    <select class="form-control form-control-sm select-form" name="genero" id="genero">
                                                        <option value="">...</option>
                                                        <?php while ($genero = mysqli_fetch_array($sqlGenero)) { ?>
                                                            <option value="<?php echo$genero['id']; ?>"><?php echo $genero['genero']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label for="marca">Marca:</label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Marca" name="marca" id="marca">
                                                </div>
                                            </div>
                                            <div class="form-row label-form">
                                                <div class="col-3">
                                                    <label for="uni_estoque">UNI Estoque:</label>
                                                    <select class="form-control form-control-sm select-form" name="uni_estoque" id="uni_estoque">
                                                        <option value="">...</option>
                                                        <?php while ($medida = mysqli_fetch_array($sqlMedida)) { ?>
                                                            <option value="<?php echo$medida['id']; ?>"><?php echo $medida['descricao']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <label for="uni_campra">UNI Compra:</label>
                                                    <select class="form-control form-control-sm select-form" name="uni_campra" id="uni_campra">
                                                        <option value="">...</option>
                                                        <?php while ($medidaCompra = mysqli_fetch_array($sqlMedidaCompra)) { ?>
                                                            <option value="<?php echo$medidaCompra['id']; ?>"><?php echo $medidaCompra['descricao']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <label for="control_validade">Controle Validade:</label>
                                                    <select class="form-control form-control-sm select-form" name="control_validade" id="control_validade">
                                                        <option value="">...</option>
                                                        <option value="1">Sim</option>
                                                        <option value="0">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row label-form">
                                                <div class="col-3">
                                                    <label for="armazenamento">Armazenamento:</label>
                                                    <select class="form-control form-control-sm select-form" name="armazenamento" id="armazenamento">
                                                        <option value="">...</option>
                                                        <?php while ($armazenamento = mysqli_fetch_array($sqlarmazenamento)) { ?>
                                                            <option value="<?php echo$armazenamento['nome']; ?>"><?php echo $armazenamento['nome']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <label for="solicitar_ref">Referencia obrigatória:</label>
                                                    <select class="form-control form-control-sm select-form" name="solicitar_ref" id="solicitar_ref">
                                                        <option value="">...</option>
                                                        <option value="1">Sim</option>
                                                        <option value="0">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row label-form ">
                                                <div class="custom-control custom-checkbox col-12 text-right">
                                                    <input type="checkbox" class="custom-control-input" id="ativo" name="ativo">
                                                    <label class="custom-control-label" for="ativo" style="font-size: 14px;">Ativo</label>
                                                </div>
                                            </div>


                                    </div>
                                    <div class="form-row label-form">
                                        <div class="col-12 text-right">

                                            <button type="submit" class="btn btn-app"  id="btn-salvar" style="display: show">
                                                <i class="fas fa-plus-square"></i>
                                                 Salvar
                                            </button>

                                            <a class="btn btn-app"  id="btn-salvando" style="display: none">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div><br>Salvando...
                                            </a>
                                           
                                        </div>
                                    </div>
                                    <input type="hidden" name="validador" value="<?php echo$validador;?>">
                                    <input type="hidden" name="id_item" id="id_item">
                                    </form>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                        </div>
                        <!-- FIM Large modal  AVATAR-->
                        <!-- jquery-validation -->
                        <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
                        <script src="plugins/jquery-validation/additional-methods.min.js"></script>

</body>

</html>