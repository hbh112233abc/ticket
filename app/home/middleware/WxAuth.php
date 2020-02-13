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
            // 未登录
            session('target_url', $request->url(true));

            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            return $oauth->redirect()->send();
        }
        $request->jssdk = $app->jssdk->buildConfig(['updateAppMessageShareData', 'updateTimelineShareData'], env('APP_DEBUG', true));
        return $next($request);
    }
}
