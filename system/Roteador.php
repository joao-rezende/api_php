<?php
require_once __DIR__ . '/RotasColecao.php';

class Roteador
{
    protected $rotas_colecao;

    function __construct()
    {
        $this->rotas_colecao = new RotasColecao();
    }

    public function get($caminho, $funcao)
    {
        $this->rotas_colecao->adicionar('get', $caminho, $funcao);
        return $this;
    }

    public function post($caminho, $funcao)
    {
        $this->rotas_colecao->adicionar('post', $caminho, $funcao);
        return $this;
    }

    public function put($caminho, $funcao)
    {
        $this->rotas_colecao->adicionar('post', $caminho, $funcao);
        return $this;
    }

    public function delete($caminho, $funcao)
    {
        $this->rotas_colecao->adicionar('post', $caminho, $funcao);
        return $this;
    }

    public function buscar($tipo_requisicao, $caminho)
    {
        return $this->rotas_colecao->buscar($tipo_requisicao, $caminho);
    }

    public function despachar($funcao, $parametros = [])
    {
        if (is_callable($funcao)) {
            return call_user_func_array($funcao, array_values($parametros));
        }

        if (!is_string($funcao) || !strpos($funcao, '@')) {
            throw new \Exception("Erro ao despachar: Função informada é inválida");
            return false;
        }

        $funcao = explode('@', $funcao);
        $controlador = $funcao[0];
        $metodo = $funcao[1];

        require_once __DIR__ . "/../src/" . ucfirst($controlador) . ".php";

        $rc = new \ReflectionClass($controlador);

        if ($rc->isInstantiable() && $rc->hasMethod($metodo)) {
            return call_user_func_array(array(new $controlador, $metodo), array_values($parametros));
        } else {
            throw new \Exception("Erro ao despachar: controller não pode ser instanciado ou método não existe");
            return false;
        }
    }

    public function resolver($requisicao)
    {
        $rota = $this->buscar($requisicao->metodo(), $requisicao->uri());

        if ($rota) {
            return $this->despachar($rota->funcao, $rota->uri);
        }

        erro_404();
        return false;
    }
}
