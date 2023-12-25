<?php
namespace App;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/database/boot.php';

use App\Models\Queue as QueueModel;

class Queue {

    public static function push(string $name, array $payload)
    {
        QueueModel::create([
            'name' => $name,
            'payload' => json_encode($payload),
            'attempts' => 1,
        ]);
    }

    public static function work()
    {
        echo "Listening Queue...\n";
        while (true) {
            $queue = QueueModel::whereNull('success_at')->whereNull('failed_at')
                                ->orderBy('id')->limit(1)->first();
            if ($queue) {
                echo "Processing Queue: {$queue->name}....\n";
                $executed = $queue->name::handle(json_decode($queue->payload, true));
                if ($executed) {
                    QueueModel::find($queue->id)->update([
                        'success_at' => now()
                    ]);
                }
                else {
                    QueueModel::find($queue->id)->update([
                        'failed_at' => now()
                    ]);
                    echo "Failed Queue: {$queue->name}....\n";
                }
                echo "Processed Queue: {$queue->name}....\n";
                echo "Listening Queue...\n";
            }
            sleep(3);
        }
    }

}