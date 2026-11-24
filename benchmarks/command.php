<?php

// Runs one console command in the "bench" environment, without output.
// Usage: php benchmarks/command.php <command name> [arguments...]
//
// The command runs directly, not through Application::run(): the console
// application and the terminal size detection start `stty` subprocesses,
// which the CodSpeed CPU simulation cannot measure.

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'bench';
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
putenv('COLUMNS=120');
putenv('LINES=50');

$kernel = new Kernel('bench', false);
$kernel->boot();
$application = new Application($kernel);

$input = new ArgvInput($argv);
$command = $application->find($input->getFirstArgument());

exit($command->run($input, new NullOutput()));
