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
    <link rel="stylesheet" href="./CSS/styleForm.css">
    <title>Almoxarifado Manutenção</title>
</head>
<body>

    <div id="positionBtnLeft" style="width: 20%; position: absolute; left: 0px;" class='ps-1'>
        <a href="index.html" class='btn btn-outline-light px-3 py-1 m-1 bi bi-house'></a>
    </div>
    <div id="positionBtnRight" style="width: 20%; position: absolute; right: 0px;" class="pe-1">
    <?php
        echo"<form action='operation.php' method='POST'>
                <div class='clearfix'>
                    <div class='d-flex float-end'>
                        <button class='btn btn-slim btn-outline-light px-3 py-1 m-1' type='submit' name='btnRegister' value='" . $item . "'>Cadastrar</button>
                    </div>
                </div>
            </form>";
    ?>
    </div>
     
    <header class="mb-5 d-flex bg-dark">
        <?php
            if($item == 'ds2'){
                $headDs2 = 'active';
                $headK18 = $headRes = "text-light";
            } else if ($item == 'k18') {
                $headK18 = 'active';
                $headDs2 = $headRes = "text-light";
            } else if ($item == 'resistencia') {
                $headRes = 'active';
                $headDs2 = $headK18 = "text-light";
            }
            echo " 
                <form action='operation.php' method='POST'>
                    <ul class='nav nav-tabs pt-1 d-flex justify-content-center'>
                        <li class='nav-item'>
                            <button class='nav-link " . $headDs2 . "' type='submit' name='btnAcess' value='ds2'>DS-2</button>
                        </li>
                        <li class='nav-item'>
                            <button class='nav-link " . $headK18 . "' type='submit' name='btnAcess' value='k18'>K-18</button>
                        </li>
                        <li class='nav-item'>
                            <button class='nav-link " . $headRes . "' type='submit' name='btnAcess' value='resistencia'>Resistência</button>
                        </li>  
                    </ul>
                </form>         
                ";

            $conn->close();
        ?>
    </header>
    <main>
        <table class="table table-striped">
            <thead class="table-dark sticky-top">
                <tr>
                    <th scope="col">Código</th>
                    <?php
                        if($item != 'resistencia'){
                            echo "<th scope='col'>Nome da Peça</th>"; 
                        }
                    ?>
                    <th scope="col">Marca da Peça</th>
                    <?php
                        if($item == 'resistencia'){
                            echo "<th scope='col'>Tipo</th>"; 
                            echo "<th scope='col'>Medidas</th>";  
                        }
                    ?>
                    <th scope="col">Estoque min</th>
                    <th scope="col">Saldo</th>
                    <th scope="col" id='colsub' class="formAdd text-center px-2">Movimentar</th>
                    <th scope="col" id='colsub'>Histórico</th>
                </tr>
            </thead>
            <tbody>  
            <?php
                if (isset($_POST['alertStatus'])) {
                    if ($_POST['alertStatus'] === 'decremented') {
                        echo "<script>alert('Quantidade reduzida com sucesso!');</script>";
                    } elseif ($_POST['alertStatus'] === 'incremented') {
                        echo "<script>alert('Quantidade aumentada com sucesso!');</script>";
                    }
                }
            ?>            
            <?php
                include('reconhecer.php');
                
                if($item == 'ds2' || $item == 'k18'){
                    $insSQL = "SELECT cod, nome, marca, estq_min, saldo FROM $item ORDER BY nome ASC";
                } else if($item == 'resistencia') {
                    $insSQL = "SELECT cod, tipo, marca, estq_min, medidas, saldo FROM $item ORDER BY nome ASC";
                }

                

                $res = $conn->query($insSQL);
            
                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){

                        if($row['saldo'] < $row['estq_min']){
                            $saldoMenor = 'text-danger';
                        } else {
                            $saldoMenor = "";
                        }
                        echo "<tr>";
                        if($item == 'ds2' || $item == 'k18'){
                            echo "<td>" . $row['cod'] . "</td>";
                            echo "<td class='text-uppercase'>" . $row['nome'] . "</td>";
                            echo "<td class='text-uppercase'>" . $row['marca'] . "</td>";
                            echo "<td>" . $row['estq_min'] . "</td>";
                            echo "<td class='estClass " . $saldoMenor . "'>" . $row['saldo'] . "</td>";
                            echo "
                                <td>
                                    <form action='atualizarQntd.php' method='POST'>
                                        <div class='d-flex justify-content-around'>
                                            <input class='inpAlter' min='1' type='number' value='1' name='qntdAlter'>
                                            <button type='submit' name='increment' value='" . $row['cod'] . "' class='btn btn-success'>+</button>

                                            <input type='hidden' name='bd' value='" . $item . "'>
                                            <input type='hidden' name='vfSaldo' value='" . $row['saldo'] . "'>  

                                            <button type='submit' name='decrement' value='" . $row['cod'] . "' class='btn btn-danger'> - </button>
                                        </div>
                                    </form>
                                </td>";
                            echo "<form action='historico.php' method='GET'>
                                    <td class='editar'>
                                        <button class='bi bi-box-arrow-up-right' type='submit' name='edicao' value='". $row['cod'] ."'></button>
                                        <input type='hidden' name='opcao' value='". $item . "'>
                                    </td>
                                </form>";
                        }
                        if($item == 'resistencia'){
                            echo "<td>" . $row['cod'] . "</td>";
                            echo "<td class='text-uppercase'>" . $row['marca'] . "</td>";
                            echo "<td>" . $row['tipo'] . "</td>";
                            echo "<td>" . $row['medidas'] ."</td>";
                            echo "<td>" . $row['estq_min'] . "</td>";
                            echo "<td class='" . $saldoMenor . "'>" . $row['saldo'] . "</td>";
                            echo "
                                <td>
                                    <form action='atualizarQntd.php' method='POST'>
                                        <div class='d-flex justify-content-around'>
                                            <input class='inpAlter' min='1' type='number' value='1' name='qntdAlter'>
                                            <button type='submit' name='increment' value='" . $row['cod'] . "' class='btn btn-success'>+</button>

                                            <input type='hidden' name='bd' value='" . $item . "'>
                                            <input type='hidden' name='vfSaldo' value='" . $row['saldo'] . "'>  

                                            <button type='submit' name='decrement' value='" . $row['cod'] . "' class='btn btn-danger'> - </button>
                                        </div>
                                    </form>
                                </td>";
                                echo "<form action='historico.php' method='GET'>
                                        <td class='editar'>
                                            <button class='bi bi-box-arrow-up-right' type='submit' name='edicao' value='". $row['cod'] ."'></button>
                                            <input type='hidden' name='opcao' value='". $item . "'>
                                        </td>
                                    </form>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table></main>";
                }else {
                    echo "</tbody></table>";
                    echo "<p class='lead text-center'>Nada Adicionado</p>";
                }
                $conn->close();
            ?>
        </table>
</body>
</html>


