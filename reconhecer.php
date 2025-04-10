<?php
include('conexao.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['btnAcess'])){
        $btn = "acessar";
        $item = $_POST['btnAcess'];
    } else if(isset($_POST['btnRegister'])){
        $btn = "cadastrar";
        $item = $_POST['btnRegister'];
    } else if(isset($_POST['btnLamina'])){
        $btn = "laminas";
    } else {
        echo "ERRO INESPERADO!";
    }

    if($btn == "acessar" || $btn == "cadastrar"){
        $selected = ($item == 'ds2') ? 'DS-2' :
        (($item == 'k18') ? 'K-18' :
        (($item == 'resistencia') ? 'Resistencia' : 'Undefined'));
    }
}