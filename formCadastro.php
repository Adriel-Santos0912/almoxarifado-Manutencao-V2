<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/stylecadastro.css">
    <link href="bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" defer></script>
    
    <title>Cadastrar Peças</title>
</head>
<body class="bg-light">
    <header class="mb-5 bg-dark d-flex px-2">
        <div id="floatbtn" class='float-start p-1 my-1'>
            <a href="index.html" class='btn btn-outline-light px-3 py-1 bi bi-house'></a>
        </div>
        <div id="h1" class="d-flex justify-content-center">
            <?php
                echo "<h1 class='text-white text-center mb-0'>Cadastro " . $selected . "</h1>";
            ?>
        </div>
    </header>
    <main class="d-flex justify-content-center">
        <form class="container" method="POST" action="cadastrar.php">
            <div class="row">
                <!-- o Código da peça pode incluir Letras e Numeros -->
                <div class="col-12 col-xl-6">
                    <label for="codigo">Código:</label><br>
                    <input class=" form-control form-control-lg" type="text" name="codigo" placeholder="Código da peça" required><br><br>
                </div>

                <div class="col-12 col-xl-6">
                    <label for="marca">Marca da Peça:</label><br>
                    <input class=" form-control form-control-lg" type="text" name="marca" placeholder="Insira a marca da peça aqui" required><br><br>
                </div>
                <?php
                if($item != 'resistencia'){
                    echo "
                    <div class='col-12'>
                        <label for='peca'>Nome da Peça:</label><br>
                        <input class='form-control form-control-lg' type='text' name='peca' placeholder='Insira o nome da peça aqui' required><br><br> 
                    </div>";
                }
                ?>
                <div class="col-12 col-xl-6">
                    <label for="estoque">Estoque Minimo:</label><br>
                    <input class="form-control form-control-lg" type="number" name="estoque" placeholder="Minimo de estoque" required><br><br>
                </div>

                <div class="col-12 col-xl-6">
                    <label for="saldo">Saldo da Peça:</label><br>
                    <input class="form-control form-control-lg" type="number" name="saldo" placeholder="Quantidade no estoque" required><br><br>
                </div>          
                <?php
                    if($item == 'resistencia'){
                    echo
                    "
                    <div class='col-12 col-xl-6 d-flex flex-column justify-content-start>
                        <label for='tipo'>Tipo da Resistência: </label>
                        <select class='shadow-none btn btn-secondary dropdown-toggle' name='tipo' required>
                            <option class='dropdown-item' value='RETA' selected>RETA</option>
                            <option class='dropdown-item' value='X'>X</option>
                            <option class='dropdown-item' value='Ø'>Ø</option>
                        </select>
                    </div>
                    <div class='col-12 col-xl-6'>
                        <label for='medida'>Medidas:</label><br>
                        <input class=' form-control form-control-lg' type='text' name='medidas' placeholder='Ex: 2x15cm 1x30cm' required>
                    </div>
                    ";
                    }   
                ?>              
                <?php
                    if($item == 'ds2'){
                        echo "<input type='hidden' value='ds2' name='maq'>";
                    } else if($item == 'k18') {
                        echo "<input type='hidden' value='k18' name='maq'>";
                    } else if($item == 'resistencia') {
                        echo "<input type='hidden' value='resistencia' name='maq'>";
                    } else {
                        echo "ERRO! Valor invalido";
                    }   
                ?>
                <div class='d-flex justify-content-center mt-5' id="enviar">
                    <input class="btn btn-info py-2 ps-5 pe-5" type="submit" value="Enviar">
                </div>
            </div>
        </form>
    </main>
</body>
</html>
