<?php

namespace App\Admin\Abnormal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Abnormal extends Model
{
    protected $table = "abnormal";
    public $timestamps = false;

    public function writeAbnormal($exception,$app = ''){
        $eData = array(
            'message' => $exception->getMessage(),
            'app'    => strtolower($app),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'send_status'    => 1,
            'create_date' => date('Y-m-d H:i:s')
        );
        self::insertGetId($eData);
    }

    public function getLists($request){
        $page = isset($request->page) ? $request->page : 0;
        $pageSize = isset($request->pageSize) ? $request->pageSize : 15;
        $pageStart = ($page-1) * $pageSize;
        $query = DB::table($this->table);
        $datatime =  $request->datetime;
        //按指定时间进行查询
        if($request->datetime!=""){
            $floatStartDate = substr($datatime,0,10);
            $startDate = $floatStartDate." 00:00:00";
            $floatEndDate   = substr($datatime,-10);
            $endDate   = $floatEndDate." 00:00:00";
            //处理时间包含开始时间和结束时间
            $startDateStr = strtotime($startDate) - 1;
            $endDateStr   = strtotime($endDate) + (24*3600-1);

            $startDate = date("Y-m-d H:i:s",$startDateStr);
            $endDate   = date("Y-m-d H:i:s",$endDateStr);
            $query->whereBetween('create_date', [$startDate, $endDate]);
        }
        //按关键词进行查询
        if($request->keyword!=""){
            $keyword = $request->keyword;
            $query->where(function($query)use ($keyword){
                $query->where('message','like','%'.$keyword.'%')
                    ->orWhere('file','like','%'.$keyword.'%');
            });
        }
        $data['count'] = $query->count();
        $data['lists'] = $query->orderBy("create_date","DESC")->offset($pageStart)->limit($pageSize)->get();
        return $data;
    }

    public function call(){
//        $total = self::where('send_status',1)->count();
//        self::where('send_status',1)->update(['send_status'=>0]);
//        return $total;
    }
}
