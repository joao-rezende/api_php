<?php

class Requisicao
{
    protected $uri;
    protected $metodo;
    protected $protocolo;
    protected $dados;
    protected $arquivos;

    function __construct()
    {
        $this->uri = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "/" . CLASSE_PADRAO;
        $this->metodo = strtolower($_SERVER['REQUEST_METHOD']);
        $this->protocolo = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $this->set_dados();

        if (count($_FILES) > 0) {
            $this->set_arquivos();
        }
    }

    protected function set_dados()
    {
        if (in_array($this->metodo, ["post", 'put']) && $_SERVER["CONTENT_TYPE"] == "application/json") {
            $this->dados = json_decode(file_get_contents('php://input'));
        } else if ($this->metodo == "post") {
            $this->dados = $_POST;
        } else if ($this->metodo == "get") {
            $this->dados = $_GET;
        } else if (in_array($this->metodo, ['head', 'put', 'delete', 'options'])) {
            parse_str(file_get_contents('php://input'), $this->data);
        }
    }

    protected function set_arquivos()
    {
        foreach ($_FILES as $indice => $arquivo) {
            $this->arquivos[$indice] = $arquivo;
        }
    }

    public function uri()
    {
        return $this->uri;
    }

    public function metodo()
    {
        return $this->metodo;
    }

    public function get_dados()
    {
        return $this->dados;
    }

    public function __isset($indice)
    {
        return isset($this->dados->{$indice});
    }

    public function __get($indice)
    {
        if (isset($this->dados->{$indice})) {
            return $this->dados->{$indice};
        }

        return NULL;
    }

    public function validar_arquivo($indice)
    {
        return isset($this->arquivos[$indice]);
    }

    public function arquivo($indice)
    {
        if (isset($this->arquivos[$indice])) {
            return $this->arquivos[$indice];
        }
    }
}
