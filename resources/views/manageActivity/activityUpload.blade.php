
<html lang="en">
<head>
  <title>Laravel 8 Multiple Image Upload Example - ItSolutionStuff.com</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="modal-content">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Sorry!</strong> There were more problems with your HTML input.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    
    @if(session('success'))
    <div class="alert alert-success">
    {{ session('success') }}
    </div> 
    @endif
    <form id="form" method="post" action="/manageActivity/activityUpload" enctype="multipart/form-data">
    @csrf
    
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">新增/修改-活動</h5>
          <button type="button" class="close closeModel" onclick="closeModel()">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="">
        <div class="form-group">
            <!-- <label for="type_name">活動名稱</label> -->
            <input type="text" name="subject" class="form-control" value="" placeholder="活動名稱">
        </div>
        <div class="form-group">
            <!-- <label for="type_name">類別</label> -->
            <select class="form-control" name="type_name" value="旅遊" placeholder="選擇類別">
                <option value="旅遊">旅遊</option>
                <option value="活動">活動</option>
                <option value="進修">進修</option>
            </select>
        </div>
        <div class="form-group">
            <!-- <label for="type_name">活動日期</label> -->
            <input type="date" name="activity_date" class="form-control" placeholder="活動日期">
        </div>
        <div class="form-group">
            <!-- <label for="type_name">活動內容</label> -->
            <textarea id="desc" name="desc" class="form-control" placeholder="活動內容">
            </textarea>
        </div>
        <div class="form-group">
            <label for="type_name">上傳活動照</label>，<span>上傳格式：JPG/JPEG/PNG/GIF</span>
            <div class="input-group hdtuto control-group lst increment" >
              <input id="img_file" type="file" name="filenames[]" class="myfrm form-control">
              <div class="input-group-btn"> 
                <button class="btn btn-success" type="button">Add</button>
              </div>
            </div>
            <div class="clone hide">
              <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                <input type="file" name="filenames[]" class="myfrm form-control">
                <div class="input-group-btn"> 
                  <button class="btn btn-danger" type="button">Remove</button>
                </div>
              </div>
            </div>
        </div>
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary closeModel" onclick="closeModel()">清除關閉</button>
          <button type="submit" class="btn btn-info">確認儲存</button>
      </div>

    </form>
    <!-- <hr/>
    <ul>
        @foreach($photos as $photo)
            <li>
                {{asset('images/activityImg/'. $photo->name)}}
                <img src="{{ asset('images/activityImg/'. $photo->name)}}" alt="" style="width:100px;">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="delImg({{ $photo->id }})">刪除{{ $photo->id }}</button>
                <a type="button" class="btn btn-outline-info btn-sm" href={{"deleteImg/". $photo['id'] }}>刪除{{ $photo->id }}</a>
            </li>
        @endforeach
    </ul> -->
</div>
  
<script type="text/javascript">
    $(document).ready(function() {
    $(".btn-success").click(function(){ 
        var lsthmtl = $(".clone").html();
        $(".increment").after(lsthmtl);
    });
    $("body").on("click",".btn-danger",function(){ 
        //console.log($(".btn-danger").length)
        var cnt = $(".btn-danger").length
        if(cnt > 1){
          $(this).parents(".hdtuto").remove();
        }
      });
    });
    function delImg(id) {
        if(confirm("確認刪除嗎?")){          
          //window.location.href='/deleteImg/'+id
            $.ajax({
                type:"get",
                url:"/deleteImg/"+id,
                data:{"id": id},
                dataType:"json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (result) {
                    if (result != null && result.error == 0) {
                        alert("數據刪除成功！");
                        location.reload();
                        //$(".modal").addClass(" show").css({"display":"block;"})
                    } else {

                        alert("數據刪除失敗");
                    }
                }
            });
        }
    }
    // $("#submit").click(function() {
    //   alert("test");
    //   if (!$('input[name="subject"]').val()) {
    //       alert('活動名稱不能為空')
    //       return false;
    //   }
    //   $("#submit").submit();
    // });
    $("#form").on("submit",function(event){
        if (!$('input[name="id"]').val()) {            
            var file = $('#img_file')[0].files[0]
            if (file){
                //console.log(file.name);
            }else{
                alert('尚未上傳圖片')
                return false;
            }
        }
        if (!$('input[name="subject"]').val()) {
            alert('活動名稱不能為空')
            return false;
        }
        $("form").submit();
        //event.preventDefault();
    })
</script>
    
</body>
</html>