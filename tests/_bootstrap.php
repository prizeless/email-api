<?php
date_default_timezone_set('Africa/Harare');

exec('php artisan migrate:refresh --env=testing');

define('WEB_SERVER_HOST', 'localhost');
define('WEB_SERVER_PORT', 8001);
define('WEB_SERVER_DOCROOT', dirname(dirname(__FILE__)) . '/public');
$command = sprintf(
    'php -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    WEB_SERVER_DOCROOT
);

$output = array();
exec($command, $output);
$pid = (int)$output[0];

echo sprintf(
        '%s - Web server started on %s:%d with PID %d',
        date('r'),
        WEB_SERVER_HOST,
        WEB_SERVER_PORT,
        $pid
    ) . PHP_EOL;

register_shutdown_function(function () use ($pid) {
    echo sprintf('%s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
    exec('kill ' . $pid);
});
