<?php
if (!function_exists('erro_404')) {
    function erro_404($msg = "Página não encontrada")
    {
        header("HTTP/1.0 404 Not Found", true, 404);
        echo json_encode([
            "erro" => TRUE,
            "mensagem" => $msg
        ]);
        exit(0);
    }
}

if (!function_exists('mensagem_log')) {
    function mensagem_log($msg, $tipo = NULL)
    {
        try {
            $log = new Log();
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            
            return $log->mensagem($msg, $tipo, $caller['file'], $caller['line']);
        } catch (Exception $e) {
            return "Erro escrever no log: " . $e->getMessage();
        }
    }
}
