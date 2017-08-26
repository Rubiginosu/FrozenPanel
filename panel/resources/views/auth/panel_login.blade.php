<!DOCTYPE html>
<html lang="zh-CN" class="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="X-ZA-Response-Id" content="11ef763f19c240cc">
    <meta http-equiv="X-ZA-Experiment" content="default:None,ge3:ge3_9,nweb_sticky_sidebar:sticky,ge2:ge2_1,live_store:ls_a2_b2_c1_f2,wechat_share_modal:wechat_share_modal_show,home_ui2:default,new_more:new,fav_act:default,mobile_qa_page_proxy_heifetz:m_qa_page_nweb,home_nweb:default,iOS_newest_version:3.55.0,qrcode_login:pwd,zcm-lighting:zcm,qa_sticky_sidebar:sticky_sidebar,android_profile_panel:panel_b">
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <title>Frozen GO Login</title>



    <link rel="shortcut icon" href="https://static.zhihu.com/static/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{ URL::asset('assets/js/main.head.css') }}">
    <!--[if lt IE 9]>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    <script src="{{ URL::asset('assets/js/intent.head.css') }}"></script>
</head>
<body class="zhi ">




<div class="index-main">
    <div class="index-main-body">
        <div class="index-header">



            <h2 class="subtitle">Frozen GO</h2>
        </div>

        <div class="desk-front sign-flow clearfix sign-flow-simple">

            <div class="index-tab-navs">
                <div class="navs-slider">
                    <a href="#signup" class="active">注册</a>
                    <a href="#signin">登录</a>
                    <span class="navs-slider-bar"></span>
                </div>
            </div>


            <div class="view view-signin" data-za-module="SignInForm">
                <form method="POST">
                    <input type="hidden" name="_xsrf" value="30393335336665662d323662352d343034382d616561372d613231353331333030393634"/>
                    <div class="group-inputs">

                        <div class="account input-wrapper">

                            <input type="text" name="account" aria-label="手机号或邮箱" placeholder="手机号或邮箱" required>
                        </div>
                        <div class="verification input-wrapper">
                            <input type="password" name="password" aria-label="密码" placeholder="密码" required /><button type="button" class="send-code-button">获取验证码</button>
                        </div>

                        <div class="Captcha input-wrapper" data-type="cn" data-za-module="Captcha">
                            <div class="Captcha-operate">
                                <input type="hidden" name="captcha" required data-rule-required="true" data-msg-required="请点击图中所有倒立的文字">
                                <input type="hidden" name="captcha_type" value="cn" required>
                                <label class="Captcha-prompt">请点击图中所有倒立的文字</label>
                                <span class="Captcha-refresh js-refreshCaptcha sprite-index-icon-refresh"></span>
                            </div>
                            <div class="Captcha-imageConatiner">
                                <img class="Captcha-image" alt="验证码" >
                            </div>
                        </div>

                    </div>
                    <div class="button-wrapper command">
                        <button class="sign-button submit" type="submit">登录</button>
                    </div>
                    <div class="signin-misc-wrapper clearfix">

                        <button type="button" class="signin-switch-button">手机验证码登录</button>

                        <a class="unable-login" href="#">无法登录？</a>
                    </div>

                    <div class="other-signup-wrapper" data-za-module="SNSSignIn">

                        <span class="name signin-switch-qrcode-buttons">二维码登录</span>
                        <span class="signup-footer-separate signup-footer-se"> · </span>

                        <span class="name signup-social-buttons js-toggle-sns-buttons">社交帐号登录</span>

                        <div class="sns-buttons">
                            <a title="微信登录" class="js-bindwechat" href="#"><i class="sprite-index-icon-wechat"></i></a>
                            <a title="微博登录" class="js-bindweibo" href="#"><i class="sprite-index-icon-weibo"></i></a>
                            <a title="QQ 登录" class="js-bindqq" href="#"><i class="sprite-index-icon-qq"></i></a>
                        </div>


                    </div>

                </form>

                <div class="qrcode-signin-container">
                    <div class="qrcode-signin-step1">

                        <p>打开最新 自由云 APP</a></p>
                        <p>在「更多」页面右上角打开扫一扫</p>
                        <div class="qrcode-signin-cut-button">
                            <span class="signin-switch-password">使用密码登录</span>
                        </div>
                    </div>
                    <div class="qrcode-signin-step2">
                        <div class="qrcode-signin-scan-status"></div>
                        <p class="qrcode-signin-scan-tips">扫描成功</p>
                        <p>请在手机上「确认登录」</p>
                        <div class="qrcode-signin-cut-button">
                            <span class="qrcode-goto-scan">返回二维码</span>
                        </div>
                    </div>
                    <div class="qrcode-signin-failure">
                        <div class="qrcode-signin-failure-icon"></div>
                        <p class="qrcode-signin-failure-message"></p>
                        <div class="qrcode-signin-cut-button">
                            <span class="signin-switch-password">使用密码登录</span>
                        </div>
                    </div>
                    <div class="qrcode-signin-guide"></div>
                </div>






            </div>
            <div class="view view-signup selected" data-za-module="SignUpForm">
                <form class="zu-side-login-box" action="/register/email" id="sign-form-1" autocomplete="off" method="POST">
                    <input type="password" hidden>
                    <input type="hidden" name="_xsrf" value="30393335336665662d323662352d343034382d616561372d613231353331333030393634"/>
                    <div class="group-inputs">


                        <div class="name input-wrapper">
                            <input required type="text" name="fullname" aria-label="用户名" placeholder="用户名">
                        </div>
                        <div class="email input-wrapper">

                            <input required type="text" class="account" name="phone_num" aria-label="手机号" placeholder="手机号">

                        </div>

                        <div class="input-wrapper">
                            <input required type="password" name="password" aria-label="密码" placeholder="密码（不少于 6 位）" autocomplete="off">
                        </div>

                        <div class="Captcha input-wrapper" data-type="cn" data-za-module="Captcha">
                            <div class="Captcha-operate">
                                <input type="hidden" name="captcha" required data-rule-required="true" data-msg-required="请点击图中所有倒立的文字">
                                <input type="hidden" name="captcha_type" value="cn" required>
                                <label class="Captcha-prompt">请点击图中所有倒立的文字</label>
                                <span class="Captcha-refresh js-refreshCaptcha sprite-index-icon-refresh"></span>
                            </div>
                            <div class="Captcha-imageConatiner">
                                <img class="Captcha-image" alt="验证码" >
                            </div>
                        </div>

                    </div>
                    <div class="button-wrapper command">
                        <button class="sign-button submit" type="submit">注册</button>
                    </div>
                </form>

                <p class="agreement-tip">点击「注册」按钮，即代表你同意<a href="/terms" target="_blank">《用户协议》</a></p>

                <div class="QRCode">
                    <div class="QRCode-card">
                        <div class="QRCode-image"></div>
                        <div class="sprite-index-icon-arrow"></div>
                    </div>
                </div>



            </div>
        </div>
    </div>

</div>
<div class="footer">

    <span>&copy; 2017 Frozen GO</span>
</div>

<script src="{{ URL::asset('assets/js/vender.cb.js') }}"></script>
<script src="{{ URL::asset('assets/js/base.js') }}"></script>

<script src="{{ URL::asset('assets/js/common.js') }}"></script>
<script src="{{ URL::asset('assets/js/page_index.js') }}"></script>
<meta name="entry" content="ZH.entrySignPage" data-module-id="page-index">
</body>
</html>