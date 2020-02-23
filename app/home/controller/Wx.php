<?php

namespace app\home\controller;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Transfer;

class Wx extends \app\BaseController
{
    protected $wx;

    protected function initialize()
    {
        $config = config('wx');
        $this->wx = Factory::officialAccount($config);
    }

    public function index()
    {
        $this->wx->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                    // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });

        // 转发收到的消息给客服
        $this->wx->server->push(function ($message) {
            return new Transfer();
        });

        $response = $this->wx->server->serve();
        // 将响应输出
        return $response->send();
    }

    /**
     * 设置菜单
     *
     * @return void
     */
    public function menu()
    {
        $current = $this->wx->menu->current();
        dump($current);
        // die;
        //管理员
        $buttons = [
            [
                "type" => "view",
                "name" => "我的小区",
                "url" => url('home/plot/index', [], false, true)->build(),
            ],
            [
                "name"       => "通行证",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我的通行证",
                        "url"  => url('home/tick/index', [], false, true)->build(),
                    ],
                    [
                        "type" => "view",
                        "name" => "访客通行证",
                        "url"  => url('home/tick/guest', [], false, true)->build(),
                    ],
                    [
                        "type" => "view",
                        "name" => "通行记录",
                        "url" => url('home/tick/record', [], false, true)->build(),
                    ],
                ],
            ],
        ];
        $res = $this->wx->menu->create($buttons);
        dump($res);
    }


    /**
     * 获取用户信息的回调
     *
     * @return void
     */
    public function callback()
    {
        $oauth = $this->wx->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        session('wechat_user', $user->toArray());

        $targetUrl = empty(session('target_url')) ? '/' : session('target_url');

        return redirect($targetUrl);
    }
}
