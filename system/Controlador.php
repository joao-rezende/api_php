<?php

class Controlador {
    public function __construct()
    {
        $this->db = new Database();

        $this->requisicao = new Requisicao();
    }

    public function resposta($dados) {
        echo json_encode(is_array($dados) ? $dados : [$dados]);
        exit(0);
    }
}