<?php

namespace LeadThread\Sms\Drivers;

use LeadThread\Sms\Interfaces\SendsSms;
use LeadThread\Sms\Interfaces\ReceivesSms;
use LeadThread\Sms\Interfaces\PhoneSearchParams;

abstract class Driver implements SendsSms, ReceivesSms
{
    protected $config = [];

    abstract public function searchNumber(PhoneSearchParams $search);
    abstract public function buyNumber($phone);
    abstract public function sellNumber($phone);

    /**
     * Searches for a number and then purchases the first one it finds
     * @param  array $search Array of search options
     * @return \LeadThread\Sms\Responses\Response
     */
    public function searchAndBuyNumber(PhoneSearchParams $search)
    {
        $resp = $this->searchNumber($search);
        return $this->buyNumber($resp->number);
    }

    /**
     * Listens for sms messages from a request to a callback url.
     * Receives messages using message params from the given provider.
     *
     * @param array $params
     */
    public function listen(array $params) {
        throw new \Exception("This driver doesn't implement the listen method");
    }
}
