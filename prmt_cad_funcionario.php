<?php
session_start();
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;

$sqlEmpresa = "SELECT * FROM cad_empresa";
$sqlEmpresa = mysqli_query($link, $sqlEmpresa);

$sqlUnidade = "SELECT * FROM cad_unidade";
$sqlUnidade = mysqli_query($link, $sqlUnidade);

$sqlFuncao = "SELECT * FROM cad_funcao";
$sqlFuncao = mysqli_query($link, $sqlFuncao);

$sqlEscala = "SELECT * FROM cad_escala";
$sqlEscala = mysqli_query($link, $sqlEscala);

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

            $(".parametrizacao").addClass('menu-open');
            $(".cadastros_rd_do").addClass('menu-open');
            $("#prmt_cad_funcionario").addClass('fas fa-circle nav-icon');

            $("#cpf").mask("000.000.000-00");

            carregarTabela();

            

            $("#cad_item").click(function() {
                $("#form")[0].reset();
                /* $('#empresa').val('8124'); */
                $("#senha").val('').attr('required', true );
                $("#form").show();
                $("#btn-salvar").show();
                $("#btn-salvando").hide();
                $("#id_item").val('');
                $("#nome").val('');
                $("#cpf").val('');
                $("#pis").val('');
                $("#matricula").val('');
                $("#nascimento").val('');
                /* $("#empresa").val(''); */
                $("#unidade").val('');
                $("#funcao").val('');
                $("#escala").val('');
                $("#banco").val('');
                $("#agencia").val('');
                $("#conta").val('');
                $("#digito").val('');
                $("#ativo").prop('checked', true);
                $('#modal_cad_item').modal("show");
                
            });

            function carregarTabela() {
                $.ajax({
                    url: "tabelas/tb_cad_funcionario.php",
                    type: "POST",
                    success: function(data) {
                        $("#tb_cad_item").html(data);
                        $("#loading1").hide();
                    }
                });
            }



            $(document).on('click', '.editar', function() {
                $("#senha").val('').attr('required', false );
                $("#btn-salvar").show();
                $("#btn-salvando").hide();
                $("#form").show();
                var id = $(this).attr('value').split('*');
                $("#id_item").val(id[0]);
                $("#nome").val(id[1]);
                $("#cpf").val(id[2]);
                $("#pis").val(id[3]);
                $("#matricula").val(id[4]);
                $("#nascimento").val(id[5]);
                $("#empresa").val(id[6]);
                $("#unidade").val(id[7]);
                $("#funcao").val(id[8]);
                $("#escala").val(id[9]);
                $("#banco").val(id[10]);
                $("#agencia").val(id[11]);
                $("#conta").val(id[12]);
                $("#digito").val(id[13]);
               /*  $('#empresa').val('8124'); */
                if (id[14] == 1) {
                    $("#ativo").prop('checked', true);
                } else {
                    $("#ativo").prop('checked', false);
                }
                
                $('#modal_cad_item').modal("show");
            })


            $('#form').validate({
                rules: {
                    nome: {
                       required: true
                    },
                    cpf: {
                       required: true
                    },
                    matricula: {
                       required: true
                    },
                    nascimento: {
                       required: true
                    },
                    empresa: {
                       required: true
                    },
                    unidade: {
                       required: true
                    },
                    funcao: {
                       required: true
                    },
                    escala: {
                       required: true
                    },
                   
                    banco: {
                       required: true
                    },
                    agencia: {
                        required: true,
                        maxlength: 4,
                        minlength: 1
                    },
                    conta: {
                        required: true,
                        maxlength: 10,
                        minlength: 1
                    },
                    digito: {
                        required: true,
                        maxlength: 1,
                        minlength: 1
                    },
                },
                messages: {
                    nome: {
                       required: "Campo Obrigatório!"
                    },
                    cpf: {
                        required: "Campo Obrigatório!"
                    },
                    matricula: {
                        required: "Campo Obrigatório!"
                    },
                    nascimento: {
                        required: "Campo Obrigatório!"
                    },
                    empresa: {
                        required: "Campo Obrigatório!"
                    },
                    unidade: {
                        required: "Campo Obrigatório!"
                    },
                    funcao: {
                        required: "Campo Obrigatório!"
                    },
                    escala: {
                        required: "Campo Obrigatório!"
                    },
                    senha: {
                        required: "Campo Obrigatório!",
                        maxlength: "Máximo Caracter Permitido: 10",
                        minlength: 'Mínimo Caracter Permitido: 6'
                    },
                    banco: {
                        required: "Campo Obrigatório!"
                    },
                    agencia: {
                        required: "Campo Obrigatório!",
                        maxlength: "Máximo Caracter Permitido: 4",
                        minlength: 'Mínimo Caracter Permitido: 1'
                    },
                    conta: {
                        required: "Campo Obrigatório!",
                        maxlength: "Máximo Caracter Permitido: 10",
                        minlength: 'Mínimo Caracter Permitido: 1'
                    },
                    digito: {
                        required: "Campo Obrigatório!",
                        maxlength: "Máximo Caracter Permitido: 1",
                        minlength: 'Mínimo Caracter Permitido: 1'
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
                    $("#loading1").show();
                    $.ajax({
                        url: "validar/validar.php?id=25",
                        type: "POST",
                        beforeSend: function() {
                            $("#form").hide();
                            
                        },
                        data: form,
                        success: function(data) {
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
                        <h1 class="m-0 text-dark"><strong>Cadastro de Funcionario:</strong>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Cadastro de Funcionario:</strong></li>
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
                        <h3 class="card-title">Cadastro de Funcionario:</h3>

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



                                    <div class="callout callout-info">
                                        <label style="font-size: 18px;">Cadastro de Funcionario:</label>
                                        <form id="form">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="nome">Nome</label>
                                                    <input type="text" name="nome" class="form-control form-control-ms" id="nome" >
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="cpf">CPF</label>
                                                    <input type="text" name="cpf" class="form-control form-control-ms" id="cpf" >
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="pis">PIS</label>
                                                    <input type="text" name="pis" class="form-control form-control-ms" id="pis">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="matricula">Matricula</label>
                                                    <input type="text" name="matricula" class="form-control form-control-ms" id="matricula">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="nascimento">Nascimento</label>
                                                    <input type="date" name="nascimento" class="form-control form-control-ms" id="nascimento">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="empresa">Empresa</label>
                                                    <select name="empresa" class="chosen-select_empresa form-control" tabindex="3" id="empresa" >
                                                        <option value="" id="empresa_select">...</option>
                                                        <?php while ($empresa = mysqli_fetch_array($sqlEmpresa)) { ?>
                                                            <option value="<?php echo$empresa['codigo'] ?>"> <?php echo$empresa['empresa'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="unidade">Unidade</label>
                                                    <select name="unidade" class="chosen-select form-control " tabindex="3" id="unidade" >
                                                        <option value="">...</option>
                                                        <?php while ($unidade = mysqli_fetch_array($sqlUnidade)) { ?>
                                                            <option value="<?php echo$unidade['id'] ?>"> <?php echo$unidade['nome'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="funcao">Função</label>
                                                    <select name="funcao" class="chosen-select  form-control " tabindex="3" id="funcao" >
                                                        <option value="">...</option>
                                                        <?php while ($funcao = mysqli_fetch_array($sqlFuncao)) { ?>
                                                            <option value="<?php echo$funcao['id'] ?>"> <?php echo$funcao['funcao'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="escala">Escala</label>
                                                    <select name="escala" class="chosen-select form-control  " tabindex="3" id="escala" >
                                                        <option value="">...</option>
                                                        <?php while ($escala = mysqli_fetch_array($sqlEscala)) { ?>
                                                            <option value="<?php echo$escala['id'] ?>"> <?php echo$escala['escala'].' - '.$escala['tipo']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">

                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="banco">Banco</label>
                                                    <select name="banco" class="chosen-select  form-control " tabindex="3" id="banco" >
                                                        <option value="">...</option>
                                                        <option value="1">Banco Do Brasil S.A</option>
                                                        <option value="33">Banco Santander Brasil S.A</option>
                                                        <option value="104">Caixa Econômica Federal</option>
                                                        <option value="237">Bradesco S.A</option>
                                                        <option value="341">Itaú Unibanco S.A</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="agencia">Agencia</label>
                                                    <input type="number" name="agencia" class="form-control form-control-ms" id="agencia">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="conta">Conta</label>
                                                    <input type="number" name="conta" class="form-control form-control-ms" id="conta">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="digito">Digito</label>
                                                    <input type="number" name="digito" class="form-control form-control-ms" id="digito">
                                                </div>
                                                <div class="form-group col-md-2">

                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="senha">Senha</label>
                                                    <input type="text" name="senha" value=""  class="form-control form-control-ms" id="senha" >
                                                </div>
                                                <div class="form-group col-md-12 text-right ">
                                                    <div class="custom-control custom-checkbox my-2">
                                                        <input type="checkbox" class="custom-control-input" id="ativo" name="ativo">
                                                        <label class="custom-control-label" for="ativo">Funcionario Ativo</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-row label-form">
                                                <div class="col-12 text-right">

                                                    <button type="submit" class="btn btn-app text-success"  id="btn-salvar" style="display: show">
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
                                                <input type="hidden" name="id_item" id="id_item">
                                        </form>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
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
                            

                                /* $('#empresa').val('8124').select2(); */
                            </script>
</body>

</html>