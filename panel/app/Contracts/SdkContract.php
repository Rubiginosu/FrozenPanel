<?php
namespace App\Contracts;

interface SdkContract
{
    public function getUser($request);

    public function getSock($ip, $port, $code);
}