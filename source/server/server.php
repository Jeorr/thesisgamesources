<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$classLoader = require __DIR__ . '/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('GAME_DATA_FOLDER_PATH', $_ENV['GAME_DATA_FOLDER_PATH']);
define('LOG_FILE_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $_ENV['LOG_FILE_NAME']);
define('CURRENT_CHAPTER', $_ENV['CURRENT_CHAPTER']);

$dataChecker = new \Server\Core\GameData\DataChecker();
$dataChecker->checkData();

$socket = new React\Socket\Server($_ENV['SOCKET_SERVER_URI']);
try{
    function App() {
        static $app = null;

        if ($app === null) {
            $app = new \Server\App();
        }

        return $app;
    }

    $app = App();
    $app->initApp();

    $websocketServer = new \Ratchet\Server\IoServer(
        new \Ratchet\Http\HttpServer(
            new \Ratchet\WebSocket\WsServer(
                $app
            )
        ),
        $socket,
        \React\EventLoop\Loop::get()
    );
    $websocketServer->run();
    echo "Server running at " . getenv('SOCKET_SERVER_URI') . PHP_EOL;
}catch (\Throwable $e) {
    var_dump($e->getMessage(), $e->getTraceAsString(), $e->getFile(), $e->getLine());
}

