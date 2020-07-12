<?php

declare(strict_types=1);

use TaskForce\Converter\Converter;
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

$csvFile1 = new ProviderCSV($dataDir . 'status.csv', ['name']);
$sqlFile1 = new ExporterSQL($dataDir . 'status.sql');
$status = new Model('status', ['name'], [new NullSeeder]);
$converter1 = new Converter($csvFile1, $status, $sqlFile1);
$converter1->start();


$csvFile1 = new ProviderCSV($dataDir . 'categories.csv', ['name', 'icon']);
$sqlFile1 = new ExporterSQL($dataDir . 'categories.sql');
$category = new Model('category', ['name', 'icon'], [new NullSeeder, new NullSeeder]);
$converter1 = new Converter($csvFile1, $category, $sqlFile1);
$converter1->start();

$csvFile2 = new ProviderCSV($dataDir . 'cities.csv', ['city', 'lat', 'long']);
$sqlFile2 = new ExporterSQL($dataDir . 'cities.sql');
$city = new Model('city', ['city', 'lat', 'long'], [new NullSeeder, new NullSeeder, new NullSeeder]);
$converter2 = new Converter($csvFile2, $city, $sqlFile2);
$converter2->start();

$csvFile3 = new ProviderCSV($dataDir . 'users.csv', ['email', 'name', 'password', 'dt_add']);
$sqlFile3 = new ExporterSQL($dataDir . 'users.sql');
$user = new Model('user', ['email', 'name', 'password', 'date_add'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder]);
$converter3 = new Converter($csvFile3, $user, $sqlFile3);
$converter3->start();

$csvFile4 = new ProviderCSV($dataDir . 'opinions.csv', ['dt_add', 'rate', 'description']);
$sqlFile4 = new ExporterSQL($dataDir . 'opinions.sql');
$opinion = new Model('opinion', ['created_at', 'rate', 'description', 'owner_id', 'executor_id'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, $user->count),
        new RandomIntSeeder(1, $user->count)]);
$converter4 = new Converter($csvFile4, $opinion, $sqlFile4);
$converter4->start();

$csvFile5 = new ProviderCSV($dataDir . 'profiles.csv', ['address', 'bd', 'about', 'phone', 'skype']);
$sqlFile5 = new ExporterSQL($dataDir . 'profiles.sql');
$profile = new Model('profile', ['address', 'birthday', 'about', 'phone', 'skype',
    'city_id', 'user_id', 'messenger', 'avatar', 'rate', 'role'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
        new RandomIntSeeder(1, 10), new RandomIntSeeder(1, $user->count),
        new StringSeeder('telegram'), new StringSeeder('no-avatar.jpg'),
        new RandomIntSeeder(1, 3), new ArrayElementSeeder(['customer', 'executor'])]);
$converter5 = new Converter($csvFile5, $profile, $sqlFile5);
$converter5->start();

$csvFile7 = new ProviderCSV($dataDir . 'tasks.csv',
    ['dt_add', 'category_id', 'description', 'expire', 'name', 'address', 'budget', 'lat', 'long']);
$sqlFile7 = new ExporterSQL($dataDir . 'tasks.sql');
$task = new Model('task', ['date_add', 'category_id', 'description',
    'expire', 'name', 'address', 'budget', 'lat', 'lng', 'status_id', 'executor_id', 'customer_id'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
    new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
        new NullSeeder, new RandomIntSeeder(1, $status->count),
    new RandomIntSeeder(1, $user->count), new RandomIntSeeder(1, $user->count)]);
$converter7 = new Converter($csvFile7, $task, $sqlFile7);
$converter7->start();

$csvFile6 = new ProviderCSV($dataDir . 'replies.csv', ['dt_add', 'rate', 'description']);
$sqlFile6 = new ExporterSQL($dataDir . 'replies.sql');
$replies = new Model('response', ['created_at', 'rate', 'description', 'task_id', 'price', 'status'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, $task->count),
        new RandomIntSeeder(1, 10000), new ArrayElementSeeder(['new', 'confirmed', 'canceled'])]
);
$converter5 = new Converter($csvFile6, $replies, $sqlFile6);
$converter5->start();

$csvFile8 = new ProviderCSV($dataDir . 'specializations.csv', ['profile_id', 'category_id']);
$sqlFile8 = new ExporterSQL($dataDir . 'specializations.sql');
$specialization = new Model('specialization', ['profile_id', 'category_id'], [new NullSeeder, new NullSeeder]);
$converter8 = new Converter($csvFile8, $specialization, $sqlFile8);
$converter8->start();

$csvFile9 = new ProviderCSV($dataDir . 'notifications.csv', ['name']);
$sqlFile9 = new ExporterSQL($dataDir . 'notifications.sql');
$notifications = new Model('notification', ['name'], [new NullSeeder]);
$converter9 = new Converter($csvFile9, $notifications, $sqlFile9);
$converter9->start();

