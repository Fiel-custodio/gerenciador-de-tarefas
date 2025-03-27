<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se um ID foi passado via GET
if (!isset($_GET['id'])) {
    echo "ID da tarefa não especificado.";
    exit();
}

$id = $_GET['id'];  // Pega o ID da URL
$banco = new BancoDeDados();
$tarefa = $banco->buscarTarefaPorId($id);

if (!$tarefa) {
    echo "Tarefa não encontrada.";
    exit();
}

// Atualiza a tarefa se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeTarefa = $_POST['tarefa'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $status = $_POST['status'];

    $banco->atualizarTarefa($id, $nomeTarefa, $descricao, $data, $status);
    
    // Redireciona de volta para a lista de tarefas após a edição
    header("Location: tarefas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Edite a tarefa</h1>
    <div class="tarefa">
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Tarefa</label>
                <input type="text" name="tarefa" value="<?= htmlspecialchars($tarefa['nomeTarefa']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Descrição</label>
                <input type="text" name="descricao" value="<?= htmlspecialchars($tarefa['descricao']) ?>" required>
            </div>
            <div class="form-group">
                <label for="data">Data</label>
                <input type="date" name="data" value="<?= htmlspecialchars($tarefa['vencimento']) ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status">
                    <option value="pendente" <?= $tarefa['posicao'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="andamento" <?= $tarefa['posicao'] == 'andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="concluida" <?= $tarefa['posicao'] == 'concluida' ? 'selected' : '' ?>>Concluída</option>
                </select>
            </div>
            <div class="submit">
                <button type="submit">Salvar Alterações</button>
            </div>
        </form>
    </div>
</body>
</html>
