<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\\Contracts\\Console\\Kernel');
$kernel->bootstrap();
$userClass = 'App\\Models\\User';
$serviceClass = 'App\\Services\\GoogleCalendarService';
$user = $userClass::find(2);
echo "user={$user->email}\n";
$service = new $serviceClass($user);
$events = $service->listEvents('primary', 10);
echo 'api_count=' . count($events) . "\n";
foreach ($events as $event) {
    $start = $event->getStart()?->getDateTime() ?? $event->getStart()?->getDate();
    echo $event->getId() . ' | ' . $event->getSummary() . ' | ' . $start . "\n";
}
