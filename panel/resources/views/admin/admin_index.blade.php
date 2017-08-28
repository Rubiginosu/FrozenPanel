@extends('layout.main')

@section('title','管理')

@section('container','')

@section('content')

    <script>
        $(function() {
            $('.message .close').on('click', function () {
                $(this).closest('.message').transition('fade');
            });
            $('.ui.accordion').accordion();
            $('.ui.checkbox').checkbox();
            $('.ui.dropdown').dropdown();
        });
    </script>
<div class="ui grid">
    <div class="sixteen wide column">
        <div class="ui stackable inverted menu">
            <div class="item">
                <img src="<?php public_path()?>/fg-ico.png">
            </div>
            <a class="active item">管理后台</a>
            <a class="item">前往面板</a>
            <a class="item">退出</a>
        </div>
    </div>
    <div class="one wide column"></div>
    <div class="twelve wide column">
        <div class="errors"></div>
        <div class="ui floating teal message transition">
            <i class="close icon"></i>
            <div class="header">
                欢迎回来！
            </div>
            <p>{{ $username }}，您可在此掌控您面板的一切信息，甚至毁掉它。</p>
        </div>
        <div class="ui raised segment">
            <div class="ui warning message">
                <i class="close icon"></i>
                <div class="header">
                    您的面板正处于危险中！
                </div>
                系统检测到您的面板没有开启安全模式，请点击下面的按钮开启：<br />
                <div class="ui segment">
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" name="gift" tabindex="0" class="hidden">
                            <label>开启面板安全模式（推荐单服务器模式开启）</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui grid">
                <div class="three column row">
                    <div class="column">
                        <div class="ui orange card">
                            <div class="content">
                                <div class="header">面板用户数</div>
                                <div class="meta">指所有有效注册的用户总数</div>
                                <div class="description">
                                    <center><strong><h1>{{ $usercount }}</h1></strong></center>
                                </div>
                            </div>
                            <div class="extra content">
                                <i class="check icon"></i>
                                更新时间
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui orange card">
                            <div class="content">
                                <div class="header">已开通服务器</div>
                                <div class="meta">指所有被开通的游戏服务器总数</div>
                                <div class="description">
                                    <center><strong><h1>100</h1></strong></center>
                                </div>
                            </div>
                            <div class="extra content">
                                <i class="check icon"></i>
                                更新时间
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui orange card">
                            <div class="content">
                                <div class="header">紧急事件</div>
                                <div class="meta">指您务必重视的事件数量</div>
                                <div class="description">
                                    <center><strong><h1>0</h1></strong></center>
                                </div>
                            </div>
                            <div class="extra content">
                                <i class="check icon"></i>
                                请在 右侧菜单>信息枢纽 查看
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <h3 class="ui dividing header">
                Daemon运行状况
            </h3>
            <div class="daemon_msg">
                <div class="ui info message daemon_msg">
                    <i class="close icon"></i>
                    <div class="daemon_msg header">
                        Daemon智能连接中...
                    </div>
                    <ul class="list">
                        <div class="daemon_msg_active"><li>我们正在尝试连接所有Daemon...</li></div>
                    </ul>
                </div>
            </div>
            <div class="ui segment">
                <div class="lod">
                    <div class="ui active dimmer">
                        <div class="ui text loader daemon-info">正在加载</div>
                    </div>
                </div>
                <table class="ui celled table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Daemon IP</th>
                        <th>链接状态</th>
                        <th>类型</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($daemon as $d)
                            <td>{{ $d->other }}</td>
                            <td>{{ $d->value }}</td>
                            <td class="td-{{ $d->other }}"><div class="{{ $d->other }}"><i class="icon checkmark"></i>测试连接...</div></td>
                            <td>FGO</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <h3 class="ui dividing header">
                面板实时
            </h3>
            <div class="ui feed">
                <div class="event">
                    <div class="label">
                        <i class="pencil icon"></i>
                    </div>
                    <div class="content">
                        <div class="summary">
                            <a class="user">
                                管理员
                            </a> 新增了服务器（#0001）
                            <div class="date">
                                1小时之前
                            </div>
                        </div>
                        <div class="meta">
                            <p>该操作不受争议</p>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="ui dividing header">
                常见问题
            </h3>
            <div class="ui styled fluid accordion">
                <div class="title">
                    <i class="dropdown icon"></i>
                    本次面板更新新增了哪些内容？
                </div>
                <div class="content">
                    <p class="transition hidden">暂时没有获取到上一次更新信息</p>
                </div>
                <div class="title">
                    <i class="dropdown icon"></i>
                    '面板实时'是什么
                </div>
                <div class="content">
                    <p class="transition hidden">'面板实时'会显示一切最近进行的操作内容，不放过任何一个可疑操作。</p>
                </div>
            </div>
        </div>
    </div>
    <div class="three wide column">

        <div class="ui vertical menu">
            <div class="item">
                <div class="ui input search"><input type="text" placeholder="搜索设置..."></div>
            </div>
            <div class="item">
                仪表盘
                <div class="menu">
                    <a class="active item" href="#">概览页</a>
                    <a class="item" href="{{ url('/admin/servers') }}">服务器管理</a>
                    <a class="item">权限系统</a>
                    <a class="item">用户管理</a>
                </div>
            </div>
            <a class="item">
                <i class="setting icon"></i> 面板设置
            </a>
            <a class="item">
                信息枢纽
            </a>
            <a class="item">
                服务监控
            </a>
            <div class="item">
                {{ $username }}
                <div class="menu">
                    <a class="item"><i class="edit icon"></i> 更改密码</a>
                    <a class="item" href="{{ url('auth/logout') }}"><i class="globe icon"></i> 退出</a>
                </div>
            </div>
        </div>

    </div>
</div>
    <script>
        $(function(){
            $.fn.api.settings.api={
                'trybind':'{{ url('/api/daemon_test') }}'
            };
            $(".daemon-info").api({
                action:'trybind',
                on:'now',
                method:'POST',
                beforeXHR: function(xhr){
                    xhr.setRequestHeader('X-CSRF-TOKEN',$('meta[name="csrf-token"]').attr('content'));
                    return xhr;
                },
                onSuccess: function(response){
                    if(response.success==true){
                        var flag=true;
                        $(response.data).each(function(i,val){
                            if(val.status=="连接正常"){
                                $("." + val.id).html('<i class="icon checkmark"></i>'+val.status+'');
                                $(".td-" + val.id).attr("class","positive");
                                $(".daemon_msg").html()
                            }else{
                                $("." + val.id).html('<i class="icon close"></i>'+val.status+'');
                                $(".td-" + val.id).attr("class","negative");
                                $(".daemon_msg .list").append('<li>[#'+val.id+']Daemon连接异常，请检查！</li>');
                                flag=false;
                            }
                            $(".lod").html('');
                        });
                        if(flag==false){
                            $(".daemon_msg_active").html('');
                            $(".daemon_msg .message").attr("class","ui error message");
                            $(".daemon_msg .header").html("Daemon连接出现问题！");
                        }else{
                            $(".daemon_msg .header").html("Daemon暂无异常！");
                            $(".daemon_msg_active").html('<li>若您发现Daemon智能检查未发现问题，但无法连接Daemon，请尝试重启Daemon！</li>');
                        }
                    }else{
                        $('.errors').html('<div class="ui red message">'+response.errormsg+'</div>');
                        $(".lod").html('');
                    }
                },
                onFailure: function(response){
                    $('.errors').html('<div class="ui red message">'+response.errormsg+'</div>');
                }
            });
        });
    </script>
@endsection