<?php

namespace LeadThread\Sms\Responses;

use LeadThread\Sms\Interfaces\SmsResponse;

class Clickatell extends Response
{
    public function applyResponse($response)
    {
        if (isset($response['response'])) {
            if (isset($response['response']['exception'])) {
                $this->error[] = $response['response']['exception'];
            }

            if (count($response['response']) == 1) {
                if (isset($response['response'][0]['error'])) {
                    $this->error = $response['response'][0]['error'];
                }

                if (isset($response['response'][0]['accepted'])) {
                    $this->status = (bool) ($response['response'][0]['accepted'] == 1);
                }

                if (isset($response['response'][0]['apiMessageId'])) {
                    $this->uuid = $response['response'][0]['apiMessageId'];
                }

                if (isset($response['response'][0]['to'])) {
                    $this->numbers = $response['response'][0]['to'];
                }

                if (isset($response['response'][0]['from'])) {
                    $this->number = $response['response'][0]['from'];
                }

            } elseif (count($response['response']) > 1) {
                $this->error = [];
                $this->status = [];
                $this->uuid = [];
                $this->number = [];
                $this->numbers = [];

                foreach ($response['response'] as $resp) {
                    if (isset($resp['error'])) {
                        $this->error[] = $resp['error'];
                    }

                    if (isset($resp['accepted'])) {
                        $this->status[] = (bool) ($resp['accepted'] == 1);
                    }

                    if (isset($resp['uuid'])) {
                        $this->uuid[] = $resp['uuid'];
                    }

                    if (isset($resp['to'])) {
                        $this->numbers[] = $resp['to'];
                    }

                    if (isset($resp['from'])) {
                        $this->number[] = $resp['from'];
                    }
                }

                if (count($this->error) == 0) {
                    $this->error = null;
                }

                if (count($this->status) == 0) {
                    $this->status = null;
                }

                if (count($this->uuid) == 0) {
                    $this->uuid = null;
                }

                if (count($this->number) == 0) {
                    $this->number = null;
                }

                if (count($this->numbers) == 0) {
                    $this->numbers = null;
                }
            }
        }
    }

    public function successful()
    {
        return $this->error === null;
    }
}
