<?php

declare(strict_types=1);

namespace app\home\controller;

class Index
{
    //http://www.tk.com/home/index/index
    public function index()
    {
        // dump(config('wx'));
        // dump(session('wechat_user'));
        // dump(session('target_url'));
        // session('aa', 11);
        // dump(session('aa'));
        return view();
    }

    public function sms($mobile = '18759201163')
    {
        $sms = new \bingher\sms\AliSms(config('ali_sms'));
        $res = $sms->code($mobile, ['code' => 1234]);
        dump($res);
        dump($sms->getError());
    }
}
