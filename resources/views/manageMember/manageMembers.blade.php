<x-app-layout>
    <div>
        <div class="d-flex p-2">
            <h5 class="mr-auto text-primary">會員管理</h5>
            <!-- <a type="button" class="btn btn-info btn-sm" href="/register">
            <i class="material-icons icons">&#xe147;</i>
            新增
            </a> -->
        </div>
        <div class="searchGrp">
            <form id="search" class="form-inline">
                <input class="form-control form-control-sm ml-2" name="keyword" type="text" placeholder="姓名、Email查詢">
                <input class="form-control form-control-sm ml-2" type="datetime-local" name="start_time">
                <span class="ml-2">-</span>
                <input class="form-control form-control-sm ml-2" type="datetime-local" name="end_time">
                <button type="button" class="search btn btn-outline-info btn-sm ml-2 searchBtn"><i class="material-icons icons">search</i>查詢確認</button>
            </form>
        </div>
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">名稱</th>
                    <th scope="col">Email</th>
                    <th scope="col">群組</th>
                    <th scope="col">更新日期</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($groups as $group)
            {{ $group }}
            @endforeach
                <?php $no=1 ?>
                @foreach($users as $user)
                <tr>
                    <td><?php print $no++ ?></td>
                    <td>{{ $user -> name }}</td>
                    <td>{{ $user -> email }}</td>
                    <td>{{ $user -> authority }}</td>
                    <td>
                    {{ $user -> updated_at == '' ? $user -> created_at : $user -> updated_at}}
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm edit" onclick="openEditModel({{$user}})"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-authority="{{ $user->authority }}"
                        data-email="{{ $user->email }}"
                        ><i class="material-icons icons">&#xe254;</i>修改</button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="delItem({{ $user->id }})"><i class="material-icons icons">&#xe5c9;</i>刪除</button>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if(!$users->onFirstPage())
                <li class="page-item">
                <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
                </li>
                @endif
                @for ($i = $users->currentPage() - 2; $i <= $users->currentPage() + 2; $i++)
                    @if($i > 0 && $i <= $users->lastPage())
                    <li class="page-item"><a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($users->lastPage() > $users->currentPage())
                <li class="page-item">
                <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
                </li>
                @endif
            </ul>
        </nav>    
        <div class="ckeditorModel">
            
            <div class="ckeditorModelDialog">
                <form id="add_edit" method="post" action="" enctype="multipart/form-data">
                @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">修改-會員</h5>
                        <button type="button" class="close closeModel" onclick="closeEditModel()">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="type_name">名稱</label>
                            <input type="text" name="name" class="form-control" placeholder="填寫名稱">
                        </div>
                        <div class="form-group">
                            <label for="type_name">權限群組</label>  
                            <input type="hidden" name="authority" value="authority">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" onclick="radioAuthorityChange(this)" name="inlineRadioOptions" id="radioAdmin" value="admin">
                                <label class="form-check-label">admin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" onclick="radioAuthorityChange(this)" name="inlineRadioOptions" id="radioUser" value="user">
                                <label class="form-check-label">user</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" onclick="radioAuthorityChange(this)" name="inlineRadioOptions" id="radioManager" value="manager">
                                <label class="form-check-label">manager</label>
                            </div>                                             
                            <!-- <select id="authority" class="form-control">
                                <option value="admin">admin</option>
                                <option value="user">user</option>
                                <option value="manager">manager</option>
                            </select> -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info btn-sm edit" onclick="closeEditModel()"><i class="material-icons icons">close</i>清除關閉</button>
                        <button type="submit" class="btn btn-outline-info btn-sm"><i class="material-icons icons">check</i>確認儲存</button>
                    </div>
                </form>
            </div>
        </div>  
    </div>
    <script type="text/javascript">
        //document.getElementById("manageMembers").style.color = "#d66790";
        //var radioAuthority="";
        $('input[name="keyword"]').val("{{ $params['keyword'] ?? '' }}");
        $('input[name="start_time"]').val("{{ $params['start_time'] ?? '' }}");
        $('input[name="end_time"]').val("{{ $params['end_time'] ?? '' }}");
        
        $('.search').on('click', function () {
            console.log($('#search'));
            $('#search').submit();
        });

        
        function closeEditModel(){
            $('.ckeditorModel').hide();
        }
        function radioAuthorityChange(thisValue){
            $('input[name="authority"]').val($(thisValue).attr('value'));
            //radioAuthority = $(thisValue).attr('value');
            //console.log("value--",$('input[name="authority"]').val());
            // console.log(radioAuthority)
            // console.log($(thisValue).attr('value'))
        }

        function openEditModel(item){
            $('.ckeditorModel').css({"display":"flex"});
            $('.modal-title').html('修改-會員');

            //console.log(item)
            $('input[name="id"]').val(item.id);
            $('input[name="name"]').val(item.name);
            //$('input[name="authority"]').val(item.authority);
            $('input[name="email"]').val(item.email);

            if(item.authority == $("#radioAdmin").val()){
                $("#radioAdmin").attr("checked",true);
            }else if(item.authority == $("#radioUser").val()){
                $("#radioUser").attr("checked",true);

            }else if(item.authority == $("#radioManager").val()){
                $("#radioManager").attr("checked",true);
            }else{
                //
            }

            // const inlineRadioOptions = document.querySelectorAll('input[name="inlineRadioOptions"]');
            // console.log("inlineRadioOptions-",inlineRadioOptions)
            // inlineRadioOptions.addEventListener('change', function (e) {
            //     if (this.checked) {
            //         console.log(this.value);
            //     }
            // });

        }
        // $('.edit').on('click', function (item) {
        //     console.log(item)
        //     console.log($(item.target).attr('data-authority'));
        //     //console.log("radio",$("#radioAdmin").val());
        //     $('.modal-title').html('修改-會員');
        //     $('input[name="id"]').val($(item.target).attr('data-id'));
        //     $('input[name="name"]').val($(item.target).attr('data-name'));
        //     $('input[name="authority"]').val($(item.target).attr('data-authority'));
        //     $('input[name="email"]').val($(item.target).attr('data-email'));
        //     if($(item.target).attr('data-authority') == $("#radioAdmin").val()){
        //         $("#radioAdmin").attr("checked",true);
        //     }else if($(item.target).attr('data-authority') == $("#radioUser").val()){
        //         $("#radioUser").attr("checked",true);

        //     }else if($(item.target).attr('data-authority') == $("#radioManager").val()){
        //         $("#radioManager").attr("checked",true);

        //     }
            
        //     //window.desc.setData($(item.target).attr('data-desc'));
        // })
        
	    

        $("#add_edit").on("submit",function(event){
            if (!$('input[name="name"]').val()) {
                alert('會員名稱不能為空')
                return false;
            }if (!$('input[name="authority"]').val()) {
                alert('權限群組不能為空')
                return false;
            }
            //console.log($('input[name="authority"]').val());
            $.ajax({
                type:"post",
                url:"/manageMember/manageMembers",
                data:{
                    'id': $('input[name="id"]').val(),
                    'name': $('input[name="name"]').val(),
                    'authority': $('input[name="authority"]').val(),
                },
                dataType:"json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (result) {
                    if (result != null && result.error == 0) {
                        console.log(result);
                        alert("儲存成功！");
                        location.reload();
                    } else {
                        console.log(result);
                        alert("儲存失敗！");
                    }
                },
                error: function(data){
                    alert("儲存失敗！"+data);
                }
            });
            return false;
        })

        // $("#add_edit").on("submit",function(event){
        //     if (!$('input[name="name"]').val()) {
        //         alert('會員名稱不能為空')
        //         return false;
        //     }
        //     $.ajax({
        //         type:"post",
        //         url:"/manageMembers",
        //         data:{
        //             'id': $('input[name="id"]').val(),
        //             'name': $('input[name="name"]').val(),
        //         },
        //         dataType:"json",
        //         headers: {
        //             'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //         },
        //         success: function (result) {
        //             if (result != null && result.error == 0) {
        //                 console.log(result);
        //                 alert("存储成功！");
        //                 location.reload();
        //             } else {
        //                 console.log(result);
        //                 alert("存储失败！");
        //             }
        //         },
        //         error: function(data){
        //             alert("存储失败！"+data);
        //         }
        //     });
        //     return false;
        // })

        function delItem(id) {
            //alert(id)
            if(confirm("確認删除嗎?" + id)){
                //window.location.href='/deleteActivity/'+id
                $.ajax({
                    type:"get",
                    url:"/manageMember/deleteMembers/"+id,
                    data:{"id": id},
                    dataType:"json",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (result) {
                        console.log(result)
                        if (result != null && result.error == 0) {
                            alert("删除成功！");
                            location.reload();
                        } else {

                            alert("删除失敗！");
                        }
                    }
                });
            }
        }
    </script>

    
</x-app-layout>