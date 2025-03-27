<?php
class BancoDeDados
{
    private $servername = "maglev.proxy.rlwy.net";
    private $username = "root";
    private $password = "YUwuhrEQjVCrdmkoIrpLzpoWaobKnzAW";
    private $dbname = "${{ listas.MYSQL_URL }}";
    private $port = "14227";
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);
        if ($this->conn->connect_error) {
            die("Falha na conexão: " . $this->conn->connect_error);
        }
    }

    public function novoUsuario($nome, $email, $senha)
    {
        $sql = $this->conn->prepare("INSERT INTO usuario ( nome, email, senha) VALUES (?, ?, ?)");
        $sql->bind_param('sss', $nome, $email, $senha);

        if ($sql->execute()) {
            echo "Usuário criado com sucesso";
        } else {
            echo "Erro: " . $sql->error;
        }

        $sql->close();
    }

    public function buscarUsuario($email)
    {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc(); // Retorna os dados do usuário
    }


    public function novaTarefa($tarefa, $descricao, $data, $status)
    {
        $sql = $this->conn->prepare("INSERT INTO tarefa ( nomeTarefa, descricao, vencimento, posicao) VALUES (?, ?, ?, ?)");
        $sql->bind_param('ssss', $tarefa, $descricao, $data, $status);

        if ($sql->execute()) {
            echo "Tarefa criada com sucesso";
        } else {
            echo "Erro: " . $sql->error;
        }
        
        $sql->close();
    }

    public function buscarTarefas()
    {
        $sql = "SELECT idTarefa, nomeTarefa, descricao, vencimento, posicao FROM tarefa";
        $result = $this->conn->query($sql);

        $tarefa = [];
        while ($row = $result->fetch_assoc()) {
            $tarefa[] = $row;
        }

        return $tarefa;
    }

    public function buscarTarefasPorVencimentoEStatus($vencimento, $status) {
        $sql = "SELECT * FROM tarefa WHERE vencimento = ? AND posicao = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $vencimento, $status);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    public function buscarTarefasPorVencimento($vencimento) {
        $sql = "SELECT * FROM tarefa WHERE vencimento = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $vencimento, );
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    public function buscarTarefasPorStatus($status) {
        $sql = "SELECT * FROM tarefa WHERE posicao = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    
    public function buscarTarefaPorId($id) {
        $sql = "SELECT * FROM tarefa WHERE idTarefa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc(); // Retorna os dados da tarefa
    }
    
    public function atualizarTarefa($id, $nomeTarefa, $descricao, $data, $status) {
        $sql = "UPDATE tarefa SET nomeTarefa = ?, descricao = ?, vencimento = ?, posicao = ? WHERE idTarefa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $nomeTarefa, $descricao, $data, $status, $id);
        $stmt->execute();
    }

    public function excluirTarefa($id) {
        $sql = "DELETE FROM tarefa WHERE idTarefa = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            die("Erro na preparação da query: " . $this->conn->error);
        }
    
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return true; // Exclusão bem-sucedida
        } else {
            return false; // Falha ao excluir
        }
    }
    
    function buscarClima($url) {
        // Usa cURL para fazer a requisição
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Retorna a resposta em formato JSON
        return json_decode($response, true);
    }
    




    public function fecharConexao()
    {
        $this->conn->close();
    }
}


?>