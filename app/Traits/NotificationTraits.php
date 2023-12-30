<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Arr;
use Throwable;

trait NotificationTraits {
    public function sendNotification($notification_message, $type, $ids = []) {
        //TODO: finish tomorrow
        $device_tokens_en = [];
        $device_tokens_ar = [];
        $notifications = [];
        $type_devices_counts = [];
        $type_device_class = "App\\Models\\" . ucfirst($type) . "Device"; // UserDevice or WorkerDevice

        foreach ($ids as $id) {
            $type_devices = $type_device_class::where($type . "_id", $id)->with("$type:id,lang")->get();
            $notifications[] = Notification::create([
                $type . "_id" => $id,
                "notification_message_id" => $notification_message->id
            ]);
            foreach ($type_devices as $type_device) {
                if ($type_device->{$type}->lang == "en") {
                    $device_tokens_en[] = $type_device->device_token;
                    continue;
                }

                $device_tokens_ar[] = $type_device->device_token;
            }
            $type_devices_counts[] = ["id" => $id, "count" => count($type_devices)];
        }
        $notification_message->disable_localization();
        $data_en = [
            "notification" => [
                "title" => $notification_message->title_en,
                "body" => $notification_message->body_en,
                "sound" => 'default'
            ],
            // Apple specific settings
            "apns" => [
                "headers" => [
                    'apns-priority' => '10',
                ],
                "payload" => [
                    "aps" => [
                        "sound" => 'default',
                    ]
                ],
            ]
        ];
        $data_ar = [
            "notification" => [
                "title" => $notification_message->title_ar,
                "body" => $notification_message->body_ar,
                "sound" => 'default'
            ],
            // Apple specific settings
            "apns" => [
                "headers" => [
                    'apns-priority' => '10',
                ],
                "payload" => [
                    "aps" => [
                        "sound" => 'default',
                    ]
                ],
            ]
        ];
        $device_tokens_ar = array_values(array_unique($device_tokens_ar, SORT_REGULAR));
        $device_tokens_en = array_values(array_unique($device_tokens_en, SORT_REGULAR));
        $this->requestSplitting($data_ar, $device_tokens_ar);
        $this->requestSplitting($data_en, $device_tokens_en);
        return;
    }
    private function requestSplitting($data, $device_tokens) {
        $length_of_tokens = count($device_tokens);
        $counter = 0;
        while ($length_of_tokens > $counter) {
            // Send 100 tokens at a time
            $new_device_tokens = array_slice($device_tokens, $counter, $counter + 1000);
            $data["registration_ids"] = $new_device_tokens;
            $this->sendFcmRequest($data);
            $counter += 1000;
        }
    }
    private function sendFcmRequest($data) {
        try {
            $data = json_encode($data);
            $headers = [

                'Authorization: key=' . env("FIREBASE_KEY"),
                'Content-Type: application/json',

            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($ch);
            return json_decode($response, true);
        } catch (Throwable $th) {
            // Do nothing for Now
            return [];
        }
    }
    private function updateNotification($response, $notifications, $type_devices_counts, $type) {
        //TODO: re-test this code
        if (!$response || !in_array("results", $response)) {
            return;
        }
        $results = $response["results"];
        $results_counter = 0;
        for ($i = 0; $i < count($notifications); $i++) {
            $message_ids = "";
            $number_of_devices = $this->getElementById($notifications[$i][$type . "_id"], $type_devices_counts)["count"];
            $number_of_devices += $results_counter;

            if ($number_of_devices <=  count($results)) {
                for ($j = $results_counter; $j < $number_of_devices; $j++) {
                    $new_message_id = "device_not_found";
                    if (Arr::has($results[$j], "message_id")) {
                        $new_message_id = $results[$j]["message_id"];;
                    }
                    if (!$message_ids) {
                        $message_ids = $new_message_id;
                        continue;
                    }
                    $results_counter += $number_of_devices;
                    $message_ids = $message_ids . ","  . $new_message_id;
                }
            }
            $notifications[$i]->update(["message_id" => $message_ids]);
        }
        return;
    }

    private function getElementById($id, $type_devices_counts) {
        foreach ($type_devices_counts as $type_device) {
            if ($id == $type_device["id"]) {
                return $type_device;
            }
        }
    }
    /**
     * @deprecated no longer needed validation
     */
    private function removeRepeatedids($ids) {
        $number_of_occurrences = array_count_values($ids);
        foreach ($number_of_occurrences as $key => $value) {
            if ($value > 1) {
                $ids = array_diff($ids, [$key]);
                // re-set the user again
                $ids[] = $key;
            }
        }
        return $ids;
    }
}
