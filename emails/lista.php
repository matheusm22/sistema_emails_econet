<?php

// SESSÃO RESPONSÁVEL PELO LOGIN
session_start();

// Limpara o buffer de redirecionamento
ob_start();

include_once('config.php');

// Incluir o arquivo para validar e recuperar dados do token
include_once 'validar_token.php';

// Chamar a função validar o token, se a função retornar FALSE significa que o token é inválido e acessa o IF
if (!validarToken()) {
    // Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Necessário realizar o login para acessar a página!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: /emails/index.php");

    // Pausar o processamento da página
    exit();
}

//print_r($_SESSION);

// $logado = $_SESSION['user'];
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM emails WHERE (id ='$data' or nome LIKE '%$data%')
         and setor = 'Comercial Wesley' and ativo = 'Sim' ORDER BY nome ASC";
} else {
    $set = $_SESSION['setor'];
    $sql = "SELECT * FROM emails WHERE setor = 'Comercial Wesley' 
        and ativo = 'Sim' ORDER BY nome ASC ";
}
if (empty($_SESSION['setor'])) {
    $sql = "SELECT * FROM emails WHERE setor = 'Comercial Wesley' 
        and ativo = 'Sim' ORDER BY nome ASC ";
}

$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="download.ico" type="image/x-icon">
    <title>ECONET - Registros</title>
    <style>
        body {
            background-image: url(fundo_econet.png);
            background-position: 0px -50px;
            background-size: cover;
            color: white;
            text-align: center;
        }

        table td {
            border: none !important;
        }

        .table-bg {
            background-image: linear-gradient(to right, #E70808 30%, #E78608, #E1D209);
            border-radius: 15px 15px 15px 15px;
        }

        .box-search {
            display: flex;
            justify-content: center;
            gap: .1%;
        }

        .d-flex {
            padding-right: 20px;
        }

        #oculta-input {
            border: none;
            outline: none;
            background-color: transparent;
            width: 60px;
        }
    </style>
</head>

<body>
    </nav>
    <br>
    <div class="d-flex">
        <a href="sair.php" class="btn btn-danger  me-2">Sair</a>
    </div>
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
        </button>
    </div>
    <div class="m-5">
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Sophia</th>
                    <th scope="col">Data da Criação</th>

                </tr>
            </thead>
            <tbody>
                <?php

                if ($result->num_rows == 0) {
                    echo '<td colspan="6">';
                    echo "Nenhum registro encontrado, tente novamente!!!</td>";
                }
                while ($user_data = mysqli_fetch_assoc($result)) {
                    // Separa as duas partes em um array, explode separada em um array toda vez que encontrar a ocorrencia, no caso ali espaço
                    $data = explode(' ', $user_data['dta_criacao']);

                    $hora = $data[1];
                    //Espaço na hora de imprimir
                    $space = ' ';

                    $fechado = '********';
                    $ss = $user_data['senha'];
                    $sop = $user_data['sophia'];

                    //'2023-05-26'  Transforma a data em um array também 
                    $dataCorreta = explode('-',  $data[0]);
                    //Inverte o array que está [2023,05,26] para [26,05,2023] 
                    $dataCorreta = array_reverse($dataCorreta);
                    // Junta o array com o delimitador / para uma string 
                    $dataCorreta = implode('/', $dataCorreta);
                    echo "<tr>";
                    echo "<td id='' >" . $user_data['id'] . "</td>";
                    echo "<td>" . $user_data['nome'] . "</td>";
                    echo "<td>" . $user_data['email'] . "</td>";
                    echo "<td><input type='password' id='oculta-input' readonly value='$ss'></td>";
                    echo "<td><input type='password' id='oculta-input' readonly value='$sop'></td>";
                    echo "<td>". $dataCorreta .$space . substr($hora, 0, 5) ."</td>";
                }

                ?>
            </tbody>
        </table>
    </div>
</body>
<script>
    var search = document.getElementById('pesquisar');

    function searchData() {
        window.location = 'visualiza.php?search=' + search.value;
    }

    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            searchData();
        }
    });

    function searchData() {
        window.location = 'visualiza.php?search=' + search.value;
    }
</script>

</html>