<?php session_start(); ?>
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
  <style>
      th {
        font-family: 'Noto Sans JP', serif;
        font-size: 12px;
        font-style: normal;
      }
      td {
        font-family: 'Noto Sans JP', serif;
        font-size: 10px;
        font-style: normal;
      }
    </style>
<div class="card ">
    <div class="card-header">
        <h3 class="card-title">Itens inseridos:</h3>
    </div>
<div class="table-responsive-sm ">
    <table class="table table-sm table-bordered  table-striped ">
        <thead>
            <tr class="text-center text-sm bg-info elevation-1">
                <th scope="col" data-toggle="tooltip" data-placement="top" title="ID do item.">#</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Nome do item.">NOME</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Quantidade a ser inserida.">QTD</th>
                <th scope="col" data-toggle="tooltip" data-placement="top" title="Saldo atual do estoque.">SALDO</th>
                <th scope="col">AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['itens_insert_estoque'] as $key => $value) {  ?>
            <tr class="text-center text-sm">
                <th scope="row" title="ID do item"><?php echo$key;?></th>
                <td title="Nome do item!"><?php echo$value['nome'];?></td>
                <td title="Quantidade a ser inserida!"><?php echo$value['qtd_insert'];?></td>
                <td title="Saldo atual do estoque!"><?php echo$value['qtd_atual'];?></td>
                <td><a class="btn btn-xs excluir elevation-1" id="<?php echo$key;?>" title="Excluir item!"><i class="fas fa-times" style="font-size: 18px; color: red"></a></i></td>
            </tr>
            <?php } ?>
            <tr class="text-center text-sm bg-info elevation-1">
                <th></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

</script>