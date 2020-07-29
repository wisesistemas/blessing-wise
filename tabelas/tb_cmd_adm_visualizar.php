<?php
require_once('../validar/class/db.class.php');
session_start();
$objDb = new db();
$link = $objDb->conecta_mysql();
$chamado = intval($_POST['chamado']);
$cont = 0;

$validador = md5("wise123456");
$_SESSION['token_adm_atender_chamado'] = $validador;

$sql = "SELECT uni.nome AS unidade, 
cha.falar_com AS contato,
cha.falar_contato AS telefone, 
ass.assunto AS assunto,
usu.nome AS cadastrado,
cha.`data` AS dt_cadastro,
cha.descricao AS descricao,
st.`status` AS `status`,
st.id AS id_status,
cha.arq AS arq
FROM ch_chamado AS cha 
INNER JOIN cad_unidade AS uni
ON cha.unidade = uni.id
INNER JOIN usuarios AS usu 
ON cha.usu = usu.id
INNER JOIN ch_assunto AS ass
ON cha.assunto = ass.id
INNER JOIN ch_status AS st
ON cha.`status` = st.id
WHERE cha.id = $chamado";
$chamado_info = mysqli_fetch_array(mysqli_query($link, $sql));

$sqlAtualizacao = "SELECT u.nome as por, a.data as data, a.hora as hora, a.descricao as descricao, s.status as status, 
a.id as id, u.id as id_usu, u.avatar as avatar, a.arq as arq
FROM ch_atual a 
INNER JOIN usuarios u 
ON a.por = u.id
INNER JOIN ch_status s 
ON a.tipo_atualizacao = s.id
where a.chamado = '$chamado' && a.ativo = 1 ORDER BY a.id ";
$sqlAtualizacao = mysqli_query($link, $sqlAtualizacao);
?>
<div class="callout callout-info">
    <h5>Chamado: <?php echo $chamado ?></h5>
    <p>
        <label>Unidade:</label> <?php echo $chamado_info['unidade']; ?> <br>
        <label>Contato:</label> <?php echo $chamado_info['contato']; ?><br>
        <label>Telefone:</label> <?php echo $chamado_info['telefone']; ?><br>
        <label>Assunto:</label> <?php echo $chamado_info['assunto']; ?><br>
        <label>Cadastro:</label> <?php echo $chamado_info['cadastrado']; ?><br>
        <label>Status:</label> <?php echo $chamado_info['status']; ?><br>
        <label>Descrição:</label> <?php echo $chamado_info['descricao']; ?><br>
        <label>Imagem Anexada:</label>
        <?php
        if (strlen($chamado_info['arq']) > 20) {
            echo " <img class='img-fluid pad' src='$chamado_info[arq]' style='height: 400px; width: 400px' alt='Photo'>";
        } else {
        }
        ?>
    </p>
</div>
<div class="">
    <?php while ($atual = mysqli_fetch_array($sqlAtualizacao)) {
        $cont++;
        $data = date('d/m/Y', strtotime($atual['data'])) . ' - ' . $atual['hora'] . 'h';
        intval($atual['id_usu']) == $_SESSION['usuario_id'] ? $usuarioAtual = "text-right" : $usuarioAtual = "text-left";
        $por = explode(' ', $atual['por']);

        if (intval($atual['id_usu']) == $_SESSION['usuario_id']) {
    ?>

            <!-- Message. Default to the left -->
            <!-- Message. Default to the left -->

            <div class="direct-chat-msg">
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-left">Você</span>
                    <span class="direct-chat-timestamp float-right"><?php echo $data; ?></span>
                </div>
                <!-- /.direct-chat-infos -->
                <img class="direct-chat-img" src="dist/img/<?php echo $atual['avatar']; ?>.png" alt="Message User Image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text bg-info text-left">
                <?php
                    if (strlen($atual['arq']) > 20) {
                        echo " <img class='img-fluid pad' src='$atual[arq]' style='height: 400px; width: 400px' alt='Photo'>";
                    } else {
                    }
                    ?>
                    <?php echo $atual['descricao']; ?>
                </div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->
        <?php } else { ?>
            <!-- Message to the right -->
            <div class="direct-chat-msg right">
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-right"><?php echo $por[0]; ?></span>
                    <span class="direct-chat-timestamp float-left"><?php echo $data; ?></span>
                </div>
                <!-- /.direct-chat-infos -->
                <img class="direct-chat-img" src="dist/img/<?php echo $atual['avatar']; ?>.png" alt="Message User Image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text text-right">
                <?php if (strlen($atual['arq']) > 20) {
                        echo " <img class='img-fluid pad' src='$atual[arq]' style='height: 400px; width: 400px' alt='Photo'>";
                    } else {
                    }
                    ?>
                    <?php echo $atual['descricao']; ?>
                </div>
                <!-- /.direct-chat-text -->
            </div>
    <?php }
    } ?>
