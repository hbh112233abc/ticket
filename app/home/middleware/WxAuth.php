<?php

declare(strict_types=1);

namespace app\home\middleware;

use EasyWeChat\Factory;

class WxAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $app = Factory::officialAccount(config('wx'));
        $oauth = $app->oauth;
        if (empty(session('wechat_user'))) {
            session('target_url', $request->url(true));
            return redirect($oauth->redirect()->getTargetUrl());
        }
        session('js_sdk', $app->jssdk->buildConfig([
            'updateAppMessageShareData',
            'updateTimelineShareData',
            'scanQRCode',
            'getLocation',
            'openLocation',
            'chooseImage',
            'uploadImage',
        ], true));
        return $next($request);
    }
}
