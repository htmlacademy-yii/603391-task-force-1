<?php

declare(strict_types=1);

use TaskForce\Convertor\Convertor;
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

$CSVFile1 = new ProviderCSV($dataDir . 'status.csv', ['name']);
$SQLFile1 = new ExporterSQL($dataDir . 'status.sql');
$Status = new Model('status', ['name'], [new NullSeeder]);
$Convertor1 = new Convertor($CSVFile1, $Status, $SQLFile1);
$Convertor1->start();


$CSVFile1 = new ProviderCSV($dataDir . 'categories.csv', ['name', 'icon']);
$SQLFile1 = new ExporterSQL($dataDir . 'categories.sql');
$Category = new Model('category', ['name', 'icon'], [new NullSeeder, new NullSeeder]);
$Convertor1 = new Convertor($CSVFile1, $Category, $SQLFile1);
$Convertor1->start();

$CSVFile2 = new ProviderCSV($dataDir . 'cities.csv', ['city', 'lat', 'long']);
$SQLFile2 = new ExporterSQL($dataDir . 'cities.sql');
$City = new Model('city', ['city', 'lat', 'lng'], [new NullSeeder, new NullSeeder, new NullSeeder]);
$Convertor2 = new Convertor($CSVFile2, $City, $SQLFile2);
$Convertor2->start();

$CSVFile3 = new ProviderCSV($dataDir . 'users.csv', ['email', 'name', 'password', 'dt_add']);
$SQLFile3 = new ExporterSQL($dataDir . 'users.sql');
$User = new Model('user', ['email', 'name', 'password', 'date_add'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder]);
$Convertor3 = new Convertor($CSVFile3, $User, $SQLFile3);
$Convertor3->start();

$CSVFile4 = new ProviderCSV($dataDir . 'opinions.csv', ['dt_add', 'rate', 'description']);
$SQLFile4 = new ExporterSQL($dataDir . 'opinions.sql');
$Opinion = new Model('opinion', ['created_at', 'rate', 'description', 'owner_id', 'executor_id'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, $User->count),
        new RandomIntSeeder(1, $User->count)]);
$Convertor4 = new Convertor($CSVFile4, $Opinion, $SQLFile4);
$Convertor4->start();

$CSVFile5 = new ProviderCSV($dataDir . 'profiles.csv', ['address', 'bd', 'about', 'phone', 'skype']);
$SQLFile5 = new ExporterSQL($dataDir . 'profiles.sql');
$Profile = new Model('profile', ['address', 'birthday', 'about', 'phone', 'skype',
    'city_id', 'user_id', 'messenger', 'avatar', 'rate', 'role'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
        new RandomIntSeeder(1, 10), new RandomIntSeeder(1, $User->count), new StringSeeder('telegram'),
        new StringSeeder('no-avatar.jpg'),
        new RandomIntSeeder(1, 3), new ArrayElementSeeder(['customer', 'executor'])]);
$Convertor5 = new Convertor($CSVFile5, $Profile, $SQLFile5);
$Convertor5->start();

$CSVFile7 = new ProviderCSV($dataDir . 'tasks.csv',
    ['dt_add', 'category_id', 'description', 'expire', 'name', 'address', 'budget', 'lat', 'long']);
$SQLFile7 = new ExporterSQL($dataDir . 'tasks.sql');
$Task = new Model('task', ['date_add', 'category_id', 'description',
    'expire', 'name', 'address', 'budget', 'lat', 'lng', 'status_id', 'executor_id', 'customer_id'], [new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder,
    new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, $Status->count),
    new RandomIntSeeder(1, $User->count), new RandomIntSeeder(1, $User->count)]);
$Convertor7 = new Convertor($CSVFile7, $Task, $SQLFile7);
$Convertor7->start();

$CSVFile6 = new ProviderCSV($dataDir . 'replies.csv', ['dt_add', 'rate', 'description']);
$SQLFile6 = new ExporterSQL($dataDir . 'replies.sql');
$Replie = new Model('response', ['created_at', 'rate', 'description', 'task_id', 'price', 'status'],
    [new NullSeeder, new NullSeeder, new NullSeeder, new RandomIntSeeder(1, $Task->count), new RandomIntSeeder(1, 10000),
        new ArrayElementSeeder(['new', 'confirmed', 'canceled'])]
);
$Convertor5 = new Convertor($CSVFile6, $Replie, $SQLFile6);
$Convertor5->start();


$CSVFile8 = new ProviderCSV($dataDir . 'specializations.csv', ['profile_id', 'category_id']);
$SQLFile8 = new ExporterSQL($dataDir . 'specializations.sql');
$Spcialization = new Model('specialization', ['profile_id', 'category_id'], [new NullSeeder, new NullSeeder]);
$Convertor8 = new Convertor($CSVFile8, $Spcialization, $SQLFile8);
$Convertor8->start();

$CSVFile9 = new ProviderCSV($dataDir . 'notifications.csv', ['name']);
$SQLFile9 = new ExporterSQL($dataDir . 'notifications.sql');
$Notifications = new Model('notification', ['name'], [new NullSeeder]);
$Convertor9 = new Convertor($CSVFile9, $Notifications, $SQLFile9);
$Convertor9->start();

