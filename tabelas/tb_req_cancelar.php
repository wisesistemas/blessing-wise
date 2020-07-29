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
    unset( $_SESSION['itens_insert_estoque'] );
    $tipo = intval($_POST['tipo']);
    $sql = "SELECT u.nome as solicitante, c.fone as contato, c.bairro as bairro, c.uf as uf, c.nome as unidade, r.data as data, r.hora as hora, c.id as idUnidadeSolicitante, r.obs_slt as obs
    FROM estoque_registro_requisicao r 
    INNER JOIN cad_unidade c
    ON r.unidade = c.id
    INNER JOIN usuarios u 
    ON r.cad = u.id
    where r.requisicao = $req";
    $sql = mysqli_query( $link, $sql );
    $painel = mysqli_fetch_array( $sql );

?>
<div class="callout callout-info elevation-1">
    <h5><strong>REQUISIÇÃO:</strong> <?php echo $req; ?></h5>
    <p>
        <strong>SOLICITANTE: </strong> <?php echo $painel['solicitante']; ?><br>
        <strong>UNIDADE: </strong> <?php echo $painel['unidade']; ?><br>
        <strong>LOCAL: </strong> <?php echo $painel['bairro']." - ".$painel['uf']; ?><br>
        <strong>DATA / HORA DO PEDIDO: </strong> <?php echo date('d/m/Y',strtotime($painel["data"]))." - ".$painel['hora']; ?><br>
        <strong>CONTATO: </strong> <?php echo $painel['contato']; ?><br>
    </p>

</div>
<div class="table-responsive-sm">
<table class="table table-sm table-striped table-bordered">
    <thead>
        <tr class="text-center bg-info elevation-1">
            <th scope="col">#</th>
            <th scope="col">ITEM</th>
            <th scope="col">QTD.S</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($tipo == 0){
            $sql = "SELECT i.id as idItem, i.nome as item, r.qtd_slt as solicitado, r.id as id
            FROM estoque_item_requisicao r 
            INNER JOIN cad_item i 
            ON r.item = i.id
            where r.registro = $req && r.status = 555";
        }else
      if( 2 == intval($familia) ){
        $sql = "SELECT i.id as idItem, i.nome as item, r.qtd_slt as solicitado, r.id as id
              FROM estoque_item_requisicao r 
              INNER JOIN cad_item i 
              ON r.item = i.id
              where r.registro = $req && i.familia = $familia";
      }else{
      $sql = "SELECT i.id as idItem, i.nome as item, r.qtd_slt as solicitado, r.id as id
              FROM estoque_item_requisicao r 
              INNER JOIN cad_item i 
              ON r.item = i.id
              where r.registro = $req && i.familia = $familia && i.genero = $genero";
      }
      $sql = mysqli_query( $link, $sql );
        while ( $req = mysqli_fetch_array( $sql ) ){
        $item[] = $req['id'];
    ?>
        <form method="post" action="validar/validar_cancelar_req.php">
            <tr class="text-center">
                <th><?php echo $req['idItem']; ?> </th>
                <td><?php echo $nomeItem->nomeItemParametrizado($req['idItem']); ?> </td>
                <td><?php echo $req['solicitado']; ?> </td>
            </tr>
            <?php 
                } 
            $_SESSION['itens_insert_estoque'] = $item;
            ?>
            <tr class="text-center bg-info elevation-1">
                <th></th>
                <td></td>
                <td></td>
            </tr>
    </tbody>
</table>

</div>
Obs:
<div class="row">
    <textarea class="form-control col-12 elevation-1" name="obs" id="obs"></textarea>
</div>

<br>

<br>
</form>