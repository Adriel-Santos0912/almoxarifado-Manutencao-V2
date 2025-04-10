<?php
include('conexao.php');

if(isset($_GET['condicao'])){
    $maqCond = $_GET['condicao'];
    $action = substr($maqCond, 0, 4);
    $number = substr($maqCond, 4, 3);

    // NOVA = 0
    // VIRA = 1

    if($action == 'nova' && $number == 426){
        $updSQL = 'UPDATE lamina SET lamina426 = 0 WHERE id = 1'; // CLICAR EM NOVA, O VALOR É ATUALIZADO PARA 0 ONDE O ID FOR = 1 (QUE NO CASO É O UNICO ID)
    } else if($action == 'nova' && $number == 427) {
        $updSQL = 'UPDATE lamina SET lamina427 = 0 WHERE id = 1'; 
    }
    
    if($action == 'vira' && $number == 426) {
        $updSQL = 'UPDATE lamina SET lamina426 = 1 WHERE id = 1'; // CLICAR EM VIRA, O VALOR É ATUALIZADO PARA 1 ONDE O ID FOR = 1 (QUE NO CASO É O UNICO ID)
    } else if($action == 'vira' && $number == 427) {
        $updSQL = 'UPDATE lamina SET lamina427 = 1 WHERE id = 1';
    }

    $envSQL = $conn->query($updSQL);
}

$binSQL = 'SELECT * FROM lamina';
$selecionar = $conn->query($binSQL);

if($selecionar->num_rows > 0){
    while($row = $selecionar->fetch_assoc()){
        if($row['lamina426'] == 0){
            $nova426 = "btn-success";
            $vl426 = "btnDisab";
        } else {
            $nova426 = "btnDisab";
            $vl426 = "btn-success";
        }
        if($row['lamina427'] == 0){
            $nova427 = "btn-success";
            $vl427 = "btnDisab";
        } else {
            $nova427 = "btnDisab";
            $vl427 = "btn-success";
        }
    }
} else {
    $forceInject = 'INSERT INTO lamina (lamina426, lamina427) VALUES (0, 0)';
    $queryInject = $conn->query($forceInject);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" defer></script>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js" defer></script>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="CSS/styleLaminas.css">
    <title>Cadastrar Peças</title>
</head>
<body>
    <header class="bg-dark d-flex px-2">
    <div id="floatbtn" class='float-start p-1 my-1'>
            <a href="index.html" class='btnHome btn btn-outline-light px-3 py-2 bi bi-house'></a>
        </div>
        <div id="h1" class="d-flex justify-content-center">
            <h1 class="text-white text-center">Controle de Lâminas</h1>
        </div>
    </header>
    <main>
        <div class="container">
            <form action="controleLaminas.php" method='GET'>
                <div class="d-flex align-items-center flex-wrap justify-content-center justify-content-md-around">
                    <div class="shadow-lg p-3 mb-5 bg-body-tertiary rounded p-5">
                        <h1 class="text-dark border-bottom border-dark-subtle">Maquina 426</h1>
                        <br>
                        <div class="d-flex justify-content-center align-items-center p-1">
                            <?php              
                                echo "<button type='submit' name='condicao' value='nova426' class='btn ". $nova426 . "'>Nova</button>";
                            ?>
                        </div>
                        <div class="d-flex justify-content-center align-items-center p-1">
                            <?php
                                echo "<button type='submit' name='condicao' value='vira426' class='btn ". $vl426 . "'>Virar Lado</button>";
                            ?>
                        </div>
                    </div>

                    <div class="shadow-lg p-3 mb-5 bg-body-tertiary rounded p-5">
                        <h1 class="text-dark border-bottom border-dark-subtle">Maquina 427</h1>
                        <br>
                        <div class="d-flex justify-content-center align-items-center p-1">
                            <?php
                                echo "<button type='submit' name='condicao' value='nova427' class='btn " . $nova427 . "'>Nova</button>";
                            ?>
                        </div>
                        <div class="d-flex justify-content-center align-items-center p-1">
                            <?php
                                echo "<button type='submit' name='condicao' value='vira427' class='btn " . $vl427 . "'>Virar Lado</button>";
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>