</div>
<?php if (false) { ?>
    <hr> 
    <!-- <div class="input-group col-md-12 my-3">
        <input type="text" name="message" id="message" placeholder="Mensagem...." class="form-control ">

    </div> -->
    <!-- <label for="descricao_atua">Nova Mensagem:</label>
        <textarea class="form-control" placeholder="" id="descricao_atua" name="descricao_atua" rows="3"></textarea> -->

    
    <div class="form-group col-md-12 text-right">

   <!-- <label for="file_atual">Enviar arquivo<label> -->
   <br><form id="form_atual" >
   <input type="hidden" id="status_atua" name="status_atua" value="<?php echo $chamado_info['id_status']; ?>">
    <input type="hidden" id="chamado_atual" name="chamado_atual" value="<?php echo $chamado; ?>">
    <input type="hidden" id="validador_atual" name="validador_atual" value="<?php echo $validador; ?>">
    <img id='output' class='img-fluid pad col-8 my-1' id="img_atual" > 
   <!--  <input type="text" name="message" id="message" placeholder="Mensagem...." class="form-control "> -->
  <!--  <div class="input-group">
                        <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                        <span class="input-group-append">
                            <label for="file_atual">
                            <i class="fas fa-camera border-bottom border-right border-top" style="font-size: 34px"></i>
                             <div class="border-bottom border-right border-top">Send</div> 
                            </label>
                            <input type='file' accept='image/*' onchange='openFile(event)' style="display: none;" id="file_atual">
                        </span>
                      </div> -->
                <div class="input-group mb-3">
                <div class="input-group-append">

                    <label class="input-group-text" for=""><a type="submit" class="finalizar" name="tipo" value="0" title="Cancelar"><i class="fas fa-power-off text-danger"></i></a></label>
                  </div>
                  <textarea type="text" class="form-control" name="message" id="message" rows="1"></textarea>
                  <div class="input-group-append">
                    <input type='file' accept='image/*' onchange='openFile(event)' style="display: none;" id="file_atual" name="files" capture="camera">
                    <label class="input-group-text" for="file_atual"><i class="fas fa-camera"></i></label>
                    <label class="input-group-text" for=""><a type="submit" class="envia" name="tipo" value="1"><i class="fas fa-chevron-right text-success"></i></a></label>
                  </div>
                </div>
</form>
<script>
  var openFile = function(event) {
    var input = event.target;

    var reader = new FileReader();
    reader.onload = function(){
      var dataURL = reader.result;
      var output = document.getElementById('output');
      output.src = dataURL;
    };
    reader.readAsDataURL(input.files[0]);
    $("#form_atual").css("background"," #e9efec");
  };
  
  var ENTER_KEY = 13;

$('textarea').on('keypress', function(event) {
  var char = event.which || event.keyCode;
  if (char == ENTER_KEY) {
    event.preventDefault();
    $(this).parent('form').submit();
  }
})
</script>
    </div>

<?php } else { ?>
    <div class="callout callout-warning">
        <p>Chamado Finalizado!</p>
    </div>
<?php } ?>