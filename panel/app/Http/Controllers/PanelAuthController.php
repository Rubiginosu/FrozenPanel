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

class PanelAuthController extends Controller
{
    public function __construct(SdkContract $sdk)
    {
        $this->sdk = $sdk;
    }

    public function login_face(Request $request)
    {
        if ($request->isMethod('post')) {
            $username = $request->input('username');
            $password = $request->input('password');
            $road = $request->input('pushroad');
            //$username='test';$password='test';$road='system';
            if (DB::table('panel_users')->where('username', $username)->first() == null) {
                $success = false;
                $msg = "登录信息不正确！";
            } else if ($request->session()->has('userid')) {
                $success = false;
                $msg = "您已经登录，请勿重复登录！";
            } else {
                $code = str_random(16);
                DB::table('panel_logins')->insert(['username' => $username, 'push_road' => $road, 'verify_code' => $code, 'timeout' => date("Y-m-d H:i:s", strtotime("+5 seconds")), 'is_read' => false, 'password_pl' => encrypt($password), 'response_status' => false]);
                //$request->session()->put('verify_password_' . $code, encrypt($password));
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
                    if (DB::table('panel_logins')->where('verify_code', $code)->value('status') == 'success') {
                        $userdata = DB::table('panel_users')->where('username', $username)->first();
                        $request->session()->put('userid', $userdata->id);
                        $token = str_random(32);
                        $request->session()->put('login_token', $token);
                        DB::table('panel_users')->where('username', $username)->update(['lastest_ip' => $request->getClientIp(), 'token' => $token, 'updated_at' => date("Y-m-d H:i:s")]);
                        $success = true;
                    } else {
                        $success = false;
                    }
                    DB::table('panel_logins')->where('verify_code', $code)->update(['is_read' => true, 'response_status' => true, 'password_pl' => 'null']);
                } else {
                    DB::table('panel_logins')->where('verify_code', $code)->update(['is_read' => true, 'response_status' => true, 'status' => 'timeout', 'password_pl' => 'null']);
                    $msg = "Auth事件处理服务器无响应！";
                    $success = false;
                }
            }
            return response()->json(['success' => $success, 'msg' => $msg]);
        } else {
            return view('Auth.panel_login');
        }
    }

    public function login_time(Request $request)
    {
        //进行预处理
        if (DB::table('panel_logins')->where([['is_read', true], ['response_status', 'null']])->first() != null) {
            $none_response = DB::table('panel_logins')->where([['is_read', true], ['response_status', false]])->get();
            foreach ($none_response as $none) {
                $errortime = date("Y-m-d H:i:s", strtotime("+1 hour", strtotime($none->timeout)));
                if (strtotime(date("Y-m-d H:i:s")) > strtotime($errortime)) {
                    DB::table('panel_users')->where('username', $none->username)->update(['black_list' => true, 'token' => '', 'updated_at' => date("Y-m-d H:i:s")]);
                    Log::error("【登录id：" . $none->verify_code . "】登录请求源头未处理返回，疑似非法调用，系统已执行封号处理。");
                    DB::table('panel_logins')->where('verify_code', $none->verify_code)->update(['response_status' => true, 'msg' => '疑似非法，已拦截', 'status' => 'error']);
                }
            }
        }
        $new = DB::table('panel_logins')->where([['is_read', false], ['status', 'null']])->first();
        if ($new != null) {
            $allrows = DB::table('panel_logins')->where([['is_read', false], ['status', 'null']])->get();
            foreach ($allrows as $row) {
                Log::info($allrows);
                if (true) {
                    try {
                        $password = decrypt($row->password_pl);
                    } catch (Exception $e) {
                        DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'error', 'is_read' => true, 'request_msg' => '用户登录请求异常！', 'password_pl' => 'null']);
                        return false;
                    }
                    $username = $row->username;
                    $userdata = DB::table('panel_users')->where('username', $username)->first();
                    if (!$userdata->black_list) {
                        if (decrypt($userdata->password) === $password) {
                            DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'success', 'is_read' => true, 'request_msg' => '用户登录成功！', 'password_pl' => 'null']);
                            Log::info("用户（" . $row->username . "）登陆成功！");
                            $success = true;
                        } else {
                            DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'error', 'is_read' => true, 'request_msg' => '登录信息不正确！', 'password_pl' => 'null']);
                            Log::info("用户（" . $row->username . "）登陆失败，原因为密码不正确！");
                            $success = false;
                        }
                    } else {
                        DB::table('panel_logins')->where('verify_code', $row->verify_code)->update(['status' => 'error', 'is_read' => true, 'request_msg' => '您被禁止登陆！', 'password_pl' => 'null']);
                        Log::info("用户（" . $row->username . "）登陆失败，原因为用户已在黑名单内！");
                        $success = false;
                    }
                }
                continue;
            }
        }
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $username = $request->input('username');
            $password = encrypt($request->input('password'));
            $phone = $request->input('phone');
            $email = $request->input('email');
            //$username='test';$password=encrypt('test');$email='test@test.com';
            if (DB::table('panel_users')->where('username', $username)->first() == null) {
                $userkey = str_random(24);
                DB::table('panel_users')->insert(['username' => $username, 'password' => $password, 'email' => $email, 'key' => $userkey, 'created_at' => date("Y-m-d H:i:s")]);
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

    public function logout(Request $request)
    {
        if ($request->session()->has('userid')) {
            $userdata = $this->sdk->getUser($request);
            $request->session()->forget('userid');
            $request->session()->forget('login_token');
            DB::table('panel_users')->where('username', $userdata->username)->update(['token' => '']);
            Log::info("用户（" . $userdata->username . "）退出成功！");
            $msg = '您已成功退出！';
            return response()->json(['success' => true, 'msg' => $msg]);
        } else {
            $msg = '您还未登录！';
            return response()->json(['success' => false, 'msg' => $msg]);
        }
    }
}