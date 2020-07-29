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
unset($_SESSION['nf_array_item']);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>
    <?php require_once('head.php'); ?>
    <!-- Select2 -->
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <!-- teste -->
    <link rel="stylesheet" href="plugins/autocomplete/autocomplete.css" type="text/css">
    <script src="plugins/autocomplete/autocomplete.js"></script>
    <!--  -->
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
    <!-- WISE - JS -->
    <script src="plugins/wise_js/cpr_cad_manual.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    
   <script>
    $(document).ready(function() {
        $(".menu_compras").addClass('menu-open');
        $("#cad_nfe_manual").addClass('fas fa-circle nav-icon'); 
       
      
        $("#destinatario_nome").change(function() {
            var destinatario = $(this).val();
            $("#destinatario_cnpj").val(destinatario).focus();
        })

        $("#emitente_cnpj").keyup(function(e) {
            var tecla = e.keyCode ? e.keyCode : e.which;
            if (tecla == 13) {
                var cnpj = $(this).val();
                $.ajax({
                    url: "validar/validar.php?id=11",
                    type: "POST",
                    data: {
                        cnpj: cnpj
                    },
                    success: function(data) {

                        $(function() {
                            var obj = JSON.parse(data)
                            console.log(obj);
                            $("#emitente_nome").val(obj.nome);
                            $("#emitente_escricao_estadual").val(obj.es);
                            $("#emitente_uf").val(obj.uf);
                        });
                    },
                });
            }
        })

        $("#emitente_nome").keyup(function(e) {
            var tecla = e.keyCode ? e.keyCode : e.which;
            if (tecla == 13) {
                var nome = $(this).val();
                $.ajax({
                    url: "validar/validar.php?id=12",
                    type: "POST",
                    data: {
                        nome: nome
                    },
                    success: function(data) {
                        $(function() {
                            var obj = JSON.parse(data)
                            console.log(obj);
                            $("#emitente_cnpj").val(obj.cnpj);
                            $("#emitente_escricao_estadual").val(obj.es);
                            $("#emitente_uf").val(obj.uf);
                        });
                    },
                });
            }
        })
       
        $("#novocad").click(function(){
            location.reload(true);
        });
    })
    </script>
    <style>
        .box {
            width: 100%;
            border: 1px solid #c7a460;
            background-color: #f4edd5;
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
        }

        .box label {
            display: block;
            text-transform: none;
            padding: 4px 0px 0px 6px;
            font-family: Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
            font-size: 12px;
            min-height: 15px;
            font-weight: normal;
            color: #6f5e39;
            text-align: left;
            padding: 1px;
            margin-bottom: 1px;
        }

        .box input {
            height: auto !important;
            height: 15px;
            min-height: 15px;
            display: block;
            background-position: center;
            border: solid 1px #d6c39e;
            background-color: #fbfbf5;
            padding: 1px 1px 1px 1px;
        }

        .text1 {
            height: auto !important;
            height: 15px;
            min-height: 15px;
            display: block;
            background-position: center;
            border: solid 1px #d6c39e;
            background-color: #fbfbf5;
            padding: 1px 1px 1px 1px;
        }

        .legend {
            text-align: left;
            border: none;
            font-size: 14px;
            height: 15px;
            font-weight: bold;
            width: 98%;
            display: block;
            color: #b27235;
            background: none;
            border: none;
            /* text-transform: capitalize; */
            font-family: Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
        }

        .form-group {
            margin-bottom: 3px;
        }

        .GeralXslt table {
            /* width: 100%; */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            /* padding-top: 5px; */
        }

        .toggle {
            background: url(../imagens/ico_mais.gif) no-repeat 8px 0px;
            background-color: #ece8df;
            cursor: pointer;
            background-position: 6px 6px;
            padding-left: 16px;
            text-indent: 0px;
            border: solid 1px rgb(236, 236, 236);
        }


        .table tr:nth-child(odd) {
            background-color: #fff;
        }

        .table tr:nth-child(even) {
            background-color: #ccc;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
   
    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando dados...</div>
        </div> 
    </div> 

    <?php require_once('menu_superior.php'); ?>
    <?php require_once('menu_lataral.php'); ?>


    <!-- body -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Cadastro de NF-e manual</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Cadastro de NF-e</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

           
            <!-- Default box -->
            <div class="card collapsed-card" id="cod_nfe">
                <div class="card-header">
                    <div class="col-12" id="input_nf_hide">
                        <form id="form_xml1" enctype="multipart/form-data">
                    <div class="form-group col-6">
                                        <label for="xml_exemplo">Enviar arquivo</label>
                                        <input type="file" class="form-control-file" id="xml_exemplo" name="xml_exemplo" >
                                    </div>
                                    <div class="form-group col-6 my-3">
                                       <button type="submit"  class="btn btn-block bg-gradient-warning btn-xs  my-2" id="formControlFile1" name="formControlFile1">Carregar</button>
                                    </div>
                    </div>
                   
                </form>
                </div>
                <div class="card-body" style="
    padding-left: 0px;
    padding-top: 0px;
    padding-right: 0px;
">
                    <!-- body card -->
                    <div id="form_xml"></div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- fim body -->

    <?php require_once('footer.php'); ?>
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <script src="plugins/mask/jquery.mask.js"></script>
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- teste -->
    <link rel="stylesheet" href="plugins/autocomplete/autocomplete.css" type="text/css">
    <script src="plugins/autocomplete/autocomplete.js"></script>
    <!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <script>
        
    $('.select2').select2({
        placeholder: 'Selecione o item...'
    })

    $('.select2').css('height', 'calc(28px + 0px');
    </script>
<script>
    $('.select2').select2({
        placeholder: 'Selecione o item...'
    })

    $('.select2').css('height', 'calc(28px + 0px');

    $("#form_xml1").validate({
    rules: {
      
    },
    messages: {
      
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    },submitHandler: function (form) {
    
       var form = $('#form_xml1')[0];
       var data = new FormData(form);
                   $.ajax({
                     type: "POST",
                     enctype: 'multipart/form-data',
                     url: "tabelas/tb_form_xml.php",
                     beforeSend: function(){
                            
                     },
                     complete: function(){    
                             
                     },
                     data: data,
                     processData: false,
                     contentType: false,
                     cache: false,
                     timeout: 600000,
                     success: function (data) {
                        $("#cod_nfe").removeClass("collapsed-card");
                         console.log(data);
                         $("#form_xml").html(data);
                        /* const obj = JSON.parse(data);
                        console.log(obj); */
                       /*  $("#cod_nfe").removeClass("collapsed-card");
                        $("#serie").val(obj.nf.serie);
                        $("#chave").val(obj.nf.chave);
                        $("#v_xml").val(obj.nf.versaoXml);
                        $("#valor_total").val(obj.nf.valorTotalNf);
                        $("#dt_hr_emissao").val(obj.nf.dtEmissao);
                        $("#numero").val(obj.nf.codNfe);


                        $("#emitente_cnpj").val(obj.nf.cnpj);
                        $("#emitente_nome").val(obj.nf.nomeEmpresa);
                        $("#emitente_escricao_estadual").val(obj.nf.escEstadual);
                        $("#emitente_uf").val(obj.nf.uf);

                        $("#destinatario_nome").val(obj.nf.cnpjDest);
                        $("#destinatario_cnpj").val(obj.nf.nomeDest); */
      
                      
                   }  /* FIM função success */
           });
       
       alert("Form successful submitted!");
      
     },
  });
    </script>
</body>

</html>