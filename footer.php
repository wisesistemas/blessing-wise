<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- Large modal  AVATAR-->
<div class="modal fade" id="mudar_avatar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">WISE - SISEMAS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="modal-title text-info">Selecione um Avatar para seu perfil!</h6>
                <div class="callout callout-info col-12"><br>
                    <form id="form_avatar">
                        <div class="row">

                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar1"
                                    value="avatar1" checked>
                                <label class="form-check-label" for="avatar1">
                                    <img src="dist/img/avatar1.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar2"
                                    value="avatar2">
                                <label class="form-check-label" for="avatar2">
                                    <img src="dist/img/avatar2.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar3"
                                    value="avatar3">
                                <label class="form-check-label" for="avatar3">
                                    <img src="dist/img/avatar3.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar4"
                                    value="avatar4">
                                <label class="form-check-label" for="avatar4">
                                    <img src="dist/img/avatar4.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar5"
                                    value="avatar5">
                                <label class="form-check-label" for="avatar5">
                                    <img src="dist/img/avatar5.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar6"
                                    value="avatar6">
                                <label class="form-check-label" for="avatar6">
                                    <img src="dist/img/avatar6.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar7"
                                    value="avatar7">
                                <label class="form-check-label" for="avatar7">
                                    <img src="dist/img/avatar7.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar8"
                                    value="avatar8">
                                <label class="form-check-label" for="avatar8">
                                    <img src="dist/img/avatar8.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>
                            <div class="form-check col-2">
                                <input class="form-check-input" type="radio" name="avatar_radios" id="avatar9"
                                    value="avatar9">
                                <label class="form-check-label" for="avatar9">
                                    <img src="dist/img/avatar9.png" class="rounded float-left" style="width: 56px;">
                                </label>
                            </div>

                        </div>
                </div>
                </form>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="atualiza_avatar">Salvar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
<!-- FIM Large modal  AVATAR-->

<!-- Large modal  HELP-->
<div class="modal fade" id="modal_help">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">WISE - SISTEMAS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body "  style="padding-left: 0px; padding-right: 0px;">
                <!-- corpo -->
                <div class="card-body "  style="padding-left: 5px; padding-right: 5px;">
                    <div id="accordion">
                        <!-- collapse Estoque -->
                        <div class="card card-info elevation-1" data-toggle="collapse" data-parent="#accordion"
                            href="#estoque_help">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a>
                                        Estoque:
                                    </a>
                                </h4>
                            </div>
                            <div id="estoque_help" class="panel-collapse collapse in">
                                <div class="card-body elevation-1">

                                    <ul>
                                        <li><label>Inventário:</label>
                                            <ul>
                                                <li>Faça o inventário dos seus itens.</li>
                                                <li>Todos os valores informados nessa tela altera a quantidade para o valor inserido.</li>
                                                <li>Exemplo: Item 'X' tem uma quantidade de 100 no estoque. O mesmo foi inventariado para 50. O item 'X' passará ter a quantidade atual de 50.</li>
                                                <li>A função de inventário: O primeiro registro do item no seu estoque.</li>
                                            </ul>
                                        </li>
                                        <li><label>Acerto:</label>
                                            <ul>
                                                <li><label>Entradas:</label>
                                                    <ul>
                                                        <li>O acerto de entrada soma a quantidade informada mais a quantidade atual.</li>
                                                        <li>O registro de acerto de entrada deverá ser informado sempre que o item for contabilizado, nos seguintes casos abaixo:</li>
                                                        <li>Casos para registros: Item que foram achados, item com entrada por transferência entre unidade, itens que deram entrega sem requisição etc.</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li><label>Saídas:</label>
                                                    <ul>
                                                        <li>O acerto de saída subtrai a quantidade informada sob a quantidade atual.</li>
                                                        <li>O registro de acerto de saída deverá ser informado sempre que o item for contabilizado, nos seguintes casos abaixo:</li>
                                                        <li>Casos para registros: Item que foram inutilizados, item quebrado, item furtado etc.</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><label>Saldo:</label>
                                            <ul>
                                                <li>Relatório de quantidade de item na sua unidade.</li>
                                                <li>Abaixo os tipos de transações que contabiliza no estoque:</li>
                                                <li>Inventário, acertos de entrada e saídas, baixa para consumo, entrada de item por requisição.</li>
                                            </ul>
                                        </li>
                                        <li><label>Baixa:</label>
                                            <ul>
                                                <li>Todos os itens que saíram do estoque para consumo/utilização.</li>
                                                <li>Todos os itens registrados na baixa são calculados com a quantidade atual em estoque.</li>
                                                <li>A quantidade informada será subtraída pela quantidade atual.</li>
                                            </ul>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <!-- Fim collapse Estoque  -->
                        <!-- collapse Requisição -->
                        <div class="card card-warning" data-toggle="collapse" data-parent="#accordion"
                            href="#requiscao_help">
                            <div class="card-header">
                                <h4 class="card-title ">
                                    <a>
                                        Requisição:
                                    </a>
                                </h4>
                            </div>
                            <div id="requiscao_help" class="panel-collapse collapse in">
                                <div class="card-body elevation-1">
                                 
                                </div>
                            </div>
                        </div>
                        <!-- Fim collapse Requisição  -->
                    </div>
                </div>
            </div><div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
            <!-- fim corpo -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- /.modal -->

</div>
<!-- FIM Large modal  HELP-->