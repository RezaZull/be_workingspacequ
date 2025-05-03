<?php

namespace App\Console\Commands;

use App\Models\MRoomSensor;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;

class MqttSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listening MQTT Subscribe and update data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mqtt = MQTT::connection();
        $mqtt->subscribe('testtopic/xd123hh', function (string $topic, string $message) {
            $data = json_decode($message, true);
            foreach ($data['data'] as $dataRoomSensor) {
                $roomSensor = MRoomSensor::findOrFail($dataRoomSensor['sensor_id'])->update([
                    'value' => $dataRoomSensor['value'],
                    'updated_by' => 'IOT Devices'
                ]);

            }
            echo "Successfully Update data";
        });

        $mqtt->loop(true);
        return self::SUCCESS;
    }
}
