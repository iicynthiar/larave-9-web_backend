<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserGroup;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    //查詢使用者
    public function index(Request $request)
    {
        $params = $request->all();
        //return $params;
        //$users = Users::query()->orderBy('id', 'desc')->paginate(10);
        
        //查询数据
        $query = Users::query()->orderBy('id', 'desc');
        
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $query->where(function ($query) use ($params) {
                $query->orWhere('name', 'like', "%{$params['keyword']}%");
                $query->orWhere('email', 'like', "%{$params['keyword']}%");
            });
        }
        if (isset($params['start_time']) && !empty($params['start_time'])) {
            $query->where(function ($query) use ($params) {
                $query->orWhere('created_at', '>=', $params['start_time']);
                $query->orWhere('updated_at', '>=', $params['start_time']);
            });            
        }
        if (isset($params['end_time']) && !empty($params['end_time'])) {
            $query->where(function ($query) use ($params) {
                $query->orWhere('created_at', '<=', $params['end_time']);
                //$query->orWhere('updated_at', '<=', $params['end_time']);
            });            
        }
        // if (isset($params['start_time']) && !empty($params['start_time'])) {
        //     $query->where('created_at', '>=', $params['start_time']);
        // }
        // if (isset($params['end_time']) && !empty($params['end_time'])) {
        //     $query->where('created_at', '<=', $params['end_time']);
        // }
        $users = $query->paginate(10);
        $groups = UserGroup::query()->orderBy('id', 'desc')->get();

        return view('/manageMember/manageMembers', compact('users','params','groups'));
    }
    /**
     * 更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        //取得參數
        $params = $request->post();
        //判断是否新增或删除
        if (isset($params['id']) && $params['id'] > 0) {
            $item = Users::query()->find($params['id']);
        } else {
            $item = new Users();
        }
        //儲存
        $item->name = $params['name'];
        $item->authority = $params['authority'];
        
        $item->updated_at = date('Y-m-d H:i:s');
        if ($item->save()) {
            return response()->json(['error' => 0]);
        }

        return response()->json(['error' => 1]);
        //return response()->json(['error' => $params]);
    }
    public function deleteMembers($id)
    {
        //取得參數
        //return "取得參數" . $id;
        // $id = $request->post('id');
        // $users = Users::query()->where('id', $id)->get();
                
        //查詢
        $item = Users::query()->find($id);
        //删除ID
        $item->delete();

        return response()->json(['error' => 0]);
    }

}
