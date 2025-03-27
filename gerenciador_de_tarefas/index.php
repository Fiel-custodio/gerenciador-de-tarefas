<?php
session_start(); // Inicia a sessão
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    $banco = new BancoDeDados();
    $usuario = $banco->buscarUsuario($email);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = $usuario['nome'];  // Armazena o nome do usuário na sessão
        $_SESSION['email'] = $usuario['email'];  // Armazena o email do usuário
        header("Location: tarefas.php");  // Redireciona para a página protegida
        exit();
    } else {
        echo "<p style='color:red;'>E-mail ou senha incorretos!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <h1>Login</h1>
    <div class="login">
         <form action="" method="post">      
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="insire seu Email">
            </div>       
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" placeholder="insire sua Senha">
            </div>
            <div class="submit">
                <button type="submit">Entrar</button>
            </div>          
        </form>
    </div>
    <div class="cadastro">
        <h5>Ainda não tem cadastro?
            <a href="cadastro.php">
                <button>Cadastre-se</button>
            </a>
        </h5>
    </div>
   
</body>
</html>