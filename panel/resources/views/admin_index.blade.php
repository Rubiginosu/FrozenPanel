@extends('layout.main')

@section('title','管理')

@section('content')

<div class="ui grid">
    <div class="sixteen wide column">
        <div class="ui stackable inverted menu">
            <div class="item">
                <img src="<?php public_path()?>/fg-ico.png">
            </div>
            <a class="item">Features</a>
            <a class="item">Testimonials</a>
            <a class="item">Sign-in</a>
        </div>
    </div>
    <div class="ten wide column">
        <div class="ui raised segment">
            <h2 class="ui block header">
                <i class="settings icon"></i>
                <div class="content">
                    概览
                    <div class="sub header">有关面板的部分重要内容</div>
                </div>
            </h2>
        </div>
    </div>
    <div class="six wide column">

        <div class="ui vertical menu">
            <div class="item">
                <div class="ui input search"><input type="text" placeholder="搜索设置..."></div>
            </div>
            <div class="item">
                仪表盘
                <div class="menu">
                    <a class="active item">概览页</a>
                    <a class="item">服务器管理</a>
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