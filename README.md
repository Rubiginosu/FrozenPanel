# FrozenPanel
A new Minecraft panel (Which supports FrozenGo)
一个全新的我的世界服务器管理面板（基于 FrozenGo）

## 介绍
此面板由 [FrozenGo](https://github.com/Rubiginosu/frozen-go) 项目创建，是一套全新的 Minecraft 服务器解决方案。

我们在 Multicraft 面板原有功能的基础上，自主开发并支持了一些独特功能

面板支持二次开发，无需开发者关心后端，可自行定制前端并快速部署后端功能。
FrozenPanel 使用 **[FrozenDaemon](https://github.com/Rubiginosu/FrozenDaemon) 作为服务支持，比MU方案更加高效！**

## 特色
- 优雅美观的UI
- 多服务器支持（物理服务器）,仅需一个面板即可管理所有Minecraft服务器!
- 子帐户系统（权限管理）,您无需将高权限账户给予他人，仅需按照所需权限分配账户，更加安全！
- 丰富的插件商店


## 安装
FrozenPanel 基于 [Laravel](https://laravel.com/) 开发，我们推荐您在以下环境安装：
1. [Homestead](http://d.laravel-china.org/docs/5.4/homestead)
2. [Valet](http://d.laravel-china.org/docs/5.4/valet) (Mac 用户)
3. [Laradock](http://laradock.io/) (推荐)

当然，您也可以自行搭建 Web Server，并将 FrozenPanel 克隆到您的 Web 根目录

>我们已为您开发了能使您快速安装面板的一键安装脚本，但以下内容仍是您需要注意的：<br />
>请您尽量事先安装Composer,PHP7.\*,Apache2.\*,Postgresql最新版本，脚本提供的环境安装服务可能无法兼容您的系统。<br />
>更多安装注意事项请查看Setup.docx<br />
#### 基础安装
请切换到 FrozenPanel 目录执行以下命令：
1. 安装拓展包依赖
```bash
composer install
```
2. 修改文件夹权限
```bash
chmod -R 777 storage
chmod -R 777 bootstrap/cache
chown -R www-data:www-data .
```
3. 生成配置文件与秘钥
```bash
cp .env.example .env
php artisan key:generate
```

4. 打开 ``.env`` 并填写相关设置
5. 生成数据表
```bash
php artisan migrate
```
