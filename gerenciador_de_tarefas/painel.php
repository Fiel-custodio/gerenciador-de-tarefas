<?php
session_start();

// Se não estiver logado, redireciona para o login
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
</head>
<body>
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']); ?>!</h1>
    <a href="logout.php"><button>Sair</button></a>
</body>
</html>
