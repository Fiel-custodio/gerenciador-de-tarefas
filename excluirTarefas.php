<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se foi passado um ID válido pela URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID da tarefa não especificado.";
    exit();
}

$idTarefa = $_GET['id'];

$banco = new BancoDeDados();

// Chama a função para excluir a tarefa
if ($banco->excluirTarefa($idTarefa)) {
    // Redireciona para a página de tarefas após a exclusão
    header("Location: tarefas.php?msg=excluido");
    exit();
} else {
    echo "Erro ao excluir a tarefa.";
}
?>
