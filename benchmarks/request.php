<?php

// Handles one HTTP request through the Symfony kernel, like public/index.php,
// in the "bench" environment. Usage: php benchmarks/request.php <path>

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'bench';
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$path = $argv[1] ?? '/';

$kernel = new Kernel('bench', false);
$request = Request::create($path);
$response = $kernel->handle($request);

if ($response->getStatusCode() !== 200) {
    fwrite(STDERR, sprintf("GET %s returned HTTP %d\n", $path, $response->getStatusCode()));
    exit(1);
}

$kernel->terminate($request, $response);
