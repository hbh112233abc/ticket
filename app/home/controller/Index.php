<?php

declare(strict_types=1);

namespace app\home\controller;

class Index
{
    public function index()
    {
        dump(url('home/tick/mine', [], false, true)->build());
    }
}
