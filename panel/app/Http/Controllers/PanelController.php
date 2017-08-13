<?php
namespace App\Http\Controllers;

/*
 * PanelController V0.1 Alpha
 * Author:XueluoPoi Date:2017.7.29
 * 此控制器针对进入面板后端逻辑
 */

use Log;
use App\Panel_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
require_once __DIR__ . '/../../../public/FrozenGo.php';

class PanelController extends Controller
{
    public function index(Request $request)
    {
        echo 'continue.......';
        if($this->is_login($request)){
            $userdata=$this->getUser($request);
            if($userdata->permission=='superadmin'){
                return reidrect()->action('PanelController@admin_index');
            }else{
                return view('server');
            }
        }
    }

    public function view_server(Request $request)
    {
        $serverid = $request->input('id');
        $playserverid = $request->input('playid');
        if ($this->is_login($request)) {
            if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("view_server"), $serverid, $playserverid)) {
                return view('server', ['key' => $this->keygen($playserverid), 'playid' => $playserverid, 'id' => $serverid]);
            }
        }
    }

    private function is_login($request)
    {
        if ($request->session()->has('userid')) {
            $data = $this->getUser();
            if ($data->token === $request->session()->get('login_token')) {
                return true;
            }
        }
        return redirect()->action('PanelController@login_face');
    }

    public function readini()
    {
        $serverINI = DB::table('panel_config')->where('name', 'manyServers')->value('value');
        return $serverINI;
    }

    private function getSock($ip, $port, $code)
    {
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

    public function portal(Request $request)
    {
        $sock = $this->getSock(0, 0, 0);//获取socket对象
        if ($sock != false) {
            switch ($request->input('action')) {
                case 'start': {
                    if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("startServer"), $request->input('serverid'), $request->input('playserverid'))) {
                        $this->start($sock, $request->input('playserverid'));
                        return true;
                    } else return false;
                };
                    break;
                case 'stop': {
                    if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("stopServer"), $request->input('serverid'), $request->input('playserverid'))) {
                        $this->stop($sock, $request->input('playserverid'));
                    } else return false;
                };
                    break;
                case 'restart': {
                    if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("restartServer"), $request->input('serverid'), $request->input('playserverid'))) {
                        $this->restart($sock, $request->input('playserverid'));
                    } else return false;
                };
                    break;
                case 'create': {
                    if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("createServer"), $request->input('serverid'), $request->input('playserverid'))) {
                        $status = $this->createServer($sock, $request->input('playserverid'));
                        if ($status != true) return $status;
                    } else return false;
                };
                    break;
                case 'delete': {
                    if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("deleteServer"), $request->input('serverid'), $request->input('playserverid'))) {
                        $status = $this->deleteServer($sock, $request->input('playserverid'));
                        if ($status != true) return $status;
                    } else return false;
                };
                case 'test': {
                    if ($this->chkUsers($request, $request->session()->get('login_token'), encrypt("test"), $request->input('serverid'), $request->input('playserverid'))) {
                        echo "yes";
                    } else echo "no";
                }
                default:
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }

    private function start($sock, $serid)
    {
        $sock->startServer($serid);
    }

    private function stop($sock, $serid)
    {
        $sock->stopServer($serid);
    }

    private function restart($sock, $serid)
    {
        $sock->startServer($serid);
        $sock->stopServer($serid);
    }

    private function createServer($sock, $serid)
    {
        $data = $sock->createServer($serid);
        if ($data == 'OK') {
            return true;
        } else {
            return $data;
        }
    }

    private function deleteServer($sock, $serid)
    {
        $data = $sock->deleteServer($serid);
        if ($data == 'OK') {
            return true;
        } else {
            return $data;
        }
    }

    public function try_bind(Request $request)
    {
        $ip = $request->input('ip');
        $port = $request->input('port');
        $code = $request->input('code');
        $sock = $this->getSock($ip, $port, $code);
        if (!$sock) return true;
        else return false;
    }

    public function getinfo_server(Request $request)
    {

    }

    public function checkUser(Request $request)
    {
        /**
         * 此功能充当Panel用户组的核心结构
         * 返回true代表有权限进行本次操作，false代表无权限
         * @param opeareID：服务器ID
         * 使用规则：当操作涉及到验证用户所属服务器时务必先调用relations_server(),当用户权限仅有三种基本权限时可调用relations_users的level 2
         */
        $status = $this->chkUsers($request, $request->session()->get('login_token'), $request->input('actionSign'), $request->input('serverID'), $request->input('play_serverID'));
        return $status;
    }

    private function chkUsers($request, $token, $actionSign, $serverID, $playserverID)
    {
        try {
            $sign = decrypt($actionSign);
        } catch (DecryptException $e) {
            return false;
        }
        $userData = $this->getUser($request);
        if ($token === $userData->token) {
            if ($userData->permission == "superadmin") return true;
            if ($this->relations_users($userData, $sign, $userData->permission, 1)) {
                if ($this->before_chkUsers($userData) == 2 && $this->relations_server($serverID, $playserverID, $this->getUser($request), 1)) {//不等于00代表本操作是需要验证serverid的
                    $permitID = str_random(32);
                    $sessionLog = $permitID . '.' . $sign;
                    $request->session()->put('permitID', $sessionLog);
                    DB::table('panel_actions')->where('name', $sign)->update(['lastest_user' => $userData->username, 'permitID' => $permitID]);
                    Log::info("准许用户：" . $userData->username . "进行：" . $sign . "操作！");
                    return true;
                } else if ($this->before_chkUsers($userData) == 1 && $this->relations_server($serverID, $playserverID, $this->getUser($request), 2)) {
                    $permitID = str_random(32);
                    $sessionLog = $permitID . '.' . $sign;
                    $request->session()->put('permitID', $sessionLog);
                    DB::table('panel_actions')->where('name', $sign)->update(['lastest_user' => $userData->username, 'permitID' => $permitID]);
                    Log::info("准许用户：" . $userData->username . "进行：" . $sign . "操作！");
                    return true;
                } else {
                    Log::info("进行用户权限检查时发生错误：用户（" . $userData->username . "）没有进行操作：" . $sign . "的权限！");
                    return false;
                }
            } else {
                Log::info("进行用户权限检查时发生错误：用户（" . $userData->username . "）没有进行操作：" . $sign . "的权限！");
                return false;
            }
        } else {
            Log::info("进行用户权限检查时发生错误：用户登录记录不正确，疑似非法登录！");
            return false;
        }
    }

    private function before_chkUsers($userData)
    {
        if ($userData->permission == 'admin') {
            return "1";
        } else {
            return "2";
        }
    }

    private function relations_users($userData, $action, $obj, $level)
    {
        $flag = false;
        if ($level == 1) {//多服务器模式
            //查找action操作是否在当前用户可操作范围内
            if (DB::table('panel_actions')->where('name', $action)->value('permission') == $userData->permission) return true;
            else {
                $actiondata = DB::table('panel_relations')->where('basic_action', $action)->get();
                $alldata = DB::table('panel_relations')->where('group', 1)->get();
                $arr = array();//存入所有有权限操作action操作的权限名
                foreach ($actiondata as $va) {
                    $arr = array_add($va->name);
                }
                foreach ($alldata as $value) {
                    foreach ($arr as $values) {
                        if ($value->permission_bind == $values) $arr = array_add($value->name);
                    }
                }
                foreach ($arr as $arrva) {
                    if ($userData->permission == $arrva) {
                        $flag = true;
                        break;
                    }
                }
            }
        } else {//单服务器模式
            //判断当前用户权限是否在obj权限之上（例如当前用户权限是superadmin，obj规定为admin，即通过
            if ($obj == "buyer") {
                if ($userData->permission == "buyer" || $userData->permission == "admin" || $userData->permission == "superadmin") {
                    $flag = true;
                }
            } else if ($obj == "admin") {
                if ($userData->permission == "admin" || $userData->permission == "superadmin") {
                    $flag = true;
                }
            } else if ($obj == "superadmin") {
                if ($userData->permission == "superadmin") {
                    $flag = true;
                }
            } else {
                $flag = false;
            }
        }
        return $flag;
    }

    private function relations_server($obj1, $obj2, $user, $level)
    {
        //证明这个obj2游戏服务器属于obj1主服务器，并且user也属于这个服务器(前提是进行了relations_users操作并且true）
        $flag = false;
        $serverdata = DB::table('panel_servers_relations')->where('serverid', $obj1)->get();
        if ($level == 1) {//1代表版主级别，为多服务器做准备
            foreach ($serverdata as $va) {
                if ($va->play_serverid == $obj2 && $user->group_server == $obj1 && $user->group == $obj2) {
                    $flag = true;
                    break;
                }
            }
        } else if ($level == 2) {
            if ($obj1 == $user->group_server) $flag = true;
        } else {
            //单服务器模式
            if ($obj1 == $user->group) $flag = true;
        }
        if ($flag) return true;
        else return false;
    }

    private function getUser(Request $request)
    {
        $userid = $request->session()->get('userid');
        $data = DB::table('panel_users')->where('id', $userid)->first();
        return $data;
    }

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
            return view('panel_login');
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
            return view('panel_register');
        }
    }

    public function keygen($id)
    {
        $key = encrypt(str_random(32) . '|' . date("Y-m-d H:i:s"));
        $sock = $this->getSock(0, 0, 0);
        if ($sock != false) {
            $sock->keyRegister($key, $id);
        }
        return true;
    }

    public function upload(){
        //who cares?
    }

    public function admin_index(Request $request){
        return view('admin_index');
    }
}