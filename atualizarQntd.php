<?php
include('conexao.php');

$bdSelect = $_POST['bd'];
$saldo = $_POST['vfSaldo'];
$qntdIncr = $_POST['qntdAlter'];
$checkLog = 0;

// Define o fuso horário do PHP (boa prática, mesmo usando NOW())
date_default_timezone_set('America/Sao_Paulo');

// Define o código do item
$codigo = isset($_POST['increment']) ? $_POST['increment'] : $_POST['decrement'];

// Consulta para obter informações do item
if ($bdSelect == 'resistencia') {
    $insSQL = "SELECT cod, marca, medidas, saldo, tipo FROM $bdSelect WHERE cod = ?";
} else {
    $insSQL = "SELECT cod, nome, marca, saldo FROM $bdSelect WHERE cod = ?";
}

$stmt = $conn->prepare($insSQL);
$stmt->bind_param("s", $codigo);
$stmt->execute();
$res = $stmt->get_result();

// Verifica se o item existe
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $marca = $row['marca'];
    $saldoComeco = $row['saldo'];
    $saldoFinal = $row['saldo'];

    if ($bdSelect != 'resistencia') {
        $namePeca = $row['nome'];
    } else {
        $medidas = $row['medidas'];
        $tipo = $row['tipo'];
    }
}

// Atualiza saldo e registra log
if (isset($_POST['decrement'])) {
    if ($saldo > 0 && $qntdIncr <= $saldo) {
        $saldoFinal -= $qntdIncr;
        $valAlterado = "-" . $qntdIncr;

        $stmt = $conn->prepare("UPDATE $bdSelect SET saldo = saldo - ? WHERE cod = ?");
        $stmt->bind_param("is", $qntdIncr, $codigo);
        $stmt->execute();
        $checkLog = 1;
    } else {
        echo "<script>alert('Saldo zerado ou insuficiente! Operação não realizada.');</script>";
    }
} elseif (isset($_POST['increment'])) {
    $saldoFinal += $qntdIncr;
    $valAlterado = "+" . $qntdIncr;

    $stmt = $conn->prepare("UPDATE $bdSelect SET saldo = saldo + ? WHERE cod = ?");
    $stmt->bind_param("is", $qntdIncr, $codigo);
    $stmt->execute();
    $checkLog = 1;
}

if ($checkLog == 1) {
    $stmtLogSQL = ($bdSelect == 'resistencia')
        ? "INSERT INTO log(cod, nome, marca, medidas, tipo, alteracao, saldo_comeco, saldo_final, equipamento, data_modificacao)
           VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, NOW())"
        : "INSERT INTO log(cod, nome, marca, medidas, tipo, alteracao, saldo_comeco, saldo_final, equipamento, data_modificacao)
           VALUES (?, ?, ?, NULL, NULL, ?, ?, ?, ?, NOW())";

    $stmtLog = $conn->prepare($stmtLogSQL);

    if ($bdSelect == 'resistencia') {
        $stmtLog->bind_param("sssssiis", $codigo, $marca, $medidas, $tipo, $valAlterado, $saldoComeco, $saldoFinal, $bdSelect);
    } else {
        $stmtLog->bind_param("sssiiss", $codigo, $namePeca, $marca, $valAlterado, $saldoComeco, $saldoFinal, $bdSelect);
    }

    $stmtLog->execute();
    $checkLog = 0;
}

$showAlert = '';
if (isset($_POST['decrement'])) {
    $showAlert = 'decremented';
} elseif (isset($_POST['increment'])) {
    $showAlert = 'incremented';
}

echo "
<form id='select' action='operation.php' method='POST'>
    <input type='hidden' name='btnAcess' value='" . $bdSelect . "'>
    <input type='hidden' name='alertStatus' value='" . $showAlert . "'>
</form>
<script>
    document.querySelector('#select').submit();
</script>
";


$stmt->close();
$stmtLog->close();
$conn->close();
?>
