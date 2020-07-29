<?php
require_once("db.class.php");
date_default_timezone_set('America/Sao_Paulo');

class Prmt extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    function relaciona($unidade){
        $sql = "SELECT id FROM cad_item ORDER BY id";
        $sql = mysqli_query($this->link, $sql);
        while ( $sqlItem =  mysqli_fetch_array($sql)) {
            $sql1 = "SELECT item FROM item_unidade WHERE unidade = $unidade AND item = $sqlItem[id] order by item;";
            $sql1 = mysqli_query($this->link, $sql1);
            if(mysqli_affected_rows($this->link) == 0 ){
                echo"<br>";
                echo $sqlItem['id'];
            }
        }   
    }
 
}

/* $prmt = new Prmt();
$prmt->relaciona(24); */