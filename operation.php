<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include('reconhecer.php');
    if($btn == "acessar"){
        include('dados.php');
    } else if ($btn == "cadastrar") {
        include('formCadastro.php');
    } else if ($btn == "laminas"){
        include('controleLaminas.php');    
    } else {
        echo "Invalido";
    }
}