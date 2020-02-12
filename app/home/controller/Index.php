<?php

declare(strict_types=1);

namespace app\home\controller;

class Index
{
    //http://www.tk.com/home/index/index
    public function index()
    {
        dump(config('wx'));
        dump(url('home/wx/callback', [], false, true)->build());
    }
}
