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

class TestController extends Controller
{
    public function insert()
    {
        DB::table('panel_config')->insert(['name' => 'Auth_serve', 'value' => 'false', 'created_at' => date('Y-m-d H:i:s'), 'permission' => 'system']);
        return 'yes';
    }
}