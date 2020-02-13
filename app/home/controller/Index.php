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
}
