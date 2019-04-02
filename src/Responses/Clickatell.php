<?php

namespace LeadThread\Sms\Responses;

use LeadThread\Sms\Interfaces\SmsResponse;

class Clickatell extends Response
{
    public function applyResponse($response)
    {
        $this->error = [];
        if (isset($response['response'])) {
            if (isset($response['response']['exception'])) {
                $this->error[] = $response['response']['exception'];
            }
        }

        foreach ($response['response'] as $resp) {
            if (isset($resp['error'])) {
                $this->error[] = $resp['error'];
            }
        }
    }

    public function successful()
    {
        return $this->error === null;
    }
}
