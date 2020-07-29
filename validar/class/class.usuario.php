<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');

class Usuario extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

   /* trocar avatar */
   function atualizaAvatar( $id_usuario, $avatar ){
        $sql = "UPDATE usuarios SET `avatar` = '$avatar' WHERE id = $id_usuario";
        mysqli_query( $this->link, $sql );
   }

}
