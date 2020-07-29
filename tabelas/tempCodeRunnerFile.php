<?php
$file_tmp   = $_FILES['xml_exemplo']['tmp_name'];
$object     = simplexml_load_file($file_tmp);