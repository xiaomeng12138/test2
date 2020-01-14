<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IndexUserModel;
use Session;
class UserController extends Controller
{
    function index(){
        return view('login.index');
    }

    function login_do(){
        $user_name=request()->input('user_name');
        $password=request()->input('password');
        if(empty($user_name) || empty($password) ){
            return back()->withErrors(['请输入账号密码']);die;
        }
        //根据用户名称进行查询账号是否存在
        $info=IndexUserModel::where('user_name',$user_name)->first();
        if(!empty($info)){
            //账号存在
            if($password==$info['password']){
                //密码正确
                if (!empty($info['finish_time'])  && time()-$info['finish_time']<=0){
                    //密码已经错误三次  锁定时间还没有过去
                    return back()->withErrors(['账号已经锁定']);die;
                }
                session(['user_id'=>$info['user_id']]);
                $sessionid=session::getId();
                IndexUserModel::where('user_id',$info['user_id'])->update(
                    [
                        'error_num'  =>0,
                        'finish_time'=>0,
                        'start_time'=>time(),
                        'sessionid' =>$sessionid
                    ]
                );
                return redirect('/list');die;
            }else{
                //密码错误 累加错误次数
                if($info['error_num']==3 && time()-$info['finish_time']<=0){
                    IndexUserModel::where('user_id',$info['user_id'])->update(
                        [
                            'finish_time'=>time()+600
                        ]
                    );

                }
                if (!empty($info['finish_time'])  && time()-$info['finish_time']<=0){
                    //密码错误三次
                    return back()->withErrors(['账号已经锁定']);die;
                }else{
                    IndexUserModel::where('user_id',$info['user_id'])->update(
                        [
                            'error_num'  =>0
                        ]
                    );
                }
                $error_num=$info['error_num']+1;

                //密码已经错误三次
                if($error_num>=3){
                    IndexUserModel::where('user_id',$info['user_id'])->update(
                        [
                            'finish_time'=>time()+600
                        ]
                    );
                    return back()->withErrors(['账号已经锁定']);die;
                }

                IndexUserModel::where('user_id',$info['user_id'])->update(
                    [
                        'error_num'  =>$error_num,
                        'finish_time'=>time()
                    ]
                );
                $num=3-$error_num;
                $msg='密码已经错误'.$error_num.'次'.'还有'.$num.'次机会';
                return back()->withErrors([$msg]);die;
            }
        }else{
            //账号不存在
            return back()->withErrors(['账号不存在']);die;
        }

    }

    function list(){
        $user_id=session('user_id');
        $info=IndexUserModel::where('user_id',$user_id)->first();
        if(empty($info)){
            //用户没有登录
            return  redirect('/login');die;
        }else if(time()-$info['start_time']>=1200){
            //判断用户20分钟操作没有
            session(['user_id'=>null]);
            echo '20分钟没有操作,请重新登录';die;
        }else{
            //成功
            return view('login.list',['info'=>$info]);
        }
    }

    function quit(){
        session(['user_id'=>null]);
        Session::getId(null);
        return redirect('/login');die;
    }
}
