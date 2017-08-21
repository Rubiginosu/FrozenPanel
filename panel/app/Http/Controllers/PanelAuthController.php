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

class PanelAuthController extends Controller
{
    public function login_face(Request $request)
    {
        if ($request->isMethod('post')) {
            $username = $request->input('username');
            $password = $request->input('password');
            $road = $request->input('pushroad');
            if (DB::table('panel_users')->where('username', $username)->first() == null) {
                $success = false;
                $msg = "登录信息不正确！";
            } else if ($request->session()->has('userid')) {
                $success = false;
                $msg = "您已经登录，请勿重复登录！";
            } else {
                $code = str_random(16);
                $request->session()->put('verify_password_' . $code, encrypt($password));
                DB::table('panel_logins')->insert(['username' => $username, 'push_road' => $road, 'verify_code' => $code, 'timeout' => date("Y-m-d H:i:s", strtotime("+5 seconds"))]);
                $timeout = false;
                while (DB::table('panel_logins')->where('verify_code', $code)->value('is_read') == false) {
                    if (strtotime(date("Y-m-d H:i:s")) > strtotime(DB::table('panel_logins')->where('verify_code', $code)->value('timeout'))) {
                        $timeout = true;
                        break;
                    }
                }
                if ($timeout == false) {
                    $reader = DB::table('panel_logins')->where('verify_code', $code)->value('request_msg') != null;
                    $reader != null ? $msg = $reader : $msg = "验证服务器状态出错！";
                    DB::table('panel_logins')->where('verify_code', $code)->update(['response_status' => true]);
                    $success = true;
                } else {
                    DB::table('panel_logins')->where('verify_code', $code)->update(['response_status' => true, 'status' => 'timeout']);
                    $msg = "Auth事件处理服务器无响应！";
                    $success = false;
                }
            }
            return response()->json(['success' => $success, 'msg' => $msg]);
        } else {
            return view('auth.panel_login');
        }
    }

    public function login_time(Request $request)
    {
        $allrows = DB::table('panel_logins')->where([['is_read', false], ['status', 'null']])->get();
        foreach ($allrows as $row) {
            if ($request->session()->has('verify_password_' . $row->verify_code)) {
                try {
                    $password = decrypy($request->session()->get('verify_password_' . $row->verify_code));
                } catch (Exception $e) {
                    $request->session()->flash('verify_password_' . $row->verify_code);
                    DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'error', 'is_read' => true, 'request_msg' => '用户登录请求异常！']);
                    return false;
                }
                $username = $row->username;
                $userdata = DB::table('panel_users')->where('username', $username)->first();
                if (!$userdata->black_list) {
                    if (decrypt($userdata->password) === $password) {
                        $request->session()->flash('verify_password_' . $row->verify_code);
                        $request->session()->put('userid', $userdata->id);
                        $token = str_random(32);
                        $request->session()->put('login_token', $token);
                        DB::table('panel_users')->where('username', $row->username)->update(['lastest_ip' => $request->getClientIp(), 'token' => $token, 'updated_time' => date("Y-m-d H:i:s")]);
                        DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'success', 'is_read' => true, 'request_msg' => '用户登录成功！']);
                        Log::info("用户（" . $row->username . "）登陆成功！");
                        $success = true;
                    } else {
                        $request->session()->flash('verify_password_' . $row->verify_code);
                        DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'error', 'is_read' => true, 'request_msg' => '登录信息不正确！']);
                        Log::info("用户（" . $row->username . "）登陆失败，原因为密码不正确！");
                        $success = false;
                    }
                } else {
                    $request->session()->flash('verify_password_' . $row->verify_code);
                    DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'error', 'is_read' => true, 'request_msg' => '您被禁止登陆！']);
                    Log::info("用户（" . $row->username . "）登陆失败，原因为用户已在黑名单内！");
                    $success = false;
                }
            }
            continue;
        }
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $username = $request->input('username');
            $password = encrypt($request->input('password'));
            $phone = $request->input('phone');
            $email = $request->input('email');
            if (DB::table('panel_users')->where('username', $username)->first() == null) {
                $userkey = str_random(24);
                DB::table('panel_users')->insert(['username' => $username, 'password' => $password, 'email' => $email, 'phone' => $phone, 'key' => $userkey, 'created_time' => date("Y-m-d H:i:s")]);
                $success = true;
                $msg = "创建账户成功！";
            } else {
                $success = false;
                $msg = "用户名已经存在，请更换";
            }
            return response()->json(['success' => $success, 'msg' => $msg]);
        } else {
            return view('auth.panel_register');
        }
    }
}