<?php

class RotasColecao
{
    protected $rotas;

    function __construct()
    {
        $this->rotas = [
            'post' => [],
            'get' => [],
            'put' => [],
            'delete' => []
        ];
    }

    protected function formatar_caminho($caminho)
    {
        $caminho = implode('\/', array_filter(explode('/', $caminho)));
        return '/^' . $caminho . '$/';
    }

    public function adicionar($tipo_requisicao, $caminho, $funcao)
    {
        if (isset($this->rotas[$tipo_requisicao])) {
            $this->rotas[$tipo_requisicao][$this->formatar_caminho($caminho)] = $funcao;
            return $this;
        } else {
            throw new \Exception('Tipo de requisição (' .  $tipo_requisicao . ') não implementado');
            return FALSE;
        }
    }

    protected function parseUri($uri)
    {
        return implode('/', array_filter(explode('/', $uri)));
    }

    public function buscar($tipo_requisicao, $caminho)
    {
        if (isset($this->rotas[$tipo_requisicao])) {
            $caminho_aux = explode("/", $this->parseUri($caminho));
            $caminho = $caminho_aux[0] . "/" . ($caminho_aux[1] ?? "index");

            foreach ($this->rotas[$tipo_requisicao] as $caminho_rota => $funcao) {
                if (preg_match($caminho_rota, $caminho)) {
                    $parametros = array_slice($caminho_aux, 2);
                    return (object) ['funcao' => $funcao, 'uri' => $parametros];
                }
            }
            return FALSE;
        } else {
            throw new \Exception('Tipo de requisição não implementado');
            return FALSE;
        }
    }
}
