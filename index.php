<?php
if (!file_exists('configs/config.php')) {
    echo "Nenhum arquivo de configuração foi encontrado";
    exit(0);
}

require_once __DIR__ . '/configs/config.php';

ini_set('display_errors', AMBIENTE == "PROD" ? 0 : 1);

require_once __DIR__ . '/libraries/database.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/system/Roteador.php';
require_once __DIR__ . '/system/Requisicao.php';
require_once __DIR__ . '/system/Controlador.php';
require_once __DIR__ . '/system/Log.php';

try {
    $roteador = new Roteador;
    require __DIR__ . '/configs/rotas.php';
} catch (\Exception $e) {
    echo "Erro na criação das rotas: " . $e->getMessage();
    exit();
}

$requisicao = new Requisicao;

$roteador->resolver($requisicao);