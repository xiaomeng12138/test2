<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\IndexUserModel;
class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sessionid=Session::getId();
        if(empty($sessionid)){
            //为空 没有登录
            return redirect('/login')->withErrors('请先去登陆');die;
        }
        $user_id=session('user_id');
        $info=IndexUserModel::where('user_id',$user_id)->first();
        if($sessionid!=$info['sessionid']){
            session(['user_id'=>null]);
            return redirect('/login')->withErrors('您的账号已经在其他地方登录,如不是本人操作,请及时修改密码,或者联系管理员');die;
        }

        $time=$info['start_time'];
        if(time() -($time+1200)>=0){
            session(['user_id'=>null]);
            return redirect('/login')->withErrors('20分钟没有操作请重新登录');die;
        }else{
            IndexUserModel::where('user_id',$info['user_id'])->update(
                [
                    'start_time'=>time()+1200
                ]
            );
        }

        return $next($request);
    }

}
