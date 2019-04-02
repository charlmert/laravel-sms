<?php

namespace LeadThread\Sms\Interfaces;

interface ReceivesSms
{
    public function listen(array $params);
}
