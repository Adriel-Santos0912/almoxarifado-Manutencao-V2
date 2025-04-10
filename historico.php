<?php 
include('conexao.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $codigo = $_GET['edicao'];
    $equipamento = $_GET['opcao'];

    // SELECT que pegará todos os dados da tabela do equipamento específico no SQL  -- Tabela no topo da página
    if($equipamento == 'resistencia'){
        $espelho = "SELECT cod, marca, medidas, tipo, estq_min, saldo, data_cadastro from $equipamento WHERE cod= '$codigo'";
    } else {
        $espelho = "SELECT cod, nome, marca, estq_min, saldo, data_cadastro from $equipamento WHERE cod= '$codigo'";
    }
    $resEspelho = $conn->query($espelho);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" defer></script>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="CSS/historiCSS.css">
    <title>Cadastrar Item</title>
</head>
<body>
    <div id="floatbtn" class='d-flex p-2'>
        <?php
            echo "<form action='operation.php' method='POST'>";
            echo "<button class='btn btn-outline-light btn-sm px-3 py-2 fs-6 bi bi-arrow-return-left' type='submit' name='btnAcess' value='". $equipamento . "'></button>";
            echo "</form>";
        ?>
        <a href="index.html" class='btn btn-outline-light px-3 py-2 mx-2 bi bi-house'></a>
    </div>   
    <header class="bg-dark d-flex justify-content-center align-items-center">
        <h1 class="text-white">Histórico de Movimentações</h1>
        <div class="delete d-flex mx-2 gap-2">
            <button type="button" id="btnEditar" class='btn btn-primary px-3 py-2 bi bi-pencil-square'></button>
            <button type="button" id="editCheck" class="btn btn-success px-3 py-2 bi bi-check-lg" hidden disabled></button>
            <button type="button" id="editDel" class="btn btn-danger px-3 py-2 bi bi-x-lg" hidden></button>
            <button type="button" id="btTrash" class='btn btn-danger px-3 py-2 bi bi-trash3'></button>
        </div>
    </header>
    <main>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="colCod">Codigo</th>
                <?php
                    if($equipamento != 'resistencia'){
                        echo "<th scope='col'>Nome da Peça</th>"; 
                    }
                ?>
                <th scope="col">Marca da Peça</th>
                <?php
                    if($equipamento == 'resistencia'){
                        echo "<th scope='col'>Tipo</th>"; 
                        echo "<th scope='col'>Medidas</th>";  
                    }
                ?>
                <th scope="col">Estoque mínimo</th>
                <th scope="col">Saldo</th>
                <th scope="col">Cadastrado em</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if($resEspelho->num_rows > 0){
                    while($row = $resEspelho->fetch_assoc()){
                        $transDataCadastro = strtotime($row['data_cadastro']);
                        $dataCadastro = date('d/m/Y', $transDataCadastro);
                        echo "<tr>";
                        echo "<form id='formPeca' action='edit.php' method='GET'>";
                        $codRef = $_GET['edicao'];
                        $equipRef = $_GET['opcao'];
                        echo "<input type='hidden' name='edicao' value='$codRef'>";
                        echo "<input type='hidden' name='opcao' value='$equipRef'>";
                        echo "<input type='hidden' id='formCheck'  name='editor' value='check' disabled>";
                        echo "<input type='hidden' id='formTrash'  name='editor' value='trash' disabled>";
                        
                        echo "<td>
                        <input class='editField tdSmall' type='text' name='codigo' value='" . $row['cod'] ."' disabled required></td>";
                        if($equipamento != 'resistencia'){
                            echo "<td>
                            <input class='editField' type='text' name='nome' value='" . $row['nome'] . "' disabled required></td>";
                        }
                        echo "<td>
                        <input class='editField' type='text' name='marca' value='" . $row['marca'] . "' disabled required></td>";
                        if($equipamento == 'resistencia'){
                            echo "<td>
                            <input class='editField tdSmall' type='text' name='tipo' value='" . $row['tipo'] . "' disabled required></td>";
                            echo "<td>
                            <input class='editField tdSmall' type='text' name='medidas' value='" . $row['medidas'] ."' disabled required></td>";
                        }
                        echo "<td>
                        <input class='editField tdSmall' type='text' name='estoque_min' value='" . $row['estq_min'] . "' disabled required></td>";
                        echo "<td class='tdSmall'>" . $row['saldo'] . "</td><br>";
                        echo "<td>". $dataCadastro ."</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </tbody>
    </table>
    <section>
        <h2>Histórico</h2>
        <hr>
        <?php
        // PEGANDO TODAS AS MODIFICAÇÕES
        $selectData = "SELECT cod, equipamento, saldo_comeco, saldo_final, alteracao, data_modificacao, DATE(data_modificacao) AS apenas_data
                    FROM log
                    WHERE cod = ? AND equipamento = ?
                    ORDER BY data_modificacao DESC";
        $stmt = $conn->prepare($selectData);
        $stmt->bind_param('ss', $codigo, $equipamento);
        $stmt->execute();
        $resData = $stmt->get_result();

        $dataAtual = '';
        if($resData->num_rows > 0){
            while($row = $resData->fetch_assoc()){
                if($dataAtual != $row['apenas_data']){
                    if($dataAtual != ''){
                        echo "</ul>";
                        echo "</div><hr>";
                    }
                    $dataAtual = $row['apenas_data'];
                    $dataBD = strtotime($row['apenas_data']);
                    $dataBR = date('d/m/Y', $dataBD);
                    echo "<div>";
                    echo "<h4>" . $dataBR . "</h4>";
                    
                    echo "<p class='saldoFinal text-light bg-dark'>Saldo final do dia: " . $row['saldo_final'] . "</p>";
                    echo "<ul class='historic_list'>";
                }
                // FORMATANDO
                $dataTransformada = strtotime($row['data_modificacao']);
                $hora = date('H:i', $dataTransformada);
                $num = (int) $row['alteracao'];
                
                // ESTRUTURA
                if($num > 0){
                    echo "<li class='text-success'>";
                    echo "Adicionado: " . $row['alteracao'];
                    echo "<span><i class='bi bi-clock'></i> " . $hora ."</span>";
                    echo "</li>";
                } else{
                    echo "<li class='text-danger'>";
                    echo "Retirado: " . $row['alteracao'];
                    echo "<span><i class='bi bi-clock'></i> " . $hora ."</span>";
                    echo "</li>";
                }
                $saldoComeco = $row['saldo_comeco'];
            }
            echo "</ul>";
            echo "<p class='saldoFinal text-light bg-dark mt-3 py-2'>Saldo inicial: " . $saldoComeco . "</p>";
            echo "</div>";
            echo "<hr>";
        } else {
            echo "<p class='lead text-center'>Sem Alterações</p>";
        }
        ?>
    </section>
</body>
</html>