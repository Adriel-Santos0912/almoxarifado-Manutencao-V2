<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" defer></script>
    <link rel="stylesheet" href="CSS/ItemCadastrado.css">
    <title>Item Cadastrado!</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-5 text-center">Item cadastrado com <span class="text-success">Sucesso!</span></h1>
            </div>
            <div class="col-12 d-flex justify-content-center pt-4">
                <div class="box shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <?php
                    include('conexao.php');

                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        $item = $_POST['maq'];
                        $codigo = $_POST['codigo'];
                        if($item != 'resistencia'){
                            $namePeca = $_POST['peca'];
                        }
                        $saldo = $_POST['saldo'];
                        $marca = $_POST['marca'];
                        $estqMin = $_POST['estoque'];
                        if(isset($_POST['medidas'])){
                            $medidas = $_POST['medidas'];
                            $tipoRes = $_POST['tipo'];
                        }
                        date_default_timezone_set('America/Sao_Paulo');
                        $dataCadastro = date('Y-m-d');
                
                        if($item == 'resistencia') {
                            $stmt = $conn->prepare("INSERT INTO $item(cod, marca, tipo, medidas, estq_min, saldo, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssiis", $codigo, $marca, $tipoRes, $medidas, $estqMin, $saldo, $dataCadastro);
                        } else {
                            $stmt = $conn->prepare("INSERT INTO $item(cod, nome, marca, estq_min, saldo, data_cadastro) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("sssiis", $codigo, $namePeca, $marca , $estqMin, $saldo, $dataCadastro);
                        }

                        if($stmt->execute()){
                            if($item == 'resistencia'){
                                echo "
                                <h3 class='text-center border-bottom'>Descrição do item</h3>
                                <p>Código: $codigo</p>
                                <p>Marca: $marca</p>
                                <p>Tipo Resistência: $tipoRes</p>
                                <p>Medidas: $medidas</p>
                                <p>Estoque Minimo: $estqMin</p>
                                <p>Quantidade: $saldo</p>";
                            } else {
                                echo "
                                <h3 class='text-center border-bottom'>Descrição do item</h3>
                                <p>Código: $codigo</p>
                                <p>Peça: $namePeca</p>
                                <p>Marca: $marca</p>
                                <p>Estoque Minimo: $estqMin</p>
                                <p>Quantidade: $saldo</p>";
                            }

                            //depois de cadastrado
                            if($item == 'ds2'){
                                echo "
                                    <form action='operation.php' method='POST'>
                                        <div class='btnCadastro d-flex flex-column pt-1 pb-1'>
                                            <button class='d-flex justify-content-center btn btn-warning mb-2 mt-2' type='submit' name='btnRegister' value='" . $item . "'>Cadastrar outro item</button>
                                            <button class='d-flex justify-content-center btn btn-info mb-2 mt-2' type='submit' name='btnAcess' value='" . $item . "'>Acessar planilha</button>
                                            <a class='d-flex justify-content-center btn text-success mb-2 mt-2' href='index.html'>Voltar ao início</a>
                                            
                                        </div>
                                    </form>"
                                ;
                            } else if($item == 'k18') {
                                echo "
                                    <form action='operation.php' method='POST'>
                                        <div class='btnCadastro d-flex flex-column pt-1 pb-1'>
                                            <button class='d-flex justify-content-center btn btn-warning mb-2 mt-2' type='submit' name='btnRegister' value='" . $item . "'>Cadastrar outro item</button>
                                            <button class='d-flex justify-content-center btn btn-info mb-2 mt-2' type='submit' name='btnAcess' value='" . $item . "'>Acessar planilha</button>
                                            <a class='d-flex justify-content-center btn text-success mb-2 mt-2' href='index.html'>Voltar ao início</a>
                                            
                                        </div>
                                    </form>"
                                ;
                            } else if($item == 'resistencia') {
                                echo "
                                    <form action='operation.php' method='POST'>
                                        <div class='btnCadastro d-flex flex-column pt-1 pb-1'>
                                            <button class='d-flex justify-content-center btn btn-warning mb-2 mt-2' type='submit' name='btnRegister' value='" . $item . "'>Cadastrar outro item</button>
                                            <button class='d-flex justify-content-center btn btn-info mb-2 mt-2' type='submit' name='btnAcess' value='" . $item . "'>Acessar planilha</button>
                                            <a class='d-flex justify-content-center btn text-success mb-2 mt-2' href='index.html'>Voltar ao início</a>
                                            
                                        </div>
                                    </form>"
                                ;
                            } 
                        }
                    } else {
                        echo "ERRO: " . $stmt->error;
                    }
                    $stmt-> close();
                    $conn->close();
                ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>