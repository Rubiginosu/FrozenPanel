<?php
namespace App\Services;

use App\Contracts\SdkContract;
use Illuminate\Support\Facades\DB;
require_once __DIR__ . '/../../public/FrozenGo.php';

class SdkService implements SdkContract
{
    public function getUser($request)
    {
        $userid = $request->session()->get('userid');
        $data = DB::table('panel_users')->where('id', $userid)->first();
        return $data;
    }

    public function getSock($ip, $port, $code){
        $SDK = new FrozenGo();
        if ($ip == 0 || $port == 0 || $code == 0) {
            $SDK->ip = DB::table('panel_config')->where('name', 'daemon_ip')->value('value');
            $SDK->port = DB::table('panel_config')->where('name', 'daemon_port')->value('value');
            $SDK->verifyCode = DB::table('panel_config')->where('name', 'daemon_verifyCode')->value('value');
        } else {
            $SDK->ip = $ip;
            $SDK->port = $port;
            $SDK->verifyCode = $code;
        }
        $data = $SDK->getServerList();
        if ($data[0] == -1) return false;
        else return $SDK;
    }
}