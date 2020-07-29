<?php
    $tipoDeImpressao = intval($_POST['tipo']);
    
    if($tipoDeImpressao == 1 ){ /* Impressão: Folha de separação */
?>
<div class="embed-responsive embed-responsive-1by1" style="height: 450px">
    <iframe class="embed-responsive-item" src="<?php echo$_POST['id'];?>" allowfullscreen></iframe>
 </div> 
 <?php }else if($tipoDeImpressao == 2){/* Impressão: Folha de transporte */?>
  <div >  
    <div class="embed-responsive embed-responsive-1by1" style="height: 450px">
        <iframe class="embed-responsive-item" src="impressao/nota_transporte.php?req=<?php echo$_POST['id']; ?>" style="height: 450px"></iframe>
    </div>
 </div>
 <?php }else if($tipoDeImpressao == 3){/* Impressão: folha_separacao_pendencia */
        $id = explode('req=',$_POST['id']);?>
    <div >  
    <div class="embed-responsive embed-responsive-1by1" style="height: 450px">
    <iframe class="embed-responsive-item" src="impressao/folha_separacao_pendencia.php?req=<?php echo$id[1]; ?>" style="height: 450px"></iframe>    </div>
 </div>
 <?php }else if($tipoDeImpressao == 4){/* Impressão: folha_separacao_pendencia */ ?>
    <div >  
        <div class="embed-responsive embed-responsive-1by1" style="height: 450px">
        <iframe class="embed-responsive-item" src="impressao/re_nota_transporte.php?req=<?php echo$_POST['id'];?>" style="height: 450px"></iframe>    </div>
    </div
 <?php }?>