<?php
session_start();
require_once 'conexao.php';

// Se não estiver logado, redireciona para a página de login
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$banco = new BancoDeDados();

// Recebe os parâmetros de busca se houver
$termoBuscaVencimento = isset($_GET['q_vencimento']) ? $_GET['q_vencimento'] : '';
$termoBuscaStatus = isset($_GET['q_status']) ? $_GET['q_status'] : '';

// Se houver busca por vencimento ou status, usa os respectivos métodos. Caso contrário, exibe todas as tarefas.
if (!empty($termoBuscaVencimento) && !empty($termoBuscaStatus)) {
    // Busca por vencimento e status
    $lista = $banco->buscarTarefasPorVencimentoEStatus($termoBuscaVencimento, $termoBuscaStatus);
} elseif (!empty($termoBuscaVencimento)) {
    // Busca somente por vencimento
    $lista = $banco->buscarTarefasPorVencimento($termoBuscaVencimento);
} elseif (!empty($termoBuscaStatus)) {
    // Busca somente por status
    $lista = $banco->buscarTarefasPorStatus($termoBuscaStatus);
} else {
    // Exibe todas as tarefas
    $lista = $banco->buscarTarefas();
}

if (isset($_GET['cidade']) && !empty($_GET['cidade'])) {
    $cidade = urlencode($_GET['cidade']); // Codifica o nome da cidade
    $apiKey = 'c3b7a852fe437627a2633c7528b95d88'; // Sua chave de API do OpenWeather
    $units = 'metric'; // Temperatura em Celsius
    $language = 'pt_br'; // Definir o idioma para português

    // URL da API para buscar o clima
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$cidade}&appid={$apiKey}&units={$units}&lang={$language}";

    // Faz a requisição para a API
    $resposta = file_get_contents($apiUrl);
    if ($resposta) {
        $dadosClima = json_decode($resposta, true);
    } else {
        $erroClima = 'Erro ao buscar informações de clima.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefa</title>
    <link rel="stylesheet" href="css/style.css">

    <script>
        function confirmarExclusao(nomeTarefa) {
            return confirm(`Tem certeza que deseja excluir a tarefa: "${nomeTarefa}"?`);
        }
    </script>
</head>

<body>
    <h1>Crie uma tarefa</h1>
    <div class="tarefa">
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Tarefa</label>
                <input type="text" name="tarefa" placeholder="insira o nome da tarefa">
            </div>
            <div class="form-group">
                <label for="email">Descrição</label>
                <input type="text" name="descricao" placeholder="insira a tarefa a ser feita">
            </div>
            <div class="form-group">
                <label for="data">Data</label>
                <input type="date" name="data" placeholder="insira a data de vencimento">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status">
                    <option value="pendente">Pendente</option>
                    <option value="andamento">Em andamento</option>
                    <option value="concluida">Concluída</option>
                </select>
            </div>
            <div class="submit">
                <button type="submit">Criar Tarefa</button>
            </div>
        </form>
    </div>

    <!-- 🔍 Campo de Busca por Data de Vencimento e Status -->
    <div class="busca">
        <h3>Buscar Tarefa</h3>
        <form action="" method="GET">
            <!-- Busca por vencimento -->
            <input type="date" name="q_vencimento" placeholder="Digite a data de vencimento">

            <!-- Busca por status -->
            <select name="q_status">
                <option value="">Selecione o Status</option>
                <option value="pendente">Pendente</option>
                <option value="andamento">Em andamento</option>
                <option value="concluida">Concluída</option>
            </select>

            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- 📋 Tabela de Tarefas -->
    <div>
        <table>
            <thead>
                <tr>
                    <th>TAREFA</th>
                    <th>DESCRIÇÃO</th>
                    <th>DATA</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($lista)) {
                    foreach ($lista as $tarefa) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($tarefa['nomeTarefa']) ?></td>
                            <td><?= htmlspecialchars($tarefa['descricao']) ?></td>
                            <td><?= htmlspecialchars($tarefa['vencimento']) ?></td>
                            <td><?= htmlspecialchars($tarefa['posicao']) ?></td>
                            <td><a href="editarTarefa.php?id=<?= htmlspecialchars($tarefa['idTarefa']) ?>"
                                    class="botao1">Editar</a></td>
                            <td>
                                <a href="excluirTarefas.php?id=<?= htmlspecialchars($tarefa['idTarefa']) ?>" class="botao1"
                                    onclick="return confirmarExclusao('<?= htmlspecialchars($tarefa['nomeTarefa']) ?>')">Excluir</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhuma tarefa encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div>
        <h4>Buscar Clima</h4>
        <form action="" method="GET">
            <input type="text" name="cidade" placeholder="Digite o nome da cidade"
                value="<?= isset($_GET['cidade']) ? $_GET['cidade'] : '' ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php
        

        if ($dadosClima && !isset($erroClima)) { ?>
            <h3>Clima em <?= htmlspecialchars($dadosClima['name']) ?>:</h3>
            <p>Descrição: <?= htmlspecialchars($dadosClima['weather'][0]['description']) ?></p>
            <p>Temperatura: <?= htmlspecialchars($dadosClima['main']['temp']) ?>°C</p>
            <p>Umidade: <?= htmlspecialchars($dadosClima['main']['humidity']) ?>%</p>
        <?php } elseif (isset($erroClima)) { ?>
            <p><?= $erroClima ?></p>
        <?php } ?>
    </div>

</body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tarefa = $_POST['tarefa'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $status = $_POST['status'];

    $banco->novaTarefa($tarefa, $descricao, $data, $status);
    $banco->fecharConexao();
}
?>

</html>