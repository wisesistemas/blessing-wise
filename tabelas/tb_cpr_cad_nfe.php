<?php
session_start();

?>
<legend class="w-auto legend">Dados dos Produtos e Serviços</legend>
<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr class="text-center bg-info">
                <th scope="col">Cod Fornecedor</th>
                <th scope="col">Descrição - Fornecedor</th>
                <th scope="col">Item - WISE</th>
                <th scope="col">Qtd.</th>
                <th scope="col">UNI</th>
                <th scope="col">Valor(R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php  foreach ($_SESSION['nf_array_item'] as $key => $value) { ?>
            <tr class="text-center">
                <td><small><?php echo$value[$key]['item_forn_cod']; ?></small></td>
                <td><small><?php echo$value[$key]['item_forn_nome']; ?></small></td>
                <td><small><?php echo$value[$key]['item_nome_wise']; ?></small></td>
                <td><small><?php echo$value[$key]['item_qtd']; ?></small></td>
                <td><small><?php echo$value[$key]['item_forn_uni']; ?></small></td>
                <td><small><?php echo$value[$key]['valor_unit']; ?></small></td>
                <td><i class="fas fa-trash-alt text-danger deletar_item" id="<?php echo'excluiritem*'.$key; ?>"></i></td>
            </tr>
            <?php } ?>
            <tr class="text-center bg-info">
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
<script>
    /*  $(".table tr:odd").css("background-color", "#ffff");
    $(".table tr:even").css("background-color", "#f4edd5"); */
    $(".table tr:odd").css("background-color", "#f4edd5");
    $(".table tr:even").css("background-color", "#ffff");
</script>