<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\OpenPlatform\Guard;
use App\Models\Ticket;

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
    $openPlatform = $app->open_platform;
  }
  /**
   * 处理微信的请求消息
   *
   * @return string
   */
  public function notify()
  {
    $ticket = new Ticket;
    $ticket->content = '访问成功';
    $ticket->save();

    // $app = new Application(config('wechat'));
    // $openPlatform = $app->open_platform;
    // $server = $openPlatform->server;
    // return $server->serve();

    $server = $this->openPlatform->server;
    return $server->serve();

    // $server->setMessageHandler(function($event) use ($openPlatform) {
    // switch ($event->InfoType) {
    //   case Guard::EVENT_AUTHORIZED: // 授权成功
    //     $authorizationInfo = $openPlatform->getAuthorizationInfo($event->AuthorizationCode);
    //     // 保存数据库操作等...
    //     $ticket->content = '保存成功';
    //     $ticket->save();
    //   case Guard::EVENT_UPDATE_AUTHORIZED: // 更新授权
    //   // 更新数据库操作等...
    //   case Guard::EVENT_UNAUTHORIZED: // 授权取消
    //   // 更新数据库操作等...
    //   }
    // });
    // $response = $server->serve();
    // return $response;
  }

  public function token()
  {
    var_dump($this->openPlatform->pre_auth->getCode());
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
