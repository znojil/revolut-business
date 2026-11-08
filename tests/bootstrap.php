<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

\Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

// temporary directory
define('TempDir', sys_get_temp_dir() . '/znojil-revolut-business-tests/' . getmypid() . '-' . uniqid());
@mkdir(TempDir, recursive: true);

register_shutdown_function(function (): void{
	@\Tester\Helpers::purge(TempDir);
	@rmdir(TempDir);
});
