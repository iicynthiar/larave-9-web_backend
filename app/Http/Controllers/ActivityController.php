<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photos;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ActivityController extends Controller
{
    //
    public function index(Request $request)
    {
        $params = $request->all();
        //return "67867";

        //查询数据
        $query = Activity::query()->orderBy('id', 'desc');
        
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

        $photos = Photos::all();
        $photoCount = $photos->count();
        // $activity_photo = DB::table('activity')
        //                 ->leftJoin('photos','activity.id', '=', 'photos.activity_id')
        //                 ->select('activity.*','photos.id','photos.activity_id','photos.name','photos.size')
        //                 ->get();
        //$AAAA = Photos::query()->select('img_path')->where('activity_id', 18)->get();        
        //return view('/manageActivity/manageActivity', compact('photos','activities','activity_photo','AAAA'));
        //return $photoCount;
        return view('/manageActivity/manageActivity', ['paginator' => $paginator, 'params' => $params, 'photos' => $photos, 'photoCount' => $photoCount ]);
    }
    public function create()
    {
        $photos = Photos::all();
        return view('activityUpload', compact('photos'));
    }

    /**
     * 查询信息
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function info($id)
    {
        //查询数据
        return Activity::query()->find($id);
    }

    public function deleteActivity($id)
    {
        //获取参数
        //return "获取参数" . $id;
        //$id = $request->post('id');
        $photos = Photos::query()->where('activity_id', $id)->get();
        
        foreach($photos as $photo)
        {
            if (is_file(public_path('images/activityImg/'.$photo->name))) {
                
                //return response()->json(['error' => "获取参数" . $photo->name]);

                unlink(public_path('images/activityImg/'.$photo->name));
            }
            $photo->delete();
        }
        
        //查询数据
        $item = Activity::query()->find($id);
        //删除数据
        $item->delete();

        return response()->json(['error' => 0]);
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
        $photos = Photos::query()->where('activity_id', $id)->get();
        
        foreach($photos as $photo)
        {
            // if(Storage::exists('public/images/activityImg/'. $photo->name)){
            //     Storage::delete('public/images/activityImg/'. $photo->name);
            //     /*
            //         Delete Multiple File like this way
            //         Storage::delete(['upload/test.png', 'upload/test2.png']);
            //     */
            // }else{
            //     dd('File does not exists.');
            // }
            if (is_file(public_path('images/activityImg/'.$photo->name))) {
                unlink(public_path('images/activityImg/'.$photo->name));
            }
            $photo->delete();
        }
        
        //查询数据
        $item = Activity::query()->find($id);
        //删除数据
        $item->delete();

        return response()->json(['error' => 0]);
    }

    public function deleteImg($id)
    {
        //return $id;

        $photo = Photos::query()->find($id);

        //删除里面存在的图片
        // $QQ = is_file(public_path('images/activityImg/'.$photo->name));
        // return response()->json(['error' => $QQ]);

        if (is_file(public_path('images/activityImg/'.$photo->name))) {
            unlink(public_path('images/activityImg/'.$photo->name));
        }        
        //資料庫删除
        $photo->delete();
        return response()->json(['error' => 0]);

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
            $item = Activity::query()->find($params['id']);
        } else {
            $item = new Activity();
        }
        //保存数据
        $item->subject = $params['subject'];
        $item->date = $params['date'];
        $item->type_name = $params['type_name'];
        $item->desc = $params['desc'];
        $item->created_at = date('Y-m-d H:i:s');
        if ($item->save()) {
            return response()->json(['error' => 0]);
        }

        return response()->json(['error' => 1]);
    }
    
    public function storeMultiple(Request $request)//多筆上傳圖
    {
        ////----------------新增活動資料---------
        //获取参数
        $params = $request->post();
        //return response()->json(['error' => $params]);
        //return $request->hasfile('filenames');
        //判断是否新增或删除
        if (isset($params['id']) && $params['id'] > 0) {
            $Activity = Activity::query()->find($params['id']);
            $ActivityID = $Activity -> id;
        } else {
            $Activity = new Activity();
        }
        //echo $params['id'];
        //return "";
        //$Activity = new Activity();
        $Activity -> subject = $request -> subject;
        $Activity -> date = $request -> activity_date;
        $Activity -> type_name = $request -> type_name;
        $Activity -> desc = $request -> desc;
        
        $Activity -> users_id = 1;
        $Activity -> is_deleted = false;
        $Activity->created_at=date('Y-m-d H:i:s');
        $Activity->save();

        //echo Activity::all();
        if (isset($params['id']) == false) {
            $ActivityID = Activity::latest()->first() -> id;
            //return response()->json(['error' => $ActivityID]);
        }
        //echo $ActivityID;

        ////----------------多筆上傳圖---------
        //echo $request->hasfile('filenames'); //回傳1表示有檔案
        //dd($request->file('filenames')->extension());

        $files = [];
        $sizes = [];
        $i=0;
        if($request->hasfile('filenames'))
         {
            foreach($request->file('filenames') as $file)
            {
                //dd($file->getClientOriginalExtension());
                $i++;
                //获取文件相关参数
                $originName = $file->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                //保存图片
                $random_chars = md5( uniqid( rand() ) ); // 產生唯一亂數
                $random_num = substr( $random_chars,0,12 ); // 只取頭十二個字串
                //$name = $fileName.'_'.time().'.'.$extension;
                
                //$name = 'activity_'.$random_num.'_'.time().'.'.$extension;

                //$name = $file->getClientOriginalName().'_'.time();
                $size = $file->getSize();
                //return $img_path;
                //$file = Image::make(public_path('images/activityImg/'). $name)->resize(300, 200);
                
                //$file->storeAs('images/activityImg', $name);
                $fileFormat = $file->getClientOriginalExtension();
                if($fileFormat == "jpeg" || $fileFormat == "jpg" || $fileFormat == "png" || $fileFormat == "gif" ){
                    // if ($size > 131072) { // 1048576 = 1024 * 1024 -> 1mb
                    //     return redirect()->back()->with('error', '圖檔太大，檔案超過1mb，無法上傳');
                    // }

                    $percent = 100;  #原图压缩，不缩放
                    if (1048576 < $size && $size <= 1572864) { // 1048576 = 1024 * 1024
                        $percent = 70;
                    } else if (1572864 < $size) { // 1572864 = 1024 * 1024 * 1.5
                        $percent = 50;
                    }

                    //压缩图片
                    $image = (string)Image::make($file)->encode(null, $percent);
                    //保存图片
                    //$fileName = $fileName.'_'.time().'.'.$extension;
                    $fileName = 'activity_'.$random_num.'_'.time().'.'.$extension;
                    file_put_contents(public_path('images/activityImg') . DIRECTORY_SEPARATOR . $fileName, $image);
                    //拼接返回值
                    $url = asset('images/activityImg/' . $fileName);
                    $img_path = 'images/activityImg/'.$fileName;

                    // $file->move(public_path('images/activityImg'), $name);
                    // $file = Image::make(public_path('images/activityImg/'). $name)->resize(800, null, function ($constraint) {
                    //     $constraint->aspectRatio();
                    // });
                    // $file->save(public_path('images/activityImg'. $name), 60);
                }

                // ///////
                // $img = Image::make($img_path)->resize(300, null, function ($constraint) {
                //     $constraint->aspectRatio();
                // });
                //     return $img->response('jpg');
                // ///////

                $photos = new photos();
                $photos -> name = $fileName;
                $photos -> img_path = $img_path;
                $photos -> size = $size;
                $photos -> activity_id = $ActivityID;
                
                $photos -> users_id = 1;
                $photos -> is_deleted = false;
                //$photos -> img_type = $img_type;

                $photos->created_at=date('Y-m-d H:i:s');
                //echo $photos;
                //dd($request->file());
                // public function save(array $options = array()) {
                //     if(isset($this->remember_token))
                //         unset($this->remember_token);
                
                //     return parent::save($options);
                // }
                $photos->save();

            }
         }
        return redirect()->back()->with('success', '資料儲存成功');

        // return $imgType;
        // return $size;
        // return $name;
        //dd($request->file());
        //dd($request);
    }
}
