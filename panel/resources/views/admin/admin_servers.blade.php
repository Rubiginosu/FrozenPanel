@extends('layout.main')

@section('title','管理')

@section('container','')

@section('content')

    <script>
        $(function() {
            $('.message .close').on('click', function () {
                $(this).closest('.message').transition('fade');
            });
            $('.ui.dropdown').dropdown();
            $('.ui.checkbox').checkbox();
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
            <div class="ui segments">
                <div class="ui segment">
                    <p>概况</p>
                </div>
                <div class="ui secondary segment">
                    <div class="ui positive message">
                        <i class="close icon"></i>
                        <div class="header">
                            暂时没有监控到异常情况！
                        </div>
                        <p>当出现（过多服务器新增、无法连接到服务器、数据无法查询）等状况时，我们会及时通过站内信通知您！</p>
                    </div>
                    <table class="ui single line table">
                        <thead>
                        <tr>
                            <th>项</th>
                            <th>详细</th>
                            <th>比例</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>在运行的服务器</td>
                            <td>10 台</td>
                            <td>占总数的 %100</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <h3 class="ui dividing header">
                管理
            </h3>
            <table class="ui compact celled definition table">
                <thead class="full-width">
                <tr>
                    <th>状态</th>
                    <th>服务器名</th>
                    <th>到期时间</th>
                    <th>拥有者</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="collapsing">
                        <div class="ui fitted slider checkbox">
                            <input type="checkbox"> <label></label>
                        </div>
                    </td>
                    <td>测试</td>
                    <td>September 14, 2013</td>
                    <td>admin</td>
                    <td><div class="ui floating teal labeled icon dropdown button">
                            <i class="add user icon"></i>
                            <span class="text">请选择操作</span>
                            <div class="menu">
                                <div class="header">
                                    请选择操作
                                </div>
                                <div class="item">
                                    详细信息
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tfoot class="full-width">
                <tr>
                    <th></th>
                    <th colspan="4">
                        <div class="ui right floated small primary labeled icon button">
                            <i class="add icon"></i> 新增服务器
                        </div>
                        <div class="ui small  button">
                            刷新
                        </div>
                        <div class="ui small  disabled button">
                            保存全部
                        </div>
                    </th>
                </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
        <div class="three wide column">

            <div class="ui vertical menu">
                <div class="item">
                    <div class="ui input search"><input type="text" placeholder="搜索设置..."></div>
                </div>
                <div class="item">
                    仪表盘
                    <div class="menu">
                        <a class="item" href="{{ url('/admin/index') }}">概览页</a>
                        <a class="active item" href="#">服务器管理</a>
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
                <div class="ui dropdown item">
                    用户名
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item"><i class="edit icon"></i> 更改密码</a>
                        <a class="item"><i class="globe icon"></i> 退出</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection