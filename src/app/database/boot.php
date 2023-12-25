<?php
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB;
$db->addConnection(config('database.pgsql'));
$db->setAsGlobal();
$db->bootEloquent();