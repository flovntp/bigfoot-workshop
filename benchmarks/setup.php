<?php

// Prepares the "bench" environment: warms the Symfony cache, creates the
// SQLite database and loads the fixtures with a fixed random seed, so every
// benchmark run reads the same data. Usage: php benchmarks/setup.php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'bench';
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$application = new Application(new Kernel('bench', false));
$application->setAutoExit(false);
$output = new ConsoleOutput();

$run = static function (array $command) use ($application, $output): void {
    $command['--no-interaction'] = true;
    if ($application->run(new ArrayInput($command), $output) !== 0) {
        throw new RuntimeException(sprintf('Command "%s" failed.', $command['command']));
    }
};

$run(['command' => 'cache:clear']);
$run(['command' => 'doctrine:schema:drop', '--force' => true, '--full-database' => true]);
$run(['command' => 'doctrine:schema:create']);

// Faker and rand() both use the global Mersenne Twister generator.
mt_srand(42);
$run(['command' => 'doctrine:fixtures:load']);

// The scores command writes on its first run only: run it once here so the
// measured runs all do the same work.
$run(['command' => 'app:update-sighting-scores', '--quiet' => true]);
