<?php

namespace LeadThread\Sms\Drivers;

use LeadThread\Sms\Drivers\Driver;
use LeadThread\Sms\Interfaces\PhoneSearchParams;
use LeadThread\Sms\Responses\Clickatell as ClickatellResponse;

use \Clickatell\Rest;
use \Clickatell\ClickatellException;

class Clickatell extends Driver
{

    /**
     * Stores the clickatell api rest object used to handle sms requests.
     *
     * @var \Clickatell\Rest
     */
    private $handle;

    public function __construct($auth_token)
    {
        $this->handle = new \Clickatell\Rest($auth_token);
    }

    public function send($msg, $to, $from = null, $callback = null)
    {
        if (!empty($callback)) {
            throw new \Exception("Callback URLs are not implemented by this driver", 1);
        }

        $params = [
            'to'  => $to,
            'content' => $msg,
        ];

        return new ClickatellResponse($this->send_message($params));
    }

    /**
     * Sends an sms via clickatell api and returns an array as response.
     *
     * @param array $params The parameters
     *
     * @return array
     */
    private function send_message(array $params) : array
    {
        // Full list of support parameters can be found at https://www.clickatell.com/developers/api-documentation/rest-api-request-parameters/

        $response = ['response'];

        try {
            $result = $this->handle->sendMessage($params);

            foreach ($result['messages'] as $message) {
                $response['response'][] = $message;

                /*
                [
                    'apiMsgId'  => null|string,
                    'accepted'  => boolean,
                    'to'        => string,
                    'error'     => null|string
                ]
                */
            }

        } catch (ClickatellException $e) {
            // Any API call error will be thrown and should be handled appropriately.
            // The API does not return error codes, so it's best to rely on error descriptions.
            //var_dump($e->getMessage());
            $response['response']['exception'][] = $e->getMessage();
        }
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
}
