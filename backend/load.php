<?php

declare(strict_types=1);

use TaskForce\Converter\SqlToCsvConverter;
use TaskForce\Exporter\ExporterSQL;
use TaskForce\Model\Model;
use TaskForce\Provider\ProviderCSV;
use TaskForce\Seeder\ArrayElementSeeder;
use TaskForce\Seeder\NullSeeder;
use TaskForce\Seeder\RandomIntSeeder;
use TaskForce\Seeder\StringSeeder;

require_once 'vendor/autoload.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL);
$dataDir = __DIR__ . '\\data\\';

$converter = new SqlToCsvConverter();

$converter->setProvider(new ProviderCSV($dataDir . 'status.csv', ['name']));
$converter->setExporter(new ExporterSQL($dataDir . 'status.sql'));
$converter->start(new Model('status', ['name'], [new NullSeeder]));

$converter->setProvider(new ProviderCSV($dataDir . 'categories.csv', ['name', 'icon']));
$converter->setExporter(new ExporterSQL($dataDir . 'categories.sql'));
$converter->start(new Model('category', ['name', 'icon'], [new NullSeeder, new NullSeeder]));


$converter->setProvider(new ProviderCSV($dataDir . 'cities.csv', ['city', 'lat', 'long']));
$converter->setExporter(new ExporterSQL($dataDir . 'cities.sql'));
$converter->start(new Model('city', ['city', 'lat', 'long'], [new NullSeeder, new NullSeeder,
    new NullSeeder]));

$converter->setProvider(new ProviderCSV($dataDir . 'users.csv', ['email', 'name', 'password', 'dt_add']));
$converter->setExporter(new ExporterSQL($dataDir . 'users.sql'));
$converter->start(new Model('user', ['email', 'name', 'password', 'date_add'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder]));

$converter->setProvider(new ProviderCSV($dataDir . 'opinions.csv', ['dt_add', 'rate', 'description']));
$converter->setExporter(new ExporterSQL($dataDir . 'opinions.sql'));
$converter->start(new Model('opinion', ['created_at', 'rate', 'description', 'owner_id', 'executor_id'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, 10),
        new RandomIntSeeder(1, 10)]));

$converter->setProvider(new ProviderCSV($dataDir . 'profiles.csv', ['address', 'bd', 'about', 'phone', 'skype']));
$converter->setExporter(new ExporterSQL($dataDir . 'profiles.sql'));
$converter->start(new Model('profile', ['address', 'birthday', 'about', 'phone', 'skype',
    'city_id', 'user_id', 'messenger', 'avatar', 'rate', 'role'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
        new RandomIntSeeder(1, 10), new RandomIntSeeder(1, 10),
        new StringSeeder('telegram'), new StringSeeder('no-avatar.jpg'),
        new RandomIntSeeder(1, 3), new ArrayElementSeeder(['customer', 'executor'])]));

$converter->setProvider(new ProviderCSV($dataDir . 'tasks.csv',
    ['dt_add', 'category_id', 'description', 'expire', 'name', 'address', 'budget', 'lat', 'long']));
$converter->setExporter(new ExporterSQL($dataDir . 'tasks.sql'));
$converter->start(new Model('task', ['date_add', 'category_id', 'description',
    'expire', 'name', 'address', 'budget', 'lat', 'lng', 'status_id', 'executor_id', 'customer_id'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
        new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
        new NullSeeder, new RandomIntSeeder(1, 4),
        new RandomIntSeeder(1, 10), new RandomIntSeeder(1, 10)]));

$converter->setProvider(new ProviderCSV($dataDir . 'replies.csv', ['dt_add', 'rate', 'description']));
$converter->setExporter(new ExporterSQL($dataDir . 'replies.sql'));
$converter->start(new Model('response', ['created_at', 'rate', 'description', 'task_id', 'price', 'status'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, 5),
        new RandomIntSeeder(1, 10000), new ArrayElementSeeder(['new', 'confirmed', 'canceled'])]));

$converter->setProvider(new ProviderCSV($dataDir . 'specializations.csv', ['profile_id', 'category_id']));
$converter->setExporter(new ExporterSQL($dataDir . 'specializations.sql'));
$converter->start(new Model('specialization', ['profile_id', 'category_id'],
    [new NullSeeder, new NullSeeder]));

$converter->setProvider(new ProviderCSV($dataDir . 'notifications.csv', ['name']));
$converter->setExporter(new ExporterSQL($dataDir . 'notifications.sql'));
$converter->start(new Model('notification', ['name'], [new NullSeeder]));


