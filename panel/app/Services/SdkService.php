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

    public function getSock($ip, $port, $code)
    {
        if ($ip == 0 || $port == 0 || $code == 0) {
            $SDK = new \FrozenGo(DB::table('panel_config')->where('name', 'daemon_ip')->value('value'), DB::table('panel_config')->where('name', 'daemon_port')->value('value'), DB::table('panel_config')->where('name', 'daemon_verifyCode')->value('value'));
        } else {
            $SDK = new \FrozenGo($ip, $port, $code);
        }
        $data = $SDK->getServerList();
        if ($data[0] == -1) return false;
        else return $SDK;
    }

    public function is_login($request)
    {
        if ($request->session()->has('userid')&&$request->session()->get('userid')!=null) {
            $data = $this->getUser($request);
            if ($data->token === $request->session()->get('login_token')) {
                return true;
            }
            return false;
        }
        return false;
    }
}