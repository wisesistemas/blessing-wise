<?php
    session_start();
    $post = explode("&", $_POST['id']);
    $req = explode("=", $post[0]);
    $req = $req[1];
    require_once('../validar/class/db.class.php');
    $objDb = new db();
    $link = $objDb->conecta_mysql();
    require_once("../validar/class/class.itemEstoque.php");
    $nomeItem = new ItemEstoque();
    unset( $_SESSION['itens_insert_estoque'] );
 
    $sql = "SELECT  COUNT(i.id) AS total, u.nome as solicitante, c.fone as contato, c.bairro as bairro, c.uf as uf, c.nome as unidade, r.data as data, r.hora as hora, c.id as idUnidadeSolicitante, r.obs_slt as obs
    FROM estoque_registro_requisicao r 
    INNER JOIN cad_unidade c
    ON r.unidade = c.id
    INNER JOIN usuarios u 
    ON r.cad = u.id
    INNER JOIN estoque_item_requisicao i 
    ON i.registro = r.requisicao
    where r.requisicao = $req AND i.status = 550";
    $sql = mysqli_query( $link, $sql );
    $painel = mysqli_fetch_array( $sql );

?>
<div class="callout callout-info">
    <h5><strong>REQUISIÇÃO:</strong> <?php echo $req; ?></h5>
    <p>
        <strong>SOLICITANTE: </strong> <?php echo $painel['solicitante']; ?><br>
        <strong>UNIDADE: </strong> <?php echo $painel['unidade']; ?><br>
        <strong>LOCAL: </strong> <?php echo $painel['bairro']." - ".$painel['uf']; ?><br>
        <strong>DATA / HORA DO PEDIDO: </strong> <?php echo date('d/m/Y',strtotime($painel["data"]))." - ".$painel['hora']; ?><br>
        <strong>CONTATO: </strong> <?php echo $painel['contato']; ?><br>
    </p>

</div>
<?php if(intval($painel['total']) > 0){ ?>
<div class="table-responsive">
<table class="table table-sm table-striped dataTable js-exportable table-bordered">
    <thead>
        <tr class="text-center BG-INFO elevation-1">
            <th scope="col">#</th>
            <th scope="col">ITEM</th>
            <th scope="col">QTD.SLT</th>
            <th scope="col">RETIFICAR</th>
            <th scope="col">STATUS</th>
        </tr>
    </thead>
    <tbody>
        <?php

      $sql = "SELECT i.id as itemId, i.nome as item, i.referencia as referencia, i.marca as marca, i.modelo as modelo, e.qtd_slt as solicitado, e.qtd_env as enviado , e.qtd_ent as recebida, s.status as status, e.id as id_item_req
      FROM estoque_registro_requisicao r 
      INNER JOIN estoque_item_requisicao e
      ON r.requisicao = e.registro
      INNER JOIN cad_item i 
      ON e.item = i.id
      INNER JOIN status s
      ON e.status = s.id
      where r.requisicao = $req AND e.status = 550";

      $sql = mysqli_query( $link, $sql );
        while ( $req = mysqli_fetch_array( $sql ) ){
    ?>
<input type="hidden" name="id[]" value="<?php echo$req['id_item_req']; ?>">
            <tr class="text-center">
                <th scope="row"><?php echo $req['itemId']; ?></th>
                <td><?php echo $nomeItem->nomeItemParametrizado($req['itemId']); ?></td>
                <td><?php echo $req['solicitado']; ?> </td>
                <td><input class="text-center" type="number" name="qtd[]" value="<?php echo $req['solicitado']; ?>"></td>
                <td><?php echo strtoupper($req['status']); ?></td>
            </tr>
            <?php 
                } 
            ?>
             <tr class="text-center BG-INFO elevation-1">
                <th></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    </tbody>
</table>
<div>
<br>
<button type="button" class="btn btn-xs btn-danger col-12 elevation-3" id="retificar_requisicao"><strong>Confirmar Retificação de Quantidade Solicitada</strong></button>
<br>
<?php }else{ ?>
              
              <div class="callout callout-danger">
  <h5><strong></h5>
  <p>
    No momento não existe item para retificação!
  </p>

</div>
          <?php } ?>