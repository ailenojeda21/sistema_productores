<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = $app['events'];
$ref = new ReflectionClass($events);
$prop = $ref->getProperty('listeners');
$prop->setAccessible(true);
$listeners = $prop->getValue($events);
$key = 'Illuminate\Auth\Events\Registered';
if (isset($listeners[$key])) {
    echo count($listeners[$key]) . ' listeners:' . PHP_EOL;
    foreach ($listeners[$key] as $l) {
        echo '  ' . (is_string($l) ? $l : get_class($l[0]) . '@' . $l[1]) . PHP_EOL;
    }
} else {
    echo 'No Registered listeners found' . PHP_EOL;
}

echo PHP_EOL . 'Providers with EventService:' . PHP_EOL;
foreach ($app->getLoadedProviders() as $class => $loaded) {
    if ($loaded && str_contains($class, 'EventService')) {
        echo '  ' . $class . PHP_EOL;
    }
}