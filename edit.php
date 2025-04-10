<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar'])) {
    $senhaCorreta = 112469; 

    if (isset($_POST['senha']) && $_POST['senha'] == $senhaCorreta) {
        $rastreabilidade = $_POST['edicao'];
        $equipamento = $_POST['opcao'];

        $deletar = "DELETE FROM $equipamento WHERE cod = ?";
        $stmt = $conn->prepare($deletar);
        $deletarLog = "DELETE FROM log WHERE cod = ?";
        $stmtLog = $conn->prepare($deletarLog);

        if ($stmt) {
            $stmt->bind_param('s', $rastreabilidade);
            $stmtLog->bind_param('s', $rastreabilidade);
            $stmt->execute();
            $stmtLog->execute();


            if ($stmt->affected_rows > 0) {
                echo "
                <script> alert('Deletado com sucesso!') </script>
                <form id='select' action='operation.php' method='POST'>
                    <input type='hidden' name='btnAcess' value='" . $equipamento . "'>
                </form>
                ";

                echo "
                <script>
                    document.querySelector('#select').submit();
                </script>
                "; 
                exit();
            } else {
                echo "Nenhuma alteração realizada ou erro na consulta.<br>";
            }
            $stmt->close();
        } else {
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    }else {
        $rastreabilidade = $_GET['edicao'];
        $equipamento = $_GET['opcao'];
        echo "<script> alert('Senha Incorreta!') </script>";
        echo "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link href='./bootstrap-5.3.3-dist/css/bootstrap.min.css' rel='stylesheet'>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css'>
            <title>Editar</title>
        </head>
        <body class='vh-100 d-flex flex-column'>
        <header class='bg-dark d-flex justify-content-start align-items-center p-2 mb-5' >
            <form action='historico.php' method='GET' style='margin:0;'>
            <button class='btn btn-outline-light btn-sm px-3 py-2 fs-6 bi bi-arrow-return-left' type='submit' style='height:41.6px;'></button>
            <input type='hidden' name='edicao' value='$rastreabilidade'>
            <input type='hidden' name='opcao' value='$equipamento'><br>
            </form>
        </header>
        <div class='d-flex justify-content-center flex-column align-items-center'>
            <form action='' method='POST'>
                <h1 class=''>Digite a senha: </h1>
                <div class='d-flex justify-content-center flex-column align-items-center'>
                    <input id='fieldPass' class='form-control' type='password' name='senha' required>
                    <input type='hidden' name='edicao' value='$rastreabilidade'>
                    <input type='hidden' name='opcao' value='$equipamento'><br>
                    <button class='btn btn-dark' type='submit' name='confirmar' value='1'>Confirmar</button>
                </div>
            </form>
        </div>

        <script>
            window.addEventListener('load', function(){
                document.getElementById('fieldPass').focus();
            });
        </script>
        </body>
        </html>
        ";
    }
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $rastreabilidade = $_GET['edicao'];
    $equipamento = $_GET['opcao'];
    
    if(isset($_GET['editor']) && $_GET['editor'] == 'check'){
        $codigo = $_GET['codigo'];
        if($equipamento != 'resistencia'){
            $nome = $_GET['nome'];
        }
        $marca = $_GET['marca'];
        $estoqueMin = $_GET['estoque_min'];
        if($equipamento == 'resistencia'){
            $tipo = $_GET['tipo'];
            $medidas = $_GET['medidas'];

            $modificar = "UPDATE $equipamento SET 
                        cod = ?, 
                        marca = ?, 
                        tipo = ?,
                        medidas = ?,
                        estq_min = ? 
                        WHERE cod = ?";

            $modificarLog = "UPDATE log SET 
                        cod = ?, 
                        marca = ?, 
                        tipo = ?,
                        medidas = ?
                        WHERE cod = ?";
                
            $stmt = $conn->prepare($modificar);
            $stmtLog = $conn->prepare($modificarLog);

            $stmt->bind_param('ssssis', $codigo, $marca, $tipo, $medidas, $estoqueMin, $rastreabilidade);
            $stmtLog->bind_param('sssss', $codigo, $marca, $tipo, $medidas, $rastreabilidade);
        } else {
            $modificar = "UPDATE $equipamento SET 
                        cod = ?, 
                        nome = ?, 
                        marca = ?, 
                        estq_min = ? 
                        WHERE cod = ?";

            $modificarLog = "UPDATE log SET 
                        cod = ?, 
                        nome = ?, 
                        marca = ?
                        WHERE cod = ?";

            $stmt = $conn->prepare($modificar);
            $stmtLog = $conn->prepare($modificarLog);

            $stmt->bind_param('sssis', $codigo, $nome, $marca, $estoqueMin, $rastreabilidade);
            $stmtLog->bind_param('ssss', $codigo, $nome, $marca, $rastreabilidade);
        }
    
        if ($stmt) {
            $stmt->execute();
            $stmtLog->execute();
    
            if ($stmt->affected_rows > 0) {
                header("Location: historico.php?edicao=" . urlencode($codigo) . "&opcao=" . urlencode($equipamento));
                exit(); 
            } else {
                echo "Nenhuma alteração realizada ou erro na consulta.<br>";
            }
            $stmt->close();
        } else {
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    } else if (isset($_GET['editor']) && $_GET['editor'] == 'trash'){
        echo "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link href='./bootstrap-5.3.3-dist/css/bootstrap.min.css' rel='stylesheet'>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css'>
            <title>Document</title>
        </head>
        <body class='vh-100 d-flex flex-column'>
        <header class='bg-dark d-flex justify-content-start align-items-center p-2 mb-5'>
            <form action='historico.php' method='GET'>
            <button class='btn btn-outline-light btn-sm px-3 py-2 fs-6 bi bi-arrow-return-left' type='submit'></button>
            <input type='hidden' name='edicao' value='$rastreabilidade'>
            <input type='hidden' name='opcao' value='$equipamento'><br>
            </form>
        </header>
        <div class='d-flex justify-content-center flex-column align-items-center'>
            <form action='' method='POST'>
                <h1 class=''>Digite a senha: </h1>
                <div class='d-flex justify-content-center flex-column align-items-center'>
                    <input id='fieldPass' class='form-control' type='password' name='senha' required>
                    <input type='hidden' name='edicao' value='$rastreabilidade'>
                    <input type='hidden' name='opcao' value='$equipamento'><br>
                    <button class='btn btn-dark' type='submit' name='confirmar' value='1'>Confirmar</button>
                </div>
            </form>
        </div>

        <script>
            window.addEventListener('load', function(){
                document.getElementById('fieldPass').focus();
            });
        </script>
        </body>
        </html>
        ";
    }
}
