<?php

namespace app\home\controller;

use EasyWeChat\Factory;

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

        $response = $this->wx->server->serve();
        // 将响应输出
        return $response->send();
    }
}
