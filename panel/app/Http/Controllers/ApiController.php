<?php
namespace App\Http\Controllers;

use Log;
use App\Panel_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;

use App;
use App\Contracts\SdkContract;
use MongoDB\Driver\Exception\ConnectionTimeoutException;

class ApiController extends Controller
{
    public function __construct(SdkContract $sdk)
    {
        $this->sdk = $sdk;
    }

    public function daemon_test(Request $request)
    {
        $daemonip = $request->session()->get('daemon_data');
        $back = array();
        foreach ($daemonip as $d) {
            $fp = @fsockopen($d->value, DB::table('panel_config')->where([['name', 'daemon_port'], ['other', $d->other]])->value('value'), $err_no, $err_str, 2);
            if (!$fp) {
                $ret = array('id' => $d->other, 'status' => '无法连接');
                $back = array_prepend($back, $ret);
            } else {
                if ($this->sdk->getSock($d->value, DB::table('panel_config')->where([['name', 'daemon_port'], ['other', $d->other]])->value('value'), DB::table('panel_config')->where([['name', 'daemon_verifyCode'], ['other', $d->other]])->value('value'))) {
                    $ret = array('id' => $d->other, 'status' => '连接正常');
                    $back = array_prepend($back, $ret);
                }
            }
        }
        return response()->json(['success' => true, 'data' => $back]);
    }
}