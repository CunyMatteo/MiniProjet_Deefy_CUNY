<?php
require_once 'vendor/autoload.php';
session_start();

use iutnc\deefy\Repository\DeefyRepository;
use iutnc\deefy\Dispatch\Dispatcher;

DeefyRepository::setConfig('config/db.config.ini');

$dispatcher = new Dispatcher();
$dispatcher->run();