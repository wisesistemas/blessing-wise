<?php
session_start();
if (!isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade'])) {
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;

$sqlCompetencia = "SELECT * FROM `custo_competencia`  where ativo = 1 ORDER BY id";
$sqlCompetencia = mysqli_query($link, $sqlCompetencia);

$sqlFornecedor = "SELECT * FROM `cad_fornecedor` WHERE ativo = 1";
$sqlFornecedor = mysqli_query($link, $sqlFornecedor);

$sqlCategoria = "SELECT * FROM `plano_contas` WHERE ativo = 1";
$sqlCategoria = mysqli_query($link, $sqlCategoria);

?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>
    <?php require_once('head.php'); ?>
    <script src="https://rawgit.com/BankFacil/vanilla-masker/master/lib/vanilla-masker.js"></script>
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
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 6000
            });

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })
            var date_regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/;

            function maskDinheiro() {
                var dinheiro = document.getElementsByClassName("valor");
                VMasker(dinheiro).maskMoney({
                    precision: 2,
                    separator: ',',
                    delimiter: '.',
                    unit: 'R$',
                    zeroCents: false
                });
            };

            maskDinheiro();

            if ((date_regex.test('12-12-2019'))) {
                var num = 1;
            } else {
                var num = 0;
            }

            console.log(num);

            $('.auto').keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });

               $(".financeiro").addClass('menu-open');
               $("#fin_conta_varial").addClass('fas fa-circle nav-icon');

            $("#loading1").hide();

            $("#form").validate({
                rules: {
                    inicio: {
                        required: true,
                    }
                }, //RULES
                messages: {
                    inicio: {
                        required: "Requerido...",
                    }
                }, //MESSAGES
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {

                    var form = $('#form')[0];
                    var data = new FormData(form);
                    $("#loading1").show();
                    $("#form").hide();
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "validar/validar.php?id=34",
                        beforeSend: function() {

                        },
                        complete: function() {
                            $("#loading1").hide();
                        },
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            console.log(data);
                            $("#novo").show();
                        } /* FIM função success */
                    });
                   
                },
            });


            maskDinheiro();

            if ((date_regex.test('12-12-2019'))) {
                var num = 1;
            } else {
                var num = 0;
            }

            console.log(num);

            $('.auto').keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });


            $("#adicionar").click(function() {

                AddTableRow();
            });

            AddTableRow = function(valorDasParcelas, dataParcelas) {
                var newRow = $("<tr class='tr' >");
                var cols = '';
                cols += '<th> <input class="form-control form-control-sm as auto valid" type="date" name="vencimento[]" value="' + dataParcelas + '" required="" aria-invalid="false"> </th>';
                cols += '<td> <input class="form-control valor form-control-sm as somar auto valid" type="text" value="' + valorDasParcelas + '" name="valor[]"  required> </td>';
                cols += '<td> <button class="btn btn-danger btn-sm remover" onclick="RemoveTableRow(this)" type="button">Remover</button> </td>';
                cols += '</tr>';
                newRow.append(cols);
                $("#table").append(newRow);

                maskDinheiro();



                return false;
            };

            RemoveTableRow = function(handler) {
                var tr = $(handler).closest('tr');

                tr.fadeOut(400, function() {
                    tr.remove();
                });

                return false;
            };

            $(document).on('blur', '.somar', function() {
                var total = 0;
                var totalDeCompos = $(".somar").length;
                console.log("Valor de total: " + total);
                for (i = 0; i < totalDeCompos; i++) {
                    //var valor =  parseFloat( $('.somar')[i].value );
                    var valor = $('.somar')[i].value.replace('R$ ', '').replace('.', '').replace(',', '');
                    var m = Math.floor(valor.length - 2);
                    var r = valor.substr(0, m).replace('.', '') + "." + valor.substr(m);

                    total = total + parseFloat(r);

                    console.log(parseFloat(r));

                }
                $("#total").val(total.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }));
            })



            $("#gerar_parcela").click(function() {

                dataAtual = new Date();
                var parcelas = Math.ceil($("#qtd_parcela").val());
                var valorParcelas = $("#valor_parcela").val();

                var valor = valorParcelas.replace('R$ ', '').replace('.', '').replace(',', '.');
                var total = valor * parcelas;
                $("#total").val(total.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }));


                var vencimento = ("00" + $("#vencimento").val()).slice(-2);
                var ifMes29 = 0;

                var valorDasParcelas = valorParcelas;
                var anoAtual = dataAtual.getFullYear();
                if (vencimento == 31) {
                    var mesAtual = dataAtual.getMonth() - 1;
                } else {
                    var mesAtual = dataAtual.getMonth();
                }

                //var totalDasParcelas = valorParcelas * parcelas;
                //$("#total").val( totalDasParcelas.toFixed(2) );

                ultimoDiaMes = function(year, month) {
                    var ultimoDia = (new Date(year, month, 0)).getDate();
                    return ultimoDia;
                }

                $("#table tr").remove();

                data = new Date(anoAtual, mesAtual, vencimento);
                var ifMes02 = 0;
                for (let cont = 0; cont < parcelas; cont++) {
                    

                    var dataParcelasSplit = data.toLocaleDateString('zh-Hans-CN').split('/');
                    var anoParcelas = dataParcelasSplit[0];
                    var mesParcelas = ("00" + dataParcelasSplit[1]).slice(-2);
                    var diaParcelas = ("00" + dataParcelasSplit[2]).slice(-2);
                    var ultimoDiaDoMes = ultimoDiaMes(anoParcelas, mesParcelas);

                    var ultimoDiaJaneiro = ultimoDiaMes(anoParcelas, 02);



                    if (vencimento >= ultimoDiaDoMes) {
                        var dataParcelas = anoParcelas + '-' + mesParcelas + '-' + ultimoDiaDoMes;
                    } else {
                        var dataParcelas = anoParcelas + '-' + mesParcelas + '-' + vencimento;
                    }

                    AddTableRow(valorDasParcelas, dataParcelas);




                    if (vencimento == 30 && mesParcelas == 01 && ifMes02 == 0) {
                        ifMes02 = 1;
                        cont++;
                        if (cont != parcelas) {
                            var ultimoDia30 = ultimoDiaMes(anoParcelas, 02);
                            var dataParcelas1 = anoParcelas + '-02-' + ultimoDia30;
                            AddTableRow(valorDasParcelas, dataParcelas1);
                        }
                    }



                    if (ultimoDiaJaneiro == 28 && vencimento == 29 && ifMes29 == 0 && anoParcelas > 2019) {
                        ifMes29 = 1;
                        cont++;
                        if (cont != parcelas) {

                            var dataParcelas1 = anoParcelas + '-02-28';
                            AddTableRow(valorDasParcelas, dataParcelas1);
                        }
                    }
                    data.setMonth(data.getMonth() + 1);
                }

            })

            $("#novo").click(function() {
                location.reload();
            })

            $("#novoCad").click(function() {
                $("#table tr").remove();
                $('#form select').val("");
                $('#form input').val("");
                $('#form textarea').val("");
                $("#sucesso").hide();
                $("#erro").hide();
                $("#painel").show();
                $("#form").show();
            })
        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">

    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando...</div>
        </div>

        <?php require_once('menu_superior.php'); ?>
        <?php require_once('menu_lataral.php'); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper ">
            <!-- Content Header (Page header) -->
            <div class="content-header ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Cadastro de Conta Variável</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Cadastro de Conta Variável</strong></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content ">
                <div class="container-fluid ">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default elevation-1">
                        <div class="card-header ">
                            <h3 class="card-title">Cadastro de Conta Variável:</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <!-- CORPO -->
                        <div class="container">
                            <div class="col-md-12 text-right" style="display: none;" id="novo">
                                <a class="btn btn-app my-1">
                                    <i class="fas fa-plus"></i> Novo
                                </a>
                            </div>
                            <form id="form">

                                <div class="row">

                                    <!-- area de campos do form -->
                                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] = base64_encode(rand()) . '/' . md5(rand()); ?>">

                                    <div class="form-group col-md-6">
                                        <label for="funcao_extra">Competencia</label>
                                        <select class="form-control chosen-select" name="competencia">
                                            <option value="">...</option>
                                            <?php
                                            while ($competencia = mysqli_fetch_array($sqlCompetencia)) {
                                            ?>
                                                <option value="<?php echo $competencia['id']; ?>"><?php echo $competencia['mes'] . '/' . $competencia['ano'];  ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="funcao_extra">Nº Doc</label>
                                        <input type="text" class="form-control form-control-sm" id="num_doc" name="num_doc" value="">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="funcao_extra">Valor Total </label>
                                        <input type="text" class="form-control form-control-sm" id="total" value="" readonly>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="funcao_extra">Fornecedor</label>
                                        <select class="form-control chosen-select" name="fornecedor" required>
                                            <option value="">...</option>
                                            <?php
                                            while ($forncedor = mysqli_fetch_array($sqlFornecedor)) {
                                            ?>
                                                <option value="<?php echo $forncedor['id']; ?>"><?php echo $forncedor['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>



                                    <div class="form-group col-md-12">
                                        <label for="funcao_extra">Categoria</label>
                                        <select class="form-control chosen-select" name="categoria" required>
                                            <option value="">...</option>
                                            <?php
                                            while ($categoria = mysqli_fetch_array($sqlCategoria)) {
                                            ?>
                                                <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label for="arq_pg">Anexar: </label>
                                        <input type="file" class="form-control-file" id="arq_pg" name="arq_pg" accept="image/jpg, image/jpeg, application/pdf">
                                        <small class="text-danger">(.PDF .JPG .JPEG)*</small>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="funcao_extra">Detalhe da despesa </label>
                                        <textarea type="text" class="form-control form-control-sm" id="detalhe_desp" value="" name="detalhe_desp"></textarea>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="qtd_parcela">Valor das parcelas</label>
                                        <input type="text" class="form-control form-control-sm valor" id="valor_parcela" value="">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="qtd_parcela">Quantidade de parcelas</label>
                                        <input type="number" class="form-control form-control-sm" id="qtd_parcela" value="">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="qtd_parcela">Dia do vencimento</label>
                                        <input type="number" class="form-control form-control-sm" id="vencimento" value="" min="0" max="31">
                                    </div>

                                    <div class="form-group col-md-3 my-4">
                                        <button type="button" class="btn btn-primary my-1" id="gerar_parcela">Gerar parcelas</button>
                                    </div>

                                    <table class="table table-sm" id="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Data Vencimento</th>
                                                <th>Valor</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                <th><input class="form-control form-control-sm auto" type="date" name="vencimento[]" value="" required></th>
                                                <td><input class="form-control form-control-sm somar auto valor" type="text" name="valor[]" value="" required></td>
                                                <td></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-sm remover my-3 float-left env" id="adicionar">Adicionar Vencimento</button>
                                    </div>

                                    <div class="alert alert-danger text-center col-12" id="nfCadastrada" role="alert" style="display: none">
                                        Sistema: <strong>NF já cadastrada!</strong>
                                    </div>

                                    <div class="col-md-12 text-right ">
                                    <button type="submit" class="btn btn-app my-1 text-success">
                                        <i class="fas fa-plus"></i>
                                        Salvar
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- CORPO -->
                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
                    </div>
                    <!-- ./wrapper -->

                    <?php require_once('footer.php'); ?>
                    <!-- jquery-validation -->
                    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
                    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
                    <!-- Select2 -->
                    <script src="plugins/select2/js/select2.full.min.js"></script>
                    <!-- Toastr -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                    <script src="plugins/toastr/toastr.min.js"></script>
                    <script src="https://rawgit.com/BankFacil/vanilla-masker/master/lib/vanilla-masker.js"></script>
                    <script>
                        $('.chosen-select').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                    </script>
</body>

</html>