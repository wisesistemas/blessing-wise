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

    $sqlUnidade = "select * from cad_unidade";
  $sqlUnidade = mysqli_query( $link, $sqlUnidade );

  $sqlColaborador   = "select * from cad_funcionario";
  $sqlColaborador   = mysqli_query( $link, $sqlColaborador );

  $sqlSub   = "select * from cad_funcionario";
  $sqlSub   = mysqli_query( $link, $sqlSub );

  $sqlStatus        = "select * from status where id >= 5 AND id <= 50";
  $sqlStatus   = mysqli_query( $link, $sqlStatus );

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
        window.alert = function() {};
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(".rh_dp").addClass('menu-open');
        $(".rh_dp_admin").addClass('menu-open');
        $("#rh_dp_gerenciar_extras").addClass('fas fa-circle nav-icon');

        function cancelarComSucesso(){
            Toast.fire({
                icon: 'success',
                title: 'Extra cancelado com sucesso!'
            })
        }

       

        $("#gerar").click( function(){
            var form = $("#form12").serialize();
            $("#loading1").show();
                $.ajax({
                url:'tabelas/ul.extras_para_aprovacao.php',
                type: 'POST',
                beforeSend: function(){
                
                },
                complete: function(){
                    
                },
                data: form,
                success: function(data){
                    $("#loading1").hide();
                    console.log(data);
                    $("#tabela").html(data);
                }
                })
        })

        $(document).on( 'click', '.extra', function(){
                $("#loading1").show();
                $.ajax({
                    url: 'tabelas/tb_analisar_extra.php',
                    type: 'POST',
                    data: {query_extras:  $(this).attr('id') },
                    success: function(data){
                        console.log(data);
                        $("#loading1").hide();
                        $("#getExtra").html(data);
                    }
                })
                 $('#exampleModal').modal('show');

            });


        $("#loading1").hide();

        
       
    });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
    
    <div class="overlay-wrapper" >
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Carregando itens...</div>
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
                        <h1 class="m-0 text-dark">Gerenciamento de extras</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Gerenciamento de extras</strong></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Gerenciamento de extras:</h3>

                        <div class="card-tools">
                            <button type="button"  class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <form id="form12" class="col-md-12 my-2">
                        <div class="row">

                            <div class="form-group col-3">
                                <label for="dataMinima">DATA MINIMA</label>
                                <input type="date" class="form-control form-control-sm" id="dataMinima" name="dataMinima">
                            </div>

                            <div class="form-group col-3">
                                <label for="dataMaxima">DATA MAXIMA</label>
                                <input type="date" class="form-control form-control-sm" id="dataMaxima" name="dataMaxima">
                            </div>

                            <div class="form-group col-3">
                                <label for="valorMenor">VALOR MAIOR</label>
                                <input type="number" class="form-control form-control-sm" id="valorMenor" name="valorMenor">
                            </div>

                            <div class="form-group col-3">
                                <label for="valorMaior">VALOR MENOR</label>
                                <input type="number" class="form-control form-control-sm" id="valorMaior" name="valorMaior">
                            </div>

                            <div class="form-group col-md-6"> 
                                <label for="unidade">UNIDADE</label>
                                <select class="form-control form-control-sm select2" name="unidade[]" id="unidade" required multiple>
                                <?php
                                    while ( $unidades = mysqli_fetch_array( $sqlUnidade ) ) {
                                ?>
                                <option value="<?php echo$unidades['id']; ?>"><?php echo$unidades['nome']; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6"> 
                                <label for="colaborador">COLABORADOR</label>
                                <select class="form-control form-control-sm select2" name="colaborador[]" id="colaborador" required multiple> 
                                <?php
                                    while ( $colaborador = mysqli_fetch_array( $sqlColaborador ) ) {
                                ?>
                                <option value="<?php echo$colaborador['id']; ?>"><?php echo$colaborador['nome']; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6"> 
                                <label for="sub">SUBSTITUÍDO</label>
                                <select class="form-control form-control-sm select2" name="sub[]" id="sub" required multiple> 
                                <?php
                                    while ( $sub = mysqli_fetch_array( $sqlSub ) ) {
                                ?>
                                <option value="<?php echo$sub['cpf']; ?>"><?php echo$sub['nome']; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6"> 
                                <label for="motivo">MOTIVO</label>
                                <select class="form-control form-control-sm select2" name="motivo[]" id="motivo" required multiple>    
                                    <option value="'Atestado medico'">Atestado Médico</option>
                                    <option value="'Atraso'">Atraso</option>
                                    <option value="'Falta'">Falta</option>
                                    <option value="'Ferias'">Ferias</option>
                                    <option value="'Licenca'">Licença</option>
                                    <option value="'Licenca maternidade'">Licença Maternidade</option>
                                    <option value="'Ferias'">Cobertura de Férias</option>
                                    <option value="'Demissão'">Demissão</option>
                                    <option value="'Folga'">Folga</option>
                                    <option value="'Falecimento'">Falecimento</option>
                                    <option value="'aumento quadro'">Aumento de Quadro</option>
                                    <option value="'Transferencia'">Transferencia</option>
                                    <option value="'Outros'">Outros</option>
                                </select>
                            </div>
                        </div>
        
                        <div class="form-group col-md-12"> 
                            <hr>   
                        </div>
                        <button type="button" class="btn btn-primary btn-lg btn-block btn-sm" id="gerar">Gerar</button>
                    </form>
                       
                    <div class="col-12">
                            <div id="tabela"></div>
                        </div>
                        <!-- ./wrapper -->
<!-- Modal -->
<div class="modal fade" id="exampleModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">WISE - SISTEMAS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="embed-responsive embed-responsive-16by9">
          <!--   <iframe class="embed-responsive embed-responsive-1by1" src="analisar_extra.php?query_extras=4260" allowfullscreen></iframe> -->
        </div> 
        <form id="form1">
            <div id="getExtra"></div> 
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Fechar Janela</button>
      </div>
    </div>
  </div>
</div>
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