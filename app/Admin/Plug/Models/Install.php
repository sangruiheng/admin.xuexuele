<?php

namespace App\Admin\Plug\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Install extends Model
{
    //安装菜单
    public function menu(array $menu,array $menuPermission){
        $menuAdd = $menu;
        $menuId = DB::table('admin_menus')->insertGetId($menuAdd);
        $roleAdd = array(
            'menu_id' => $menuId,
            'action_ids' => $menuPermission['action_ids'],
            'role_id'=> $menuPermission['role_id'],
        );
        $roleId = DB::table('admin_permissions')->insertGetId($roleAdd);
        return $roleId;
    }
}
