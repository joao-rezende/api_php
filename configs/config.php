<?php

defined('BASE_PATH') OR define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/frente_loja/');
defined('AMBIENTE') OR define('AMBIENTE', 'DEV');

defined('CLASSE_PADRAO') OR define('CLASSE_PADRAO', '');

defined('DSN') OR define('DSN', 'pgsql:host=localhost;dbname=frente_loja');
defined('USUARIO_BD') OR define('USUARIO_BD', 'postgres');
defined('SENHA_BD') OR define('SENHA_BD', '123456');