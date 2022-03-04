<?php

class Log
{
    private $pasta = __DIR__ . "/../logs/";
    private static $arquivo;

    function __construct()
    {
        if (empty(Log::$arquivo)) {
            try {
                Log::$arquivo = fopen($this->pasta . "log_" . date("Y_m_d") . ".log", "a+");
            } catch (Exception $e) {
                throw new Exception("Erro ao abrir o arquivo: " . $e->getMessage());
                return false;
            }
        }
    }

    function __destruct()
    {
        if (!empty(Log::$arquivo)) fclose(Log::$arquivo);
    }


    public function mensagem($mensagem, $tipo = "ERRO", $arquivo_mensagem = NULL, $linha = NULL)
    {
        if (empty(Log::$arquivo)) return false;
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        
        if (empty($arquivo_mensagem)) {
            $arquivo_mensagem = $caller['file'];
        }
        if (empty($linha)) {
            $linha = $caller['line'];
        }

        return fwrite(Log::$arquivo, "[$tipo] " . date("Y-m-d H:i:s") . " - (" . $arquivo_mensagem . ":" . $linha . ") $mensagem\n");
    }
}
