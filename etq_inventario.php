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

?>
<html>

<head>
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

        $(".estoque").addClass('menu-open');
        $("#set_invenratio").addClass('fas fa-circle nav-icon');
        
       $(document).on('click','.excluir', function(){
         var item = $(this).attr('id');
         $.ajax({
              url: "validar/validar.php?id=2",
              type: "POST",
              data: {item: item},
              success: function(data){
                console.log(data);
                $("#button").show(); 
                getTabela();
              }
          });
       })

        $("#inserir").click( function(){
          var form = $("#form").serialize();
          $.ajax({
              url: "validar/validar.php?id=1",
              type: "POST",
              data: form,
              success: function(data){
                if(data == 1){
                    $("#button").show();
                    getTabela();
                }else{
                    Toast.fire({
                        icon: 'error',
                        title: 'Registro sem item: Por favor, selecione um item para o registro!'
                    })
                }
                
              }
          });
        })

        $("#cancelar").click( function(){
            $.ajax({
              url: "validar/validar.php?id=2&prmt=2",
              success: function(data){
                $("#tb_itens").hide(data);
                $("#button").hide();
              }
          });
        })

        $("#salvar").click( function(){
            $("#loading1").show();
            $.ajax({
              url: "validar/validar.php?id=3&prmt=1",
              type: "POST",
              beforeSend: function(){
                    $("#tb_itens").hide();
                    $("#button").hide();
                },
              data: {token: '<?php echo$token;?>'},
              success: function(data){
                  console.log(data);
                  $("#loading1").hide();
                if(data == 1){
                    Toast.fire({
                        icon: 'success',
                        title: 'Todos os registros foram realizados com sucesso!'
                    })
                }else{
                    Toast.fire({
                        icon: 'error',
                        title: 'Registro sem item: Por favor, selecione um item para o registro!'
                    })
                }
                
              }
          });
        })

        function getTabela(){
          $.ajax({
              url: "tabelas/tb_itens_estoque.php",
              success: function(data){
                $("#tb_itens").html(data).show();
              }
          })
        }

        $("#loading1").hide();
    
    });
    </script>
    
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
    
    <div class="overlay-wrapper" >
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Carregando...</div>
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
                        <h1 class="m-0 text-dark">Inventário estoque</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Inventário</strong></li>
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
                        <h3 class="card-title">Inventário estoque:</h3>

                        <div class="card-tools">
                            <button type="button"  class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <form id="form">
                    <div class="card-body" style="padding-left: 10px; padding-right: 10px;">
                        <div class="row">
                            <input type="hidden" name="token" id="token" value="<?php  echo$token;?>">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_item" >Selecione o item:</label>
                                    <select class="form-control form-control-sm select2" style="width: 100%;" id="id_item" name="item">
                                        <option selected="selected" value="">...</option>
                                        <?php 
                                        $sql = "SELECT * FROM vw_item_com_nome_unidade_saldo WHERE id_unidade = $_SESSION[usuario_id_unidade]";
                                        $sql = mysqli_query( $link, $sql );
                                        while ( $sqlItem = mysqli_fetch_array( $sql ) ) {
                                        ?>
                                        <option value="<?php echo$sqlItem['id']?>"><?php echo$sqlItem['nome']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
             
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qtd">Quantidade Em Estoque:</label>
                                    <input type="number" min="0" class="form-control form-control-sm" name="qtd" id="qtd"
                                        placeholder="Quantidade">
                                </div>
                            </div>
                            <div class="col-md-1 my-2 text-right" >
                                <a class="btn btn-app btn-sm elevation-1" id="inserir" data-toggle="tooltip" data-placement="top" title="Inserir item na lista">
                                    <i class="fas fa-arrow-down " style="font-size: 24px; color: green"></i><strong>Inserir</strong>
                                </a>
                            </div>
                        </div>
                        </form>
                        <div id="tb_itens"></div>
                        <div class="row" style="display: none" id="button">
                        <div class="col-md-12 text-right">
                                <a class="btn btn-app btn-sm elevation-1" id="cancelar" data-toggle="tooltip" data-placement="top" title="Cancelar todos os itens.">
                                <i class="fas fa-times " style="font-size: 24px; color: red"></i></i><strong>Cancelar</strong>
                                </a>
                                <a class="btn btn-app btn-sm elevation-1" id="salvar" data-toggle="tooltip" data-placement="top" title="Salvar.">
                                <i class="far fa-save " style="font-size: 24px; color: green"></i></i><strong>Salvar</strong>
                                </a>
                          </div>
                          </div>
                        </div>
                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
                        <!-- ./wrapper -->

                        <?php require_once('footer.php'); ?>
                        <!-- Select2 -->
                        <script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>

                        <script>
                            
                        $('.select2').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                        </script>
</body>

</html>