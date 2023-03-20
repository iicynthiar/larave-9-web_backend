<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(['auth','verified']);

    }
    public function webInfo(Request $request)
    {
        $params = $request->all();
        // if($params){
        //     return $params;
        // }
        //查询数据
        $query = News::query()->orderBy('id', 'desc');
        if (isset($params['year']) && !empty($params['year'])) {
            $query->where('year', $params['year']);
        }
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $query->where(function ($query) use ($params) {
                $query->orWhere('subject', 'like', "%{$params['keyword']}%");
                $query->orWhere('desc', 'like', "%{$params['keyword']}%");
            });
        }
        if (isset($params['start_time']) && !empty($params['start_time'])) {
            $query->where('date', '>=', $params['start_time']);
        }
        if (isset($params['end_time']) && !empty($params['end_time'])) {
            $query->where('date', '<=', $params['end_time']);
        }

        $paginator = $query->paginate(10);

        //查询年度
        $years = News::query()->groupBy('year')->orderBy('year','desc')->get(['year'])->toArray();
        $years = array_column($years, 'year');

        return view('news', ['paginator' => $paginator, 'params' => $params, 'years' => $years]);
    }
    /**
     * 展示列表数据
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $params = $request->all();

        //查询数据
        $query = News::query()->orderBy('id', 'desc');
        if (isset($params['year']) && !empty($params['year'])) {
            $query->where('year', $params['year']);
        }
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $query->where(function ($query) use ($params) {
                $query->orWhere('subject', 'like', "%{$params['keyword']}%");
                $query->orWhere('desc', 'like', "%{$params['keyword']}%");
            });
        }
        if (isset($params['start_time']) && !empty($params['start_time'])) {
            $query->where('date', '>=', $params['start_time']);
        }
        if (isset($params['end_time']) && !empty($params['end_time'])) {
            $query->where('date', '<=', $params['end_time']);
        }

        $paginator = $query->paginate(10);
        $params['dialog'] = '';
        $message_sent = '您的訊息已經成功寄出';
        if($params['dialog'] == 'Add'){
            $message_sent = 'Add';
        }

        //查询年度
        $years = News::query()->groupBy('year')->orderBy('year')->get(['year'])->toArray();
        $years = array_column($years, 'year');
        //return $message_sent;
        return view('/manageNews/manageNews', ['paginator' => $paginator, 'params' => $params, 'years' => $years, 'message_sent' => $message_sent ]);
    }

    public function openModel($id)
    {
        //return $id;
        return response()->json(['error' => $id]);
    }
    /**
     * 查询信息
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function info($id)
    {
        //查询数据
        return News::query()->find($id);
    }

    /**
     * 更新数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        //获取参数
        $params = $request->post();
        //判断是否新增或删除
        if (isset($params['id']) && $params['id'] > 0) {
            $item = News::query()->find($params['id']);
        } else {
            $item = new News();
        }
        //保存数据
        $item->subject = $params['subject'];
        $item->year = date('Y', strtotime($params['date']));
        $item->date = $params['date'];
        $item->type_name = $params['type_name'];
        $item->desc = $params['editor'];
        $item->created_at = date('Y-m-d H:i:s');
        if ($item->save()) {
            return response()->json(['error' => 0]);
        }

        return response()->json(['error' => 1]);
    }

    /**
     * 删除消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        //获取参数
        $id = $request->post('id');
        //查询数据
        $item = News::query()->find($id);
        //删除里面存在的图片
        if (isset($item->desc) && !empty($item->desc)) {
            $preg = '/images\/uploads\/.*?\.(jpg|jpeg|bmp|png|gif|webp)/';
            preg_match_all($preg, $item->desc, $res);
            foreach ($res[0] as $photo) {
                if (is_file(public_path($photo))) {
                    unlink(public_path($photo));
                }
            }
        }
        //删除数据
        $item->delete();

        return response()->json(['error' => 0]);
    }

    /**
     * 上传图片处理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            //获取文件相关参数
            $size = $request->file('upload')->getSize();
            $percent = 100;  #原图压缩，不缩放
            if (1048576 < $size && $size <= 1572864) { // 1048576 = 1024 * 1024
                $percent = 70;
            } else if (1572864 < $size) { // 1572864 = 1024 * 1024 * 1.5
                $percent = 50;
            }
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            //压缩图片
            $image = (string)Image::make($request->file('upload'))->encode(null, $percent);
            //保存图片
            $fileName = $fileName.'_'.time().'.'.$extension;
            file_put_contents(public_path('images/uploads') . DIRECTORY_SEPARATOR . $fileName, $image);
            //拼接返回值
            $url = asset('images/uploads/' . $fileName);
            return response()->json(['error' => 0, 'url' => $url]);
        }
        return response()->json(['error' => 1, 'url' => '']);
    }

    public function store(Request $request){
        dd($request->all());
    }
    public function uploadimage(Request $request){
        if($request->hasFile('upload')){
            $originName = $request->file('upload')->getClientOriginName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('media'), $fileName);
            $url = asset('media/' . $fileName);

            return response()->json(['fileName' => $fileName, 'upload' => 1, 'url' => $url]);
        }
    }
}
