<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Cadastre-se</h1>
    <div class="cadastro">
         <form action="" method="POST">      
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" placeholder="insire seu Nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="insire seu Email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" placeholder="insire sua Senha" required>
            </div>
            <div class="submit">
                <button type="submit">Cadastrar</button>
            </div>          
        </form>
    </div>
</body>
<?php
    require_once 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); 
        
        $banco = new BancoDeDados();
        $banco->novoUsuario($nome, $email, $senha);
        $banco->fecharConexao();
    }
?>
</html>