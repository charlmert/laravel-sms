<?php

namespace LeadThread\Sms\Responses;

use LeadThread\Sms\Interfaces\SmsResponse;

class Clickatell extends Response
{
    public function applyResponse($response)
    {
        if (isset($response['response'])) {
            if (isset($response['response']['exception'])) {
                $this->error = count($response['response']['exception']);
            }
        }

        $errors = 0;
        foreach ($response['response'] as $resp) {
            if (isset($resp['error'])) {
                $errors++;
            }
        }

        if ($errors > 0) {
            $this->error = $errors;
        }
    }

    public function successful()
    {
        return $this->error === null;
    }
}
