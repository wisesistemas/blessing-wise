<?php
    session_start();
    $post = explode("&", $_POST['id']);
    $req = explode("=", $post[0]);
    $req = $req[1];
    $familia = explode("=", $post[1]);
    $familia = $familia[1];
    $genero = explode("=", $post[2]);
    $genero = $genero[1];
    require_once('../validar/class/db.class.php');
    $objDb = new db();
    $link = $objDb->conecta_mysql();
    require_once("../validar/class/class.itemEstoque.php");
    $nomeItem = new ItemEstoque();
    require_once("../validar/class/class.estoque.php");
    $estoque = new Estoque();
    unset( $_SESSION['itens_insert_estoque'] );
    $_SESSION['req'] = $req;
    $sql = "SELECT u.nome as solicitante, c.fone as contato, c.bairro as bairro, c.uf as uf, c.nome as unidade, r.data as data, r.hora as hora, c.id as idUnidadeSolicitante, r.obs_slt as obs
    FROM estoque_registro_requisicao r 
    INNER JOIN cad_unidade c
    ON r.unidade = c.id
    INNER JOIN usuarios u 
    ON r.cad = u.id
    where r.requisicao = $req";
    $sql = mysqli_query( $link, $sql );
    $painel = mysqli_fetch_array( $sql );
    unset($_SESSION['itens_req']);

?>
<div class="callout callout-info elevation-1">
    <h5><strong>REQUISIÇÃO:</strong> <?php echo $req; ?></h5>
    <p>
        <strong>SOLICITANTE: </strong> <?php echo $painel['solicitante']; ?><br>
        <strong>UNIDADE: </strong> <?php echo $painel['unidade']; ?><br>
        <strong>LOCAL: </strong> <?php echo $painel['bairro']." - ".$painel['uf']; ?><br>
        <strong>DATA / HORA DO PEDIDO: </strong>
        <?php echo date('d/m/Y',strtotime($painel["data"]))." - ".$painel['hora']; ?><br>
        <strong>CONTATO: </strong> <?php echo $painel['contato']; ?><br>
        <strong>OBSERVAÇÃO DO SOLICITANTE: </strong> <?php echo $painel['obs']; ?><br>
    </p>

</div>
<div class="table-responsive-sm">
    <table class="table table-sm table-bordered">
        <thead>
            <tr class="text-center bg-info elevation-1">
                <th data-toggle="tooltip" data-placement="top" title="ID do item solicitado!">#</th>
                <th data-toggle="tooltip" data-placement="top" title="Nome do item solicitado!">ITEM</th>
                <th data-toggle="tooltip" data-placement="top" title="Saldo em estoque da unidade central!">
                    SALDO.EC</th>
                <th data-toggle="tooltip" data-placement="top"
                    title="Saldo em estoque da unidade solicitante!">SALDO.ES</th>
                <th data-toggle="tooltip" data-placement="top" title="Quantidade solicitada!">QTD.S</th>
                <th data-toggle="tooltip" data-placement="top"
                    title="Quantidade em estoque informada no momento da requisição!">QTD.STQ</th>
                <th data-toggle="tooltip" data-placement="top"
                    title="Referencia informada no momento da requisição!">REF</th>
                <th data-toggle="tooltip" data-placement="top" title="Quantidade que será atendida!">ENVIAR
                </th>
            </tr>
        </thead>

        <tbody>
            <?php
         if(intval($_POST['pendente']) == 0 ){
            $sql = "SELECT i.id as idItem, i.nome as item, r.qtd_slt as solicitado, r.qtd_etq as qtd_estoque_informado, r.ref as referencia_informado, r.id as atualiza
            FROM estoque_item_requisicao r 
            INNER JOIN cad_item i 
            ON r.item = i.id
            where r.status = 555 && r.registro = $req";
          }else
      if( 2 == intval($familia)  || 1 == intval($familia)  ){
        $sql = "SELECT i.id as idItem, i.nome as item, r.qtd_slt as solicitado, r.qtd_etq as qtd_estoque_informado, r.ref as referencia_informado, r.id as atualiza
              FROM estoque_item_requisicao r 
              INNER JOIN cad_item i 
              ON r.item = i.id
              where r.registro = $req && i.familia = $familia ";
      }
      else{
      $sql = "SELECT i.id as idItem, i.nome as item, r.qtd_slt as solicitado, r.qtd_etq as qtd_estoque_informado, r.ref as referencia_informado, r.id as atualiza
              FROM estoque_item_requisicao r 
              INNER JOIN cad_item i 
              ON r.item = i.id
              where r.registro = $req && i.familia = $familia && i.genero = $genero";
      }
      $sql = mysqli_query( $link, $sql );
      while ( $req = mysqli_fetch_array( $sql ) ){
        $_SESSION['itens_req1'][$req['atualiza']] = $req['idItem'];
    ?>

            <tr class="text-center" style="height: 10px">
                <th scope="row" class="text-center" style="vertical-align: middle;" title="ID do item solicitado!">
                    <?php echo$req['idItem']; ?></th>
                <td title="Nome do item solicitado!" style="vertical-align: middle;">
                    <?php echo$nomeItem->nomeItemParametrizado($req['idItem']); ?></td>
                <td title="Saldo em estoque da unidade central!" style="vertical-align: middle;">
                    <?php echo$estoque->saldoAtual( $_SESSION['usuario_id_unidade'],  $req['idItem'] ); ?></td>
                <td title="Saldo em estoque da unidade solicitante!" style="vertical-align: middle;">
                    <?php  echo$estoque->saldoAtual( $painel['idUnidadeSolicitante'],  $req['idItem'] ); ?></td>
                <td title="Quantidade solicitada!" style="vertical-align: middle;">
                    <?php echo$req['solicitado']; ?></td>
                <td title="Quantidade em estoque informada no momento da requisição!" style="vertical-align: middle;">
                    <?php echo$req['qtd_estoque_informado']; ?></td>
                <td title="Referencia informada no momento da requisição!" style="vertical-align: middle;">
                    <?php echo$req['referencia_informado']; ?></td>
                <td title="Quantidade que será atendida!" style="vertical-align: middle;"><input type="number"
                            name="qtd_<?php echo$req['atualiza'];?>_[]" class="pula"
                            style="width: 100%; vertical-align: center;" value=""
                            max="<?php echo$estoque->saldoAtual( $_SESSION['usuario_id_unidade'],  $req['idItem'] ); ?>">
                </td>
            </tr>

            <?php } 
        
            ?>
            <tr class="text-center bg-info elevation-1">
                <th></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        </tbody>
    </table>
</div>

</div>
Obs:
<div class="row">
    <textarea class="form-control col-12 pula elevation-1" id="obs"></textarea>
</div>



<br>

<br>
<script>
$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})
$(".pula").keypress(function(e) {
    var tecla = e.keyCode ? e.keyCode : e.which;
    if (tecla == 13) {
        campo = $(".pula");
        indice = campo.index(this);
        e.preventDefault(e);
        if (campo[indice + 1] != null) {
            proximo = campo[indice + 1];
            proximo.focus();
        }
    }
});
</script>