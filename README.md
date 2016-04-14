# 然之协同系统(ranzhi)

<font color="#4078c0">好雨云 一键部署 版本，与官方同步更新</font>


<img src="http://www.goodrain.com/images/apps/ranzhi/logo.png" width="145px" height="50px"></img>

然之协同系统由客户管理(crm)、日常办公(oa)、现金记账(cash)、团队分享(team)和应用导航(ips)五大模块组成，主要面向中小团队的企业内部管理。和市面上其他的产品相比，然之协同更专注于提供一体化、精简的解决方案。

更多信息参见[官方介绍](https://www.ranzhico.com/book/ranzhi.html)

<a href="#" target="_blank" ><img src="http://www.goodrain.com/images/deploy/button_160125.png" width="147" height="32"></img></a>

# 目录
- [部署到好雨云](#部署到好雨云)
  - [一键部署](#一键部署)
  - [配置向导](#配置向导)
    - [欢迎页面](#欢迎页面)
    - [授权协议](#授权协议)
    - [系统检查](#系统检查)
    - [生产配置文件](#生产配置文件)
    - [保存配置文件](#保存配置文件)
    - [设置帐号](#设置帐号)
    - [安装完成](#安装完成)
    - [登录](#登录)
- [参与和讨论](#参与和讨论)
- [版权说明](#版权说明)

# 部署到好雨云
## 一键部署
通过点击本文最上方的 “部署到好雨云” 按钮会跳转到 好雨应用市场的应用首页中，可以通过一键部署按钮安装


**部署然之**

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy00.png" width="70%" height="70%"></img>


**注意：** 由于然之协同系统需要MySQL数据库，因此部署时会提示用户新建MySQL应用或者选择一个已有的MySQL应用。

## 配置向导
部署完成后 应用首页 点击 “访问” 按钮会跳转到然之的安装向导页面，如下图：

### 欢迎页面

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy01.png" width="70%" height="70%"></img>


### 授权协议

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy02.png" width="70%" height="70%"></img>

### 系统检查

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy03.png" width="70%" height="70%"></img>

### 生产配置文件
> 请根据关联的MySQL实际情况填写连接信息，可以在MySQL首页，或者然之的依赖页面查看到MySQL的连接信息。

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy04.png" width="70%" height="70%"></img>

### 保存配置文件

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy_saveconfig.png" width="70%" height="70%"></img>

### 设置管理帐号

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy05.png" width="60%" height="60%"></img>

### 安装完成

> - 安装完成后平台会自动删除 `install.php` 和 `upgrade.php` 文件
> - 如果需要重新初始化配置，只需要删除 `../config/my.php` 文件即可

### 登录然之

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy06.png" width="50%" height="50%"></img>

**然之系统截图**

<img src="http://www.goodrain.com/images/apps/ranzhi/deploy07.png" width="60%" height="60%"></img>

# 参与和讨论
如果您对本项目感兴趣或有疑问可以在好雨讨论社区[发表评论](http://t.goodrain.com/c/11-category)

# 版权说明
本项目同步更新 [然之](https://www.ranzhico.com/download.html) 官方发布的开源版本，并适配好雨云的一键部署 功能。

[然之](https://www.ranzhico.com/) 开源版本 版权归[青岛易软天创网络科技有限公司](http://www.cnezsoft.com/)所有并遵循原软件的[版权规则](https://github.com/goodrain-apps/ranzhi/blob/master/doc/LICENSE)
