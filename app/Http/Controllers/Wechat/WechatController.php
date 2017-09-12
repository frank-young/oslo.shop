<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\OpenPlatform\Guard;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class WechatController extends Controller
{
  protected $openPlatform;
  /**
  *
  * WechatNotice constructor.
  */
  public function __construct()
  {
    $app = new Application(config('wechat'));
    $this->openPlatform = $app->open_platform;
  }
  /**
   * 处理微信的请求消息
   *
   * @return string
   */
  public function notify(Request $request)
  {
    $ticket = new Ticket;
    $ticket->content = '访问成功';
    $ticket->save();

    $this->openPlatform->verify_ticket->setTicket($request);
    return 'success';
  }

  public function token()
  {
    var_dump($this->openPlatform->access_token->getTokenFromServer());
    // var_dump($this->openPlatform->verify_ticket->getTicket());
  }

  public function getCode()
  {
    var_dump($this->openPlatform->pre_auth->getCode());
  }

  public function getUrl()
  {
    $response = $this->openPlatform->pre_auth->redirect(config('wechat.oauth.callback'));
    $data = redirect($response->getTargetUrl());
    return view('wechat.authorization', compact('data'));
  }

  public function callback()
  {
    $server = $this->openPlatform->server;
    $server->setMessageHandler(function($event) use ($openPlatform) {
      switch ($event->InfoType) {
        case Guard::EVENT_AUTHORIZED: // 授权成功
          $authorizationInfo = $openPlatform->getAuthorizationInfo($event->AuthorizationCode);
        // 保存数据库操作等...
        case Guard::EVENT_UPDATE_AUTHORIZED: // 更新授权
        // 更新数据库操作等...
        case Guard::EVENT_UNAUTHORIZED: // 授权取消
        // 更新数据库操作等...
        }
    });
    $response = $server->serve();
    return $response;
  }

  public function getInfo()
  {
    $authCode = $this->openPlatform->pre_auth->getCode();
    var_dump($this->openPlatform->api->getAuthorizationInfo());
  }

  public function wxauth() {
    $wechat = app("OpenWechat");
    //验证消息前面并解密
    $wechat->valid();

    //接收用户发送到微信的数据
    $receive = $wechat->getRev()->getRevData();

    //回复消息
    return $wechat->text("hello".$appid)->reply(null, true);
  }

  public function serve()
  {
    $app = new Application(config('wechat'));
    $response = $app->server->serve();
    // 将响应输出
    return $response;
  }
}
