<?php
	
	class nomeItem{
        
       

        	public function nome( $id ){ 

                        require_once('db.class.php');               
                	$objDb = new db();
                        $link = $objDb->conecta_mysql();

                        $sql1 = "SELECT i.nome as nome, i.referencia as referencia, i.marca as marca, m.medida as uni 
                        FROM cad_item i 
                        INNER JOIN cad_medida m 
                        ON i.und_estoque = m.id
                        where i.id = $id";
                        $sql1 = mysqli_query( $link, $sql1 );
                        $item = mysqli_fetch_array( $sql1 );

                        if( $item['referencia'] ){
                        	$referencia = "| Ref:".$item['referencia']."";
                        }else{
                        	$referencia = '';
                        }

                        if( $item['marca'] ){
                        	$marca = "| Marca:".$item['marca']."";
                        }else{
                        	$marca = '';
                        }


                       

                        $nome = $item['nome']." $referencia $marca ";


                       return $nome;
        	}
        }

?>