<?php

namespace LeadThread\Sms\Drivers;

use LeadThread\Sms\Drivers\Driver;
use LeadThread\Sms\Interfaces\PhoneSearchParams;
use LeadThread\Sms\Responses\Clickatell as ClickatellResponse;

use \Clickatell\Api;
use \Clickatell\ClickatellException;

class Clickatell extends Driver
{
    /**
     * Stores the clickatell api rest object used to handle sms requests.
     *
     * @var \Clickatell\Api
     */
    private $handle;

    /**
     * Stores the clickatell api rest object used to handle sms requests.
     *
     * @var \Clickatell\Rest
     */
    private $type;

    /**
     * Stores the clickatell api rest object used to handle sms requests.
     *
     * @var \Clickatell\Rest
     */
    private $version;

    public function __construct($auth_token, $type = 'REST', $version = '1')
    {
        $this->handle = \Clickatell\Api::load($auth_token, $type, $version);
    }

    public function send($msg, $to, $from = null, $callback = null)
    {
        $params = [
            'to'  => [$to],
            'text' => $msg,
        ];

        if (!empty($from)) {
            $params['from'] = $from;
        }

        if (!empty($callback)) {
            $params['callback'] = $callback;
        }

        return new ClickatellResponse($this->sendMessage($params));
    }

    /**
     * Sends an sms via clickatell api and returns an array as response.
     *
     * @param array $params The parameters
     *
     * @return array
     */
    private function sendMessage(array $params) : array
    {
        // Full list of support parameters can be found at https://www.clickatell.com/developers/api-documentation/rest-api-request-parameters/

        $response = ['response'];
        try {
            $result = $this->handle->sendMessage($params);

            foreach ($result as $message) {
                $response['response'][] = $message;

                /*
                    [accepted] => 1
                    [to] => 27620121816
                    [apiMessageId] => a12f6dcfac3257206bfdede0a5217daf
                */
            }

        } catch (ClickatellException $e) {
            // Any API call error will be thrown and should be handled appropriately.
            // The API does not return error codes, so it's best to rely on error descriptions.
            //var_dump($e->getMessage());
            $response['response']['exception'][] = $e->getMessage();
        }

        return $response;
    }

    public function searchNumber(PhoneSearchParams $search)
    {
        throw new \Exception("The searchNumber feature is not supported by this driver");
    }

    public function buyNumber($phone)
    {
        throw new \Exception("The buyNumber feature is not supported by this driver");
    }

    public function sellNumber($phone)
    {
        throw new \Exception("The sellNumber feature is not supported by this driver");
    }

    /**
     * Listens for messages.
     *
     * @param array $params The parameters sent by the provider.
     */
    public function listen($params)
    {

    }
}
