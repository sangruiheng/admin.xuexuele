<?php

namespace App\Admin\Financial\Controllers;

use App\Admin\Financial\Models\Financial;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;
use Excel;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->financial = new Financial();
        $this->formCheck = new FormCheck();
    }
    /***
     * 智慧豆管理
     * @param Request $request
     */
    public function smartbean(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Financial.Views.smartbean")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /***
     * 充值管理
     * @param Request $request
     */
    public function recharge(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Financial.Views.recharge")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /***
     * 订单管理
     * @param Request $request
     */
    public function orders(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Financial.Views.orders")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /***
     * 提现管理
     * @param Request $request
     */
    public function putforward(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Financial.Views.putforward")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 充值管理
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getRechargeLists(Request $request){
        $abnormal = new Financial();
        $result = $abnormal->getRechargeLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    
    /**
     * 订单管理
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getOrdersLists(Request $request){
        $abnormal = new Financial();
        $result = $abnormal->getOrdersLists($request);
        return result($result->msg, $result->code, $result->data);

    }

   /* public function export(Request $request){
        $result = $abnormal->getRechargeLists($request);
        Excel::create('充值管理',function($excel) use ($result){
            $excel->sheet('score', function($sheet) use ($result){
                $sheet->rows($result);
            });
        })->export('xls');
    }*/

    public function rechargeexport(Request $request)
    { //return Excel::download(new CunliangExport, 'invoices.xlsx'); 
        // $data = Monitor::get()->toArray(); 
        $abnormal = new Financial();
        $data = $abnormal->getRechargeListsExcel($request);

        return Excel::create('充值记录', function($excel) use ($data) { 
            $excel->sheet('充值记录', function($sheet) use ($data)
            { 
                $sheet->cell('A1', function($cell) {$cell->setValue('ID'); }); 
                $sheet->cell('B1', function($cell) {$cell->setValue('充值时间'); }); 
                $sheet->cell('C1', function($cell) {$cell->setValue('充值方式'); }); 
                $sheet->cell('D1', function($cell) {$cell->setValue('充值用户'); }); 
                $sheet->cell('E1', function($cell) {$cell->setValue('手机号'); }); 
                $sheet->cell('F1', function($cell) {$cell->setValue('充值金额'); }); 
                if (!empty($data)) { 
                    foreach ($data as $key => $value) { 
                        $i= $key+2; 
                        $complaint='';
                        if($value->complaint==1){
                            $complaint='微信';
                        }
                        if($value->complaint==2){
                            $complaint='支付宝';
                        }
                        $sheet->cell('A'.$i, $value->id); 
                        $sheet->cell('B'.$i, $value->create_time); 
                        $sheet->cell('C'.$i, $complaint); 
                        $sheet->cell('D'.$i, $value->nickname); 
                        $sheet->cell('E'.$i, $value->phone); 
                        $sheet->cell('F'.$i, $value->wisdombean); 
                    } 
                } 
            }); 
        })->download('xlsx');
    }


    public function orderexport(Request $request)
    { //return Excel::download(new CunliangExport, 'invoices.xlsx'); 
        // $data = Monitor::get()->toArray(); 
        $abnormal = new Financial();
        $data = $abnormal->getOrdersListsExcel($request);

        return Excel::create('订单记录', function($excel) use ($data) { 
            $excel->sheet('订单记录', function($sheet) use ($data)
            { 
                $sheet->cell('A1', function($cell) {$cell->setValue('ID'); }); 
                $sheet->cell('B1', function($cell) {$cell->setValue('订单时间'); }); 
                $sheet->cell('C1', function($cell) {$cell->setValue('购买用户'); }); 
                $sheet->cell('D1', function($cell) {$cell->setValue('电话号码'); }); 
                $sheet->cell('E1', function($cell) {$cell->setValue('专辑名称'); }); 
                $sheet->cell('F1', function($cell) {$cell->setValue('购买课时量'); }); 
                $sheet->cell('G1', function($cell) {$cell->setValue('所属导师'); }); 
                $sheet->cell('H1', function($cell) {$cell->setValue('订单金额'); }); 
                $sheet->cell('I1', function($cell) {$cell->setValue('打赏平台'); }); 
                if (!empty($data)) { 
                    foreach ($data as $key => $value) { 
                        $i= $key+2; 
                        
                        $sheet->cell('A'.$i, $value->id); 
                        $sheet->cell('B'.$i, $value->create_time); 
                        $sheet->cell('C'.$i, $value->nickname); 
                        $sheet->cell('D'.$i, $value->phone); 
                        $sheet->cell('E'.$i, $value->albumname); 
                        $sheet->cell('F'.$i, $value->coursenum); 
                        $sheet->cell('G'.$i, $value->albumuser); 
                        $sheet->cell('H'.$i, $value->wisdombean); 
                        $sheet->cell('I'.$i, $value->rewardplatform); 
                    } 
                } 
            }); 
        })->download('xlsx');
    }


}
