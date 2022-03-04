<?php

class Autenticacao extends Controlador
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $codigo_usuario = $this->requisicao->codigo_usuario;
        $senha = $this->requisicao->senha;

        $sql = "SELECT * FROM usuarios
            WHERE codigo = ? OR login = ?";

        $usuario = $this->db->consultar($sql, [$codigo_usuario, $codigo_usuario]);

        if (empty($usuario) || !password_verify($senha, $usuario['senha'])) return $this->resposta(["valido" => false]);

        $token = '';
        for ($i = 0; $i < 39; $i++) {
            $number = random_int(0, 36);
            $caractere = base_convert($number, 10, 36);
            $token .= $caractere;
        }

        $sql_atualizar = "UPDATE usuarios SET token_autenticacao = ?, data_venc_token = ? WHERE id_usuario = ?";
        $status = $this->db->atualizar($sql_atualizar, [$token, date("Y-m-d H:i:s", strtotime("+12 hours")), $usuario['id_usuario']]);

        if (!$status) return $this->resposta(["valido" => false]);

        return $this->resposta(["valido" => true, "token" => $token]);
    }
}
