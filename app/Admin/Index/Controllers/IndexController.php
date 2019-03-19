<?php

namespace App\Admin\Index\Controllers;

use App\Classlib\FormCheck;
use App\Admin\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct(){
        $this->formCheck = new FormCheck();
    }

    public function index(Request $request){
        $usercount = DB::table('s_users')->count();
        $userwisdombean = DB::table('s_users')->sum('wisdombean');

        $albumcount = DB::table('s_album')->count();
        $coursecount = DB::table('s_album_course')->count();

        $orderwisdombean = DB::table('s_order')->where('state',2)->sum('wisdombean');
        $chuangwisdombean = DB::table('s_users_wisdombeanuse')->where('type',5)->orwhere('type',8)->sum('wisdombean');
        $sendwisdombean=$orderwisdombean+$chuangwisdombean;

        $platformwisdombean = DB::table('s_users_wisdombeanuse')->sum('rewardplatform');

        if($request->usertime=='' or $request->usertime==1){
            $userbegin =Carbon::now()->subMonth(); 
        }elseif($request->usertime==2){
            $userbegin =Carbon::now()->subWeek(); 
        }elseif($request->usertime==3){
            $userbegin =Carbon::now()->subYear();
        }else{
            $userbegin =Carbon::now()->subMonth();
        } 

        $usercounttu =DB::table('s_users')->select(DB::raw('DATE_FORMAT(create_time,"%Y-%m-%d") as userday,count(id) as daynum'))->where('create_time','>=',$userbegin)->groupBy('userday')->get();
        $userday=array();
        $daynum=array();
        foreach ($usercounttu as $usertu) {
            array_push($userday,$usertu->userday);
            $userday=$userday;
            array_push($daynum,$usertu->daynum);
            $daynum=$daynum;
        }
        $userday=implode(",", $userday);
        $daynum=implode(",", $daynum);


        if($request->studytime=='' or $request->studytime==1){
            $studybegin =Carbon::now()->subMonth(); 
        }elseif($request->studytime==2){
            $studybegin =Carbon::now()->subWeek(); 
        }elseif($request->studytime==3){
            $studybegin =Carbon::now()->subYear();
        }else{
            $studybegin =Carbon::now()->subMonth();
        } 

        $studycounttu =DB::table('s_album_course_study')->select(DB::raw('DATE_FORMAT(create_time,"%Y-%m-%d") as studyday,count(id) as daynum'))->where('create_time','>=',$studybegin)->groupBy('studyday')->get();
        $studyday=array();
        $daynum2=array();
        foreach ($studycounttu as $studytu) {
            array_push($studyday,$studytu->studyday);
            $studyday=$studyday;
            array_push($daynum2,$studytu->daynum);
            $daynum2=$daynum2;
        }
        $studyday=implode(",", $studyday);
        $daynum2=implode(",", $daynum2);


        return view($this->viewPath())
                ->with("thisAction",'/')
                ->with("title",Lang('Index','home'))
                ->with("usercount",$usercount)
                ->with("userwisdombean",$userwisdombean)
                ->with("albumcount",$albumcount)
                ->with("coursecount",$coursecount)
                ->with("sendwisdombean",$sendwisdombean)
                ->with("orderwisdombean",$orderwisdombean)
                ->with("platformwisdombean",$platformwisdombean)
                ->with("userday",$userday)
                ->with("daynum",$daynum)
                ->with("usertime",$request->usertime)
                ->with("studyday",$studyday)
                ->with("daynum2",$daynum2)
                ->with("studytime",$request->studytime);

    }
    public function axis()
    {
        //$data = DB::table('s_users')->select('create_time','wisdombean');//
        //return $data;
        return ;
    }
}