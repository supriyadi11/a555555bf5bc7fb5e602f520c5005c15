<?php
namespace App;

use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger {
    
    private MonologLogger $log;

    public function __construct() {
        $dir = __DIR__.'/..';
        $logPath = $dir . '/'.config('app.logPath');
        $filename = now()->format('Y-m-d');
        $this->log = new MonologLogger(config('app.name'));
        $this->log->pushHandler(new StreamHandler("$logPath/$filename.log", Level::Warning));
    }

    public function writeLog(string $level, string $message, array $context = [])
    {
        $this->log->$level($message, $context);
    }

    public function warningLog(string $message, array $context = [])
    {
        $this->log->warning($message, $context);
    }

    public function errorLog(string $message, array $context = [])
    {
        $this->log->error($message, $context);
    }
}