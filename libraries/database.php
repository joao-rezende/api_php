<?php

class Database
{
    private static $conexao;

    public function __construct()
    {
        if (empty(self::$conexao)) self::$conexao = new PDO(DSN, USUARIO_BD, SENHA_BD);
    }

    public function getConexao()
    {
        return self::$conexao;
    }

    private function executar($query, $dados)
    {
        try {
            $stmt = self::$conexao->prepare($query);
            return $stmt->execute($dados);
        } catch (PDOException $pdo_ex) {
            mensagem_log("Erro no PDO: " . $pdo_ex->getMessage());
            return false;
        }
    }

    private function executar_retornar($query, $dados)
    {
        try {
            $stmt = self::$conexao->prepare($query);
            $stmt->execute($dados);
            return $stmt;
        } catch (PDOException $pdo_ex) {
            mensagem_log("Erro no PDO: " . $pdo_ex->getMessage());
            return false;
        }
    }

    public function consultar($query, $dados = array())
    {
        $stmt = $this->executar_retornar($query, $dados);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : NULL;
    }

    public function consultar_varios($query, $dados = array())
    {
        $stmt = $this->executar_retornar($query, $dados);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : NULL;
    }

    public function atualizar($query, $dados = array())
    {
        return $this->executar($query, $dados);
    }
}
