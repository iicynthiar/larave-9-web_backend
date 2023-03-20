<x-app-layout>    
    <div class="outContent">
        <div class="d-flex p-2">
            <h5 class="mr-auto text-primary">活動管理</h5>
            <button type="button" id="add" class="btn btn-info btn-sm" onclick="openModel('Add')"><i class="material-icons icons">&#xe147;</i>新增</button>
        </div>
        <div class="searchGrp">
            <form id="search" class="form-inline">
                <input class="formTxt form-control-sm ml-2 w-25" name="keyword" type="text" placeholder="標題、內文查詢">
                <input class="form-control form-control-sm ml-2" type="date" name="start_time">
                <span class="ml-2">-</span>
                <input class="form-control form-control-sm ml-2" type="date" name="end_time">
                <button type="button" class="search btn btn-outline-info btn-sm ml-2 searchBtn"><i class="material-icons icons">search</i>查詢確認</button>
            </form>
        </div>
        @if(session('error'))
        <div class="alert alert-danger alertGrp alertClose">
            <i class="material-icons" onclick="iconClose()">&#xe5c9;</i>
            <p>{{ session('error') }}</p>
        </div> 
        @endif
        @if(session('success'))
        <div class="alert alert-success alertGrp alertClose">
            <i class="material-icons" onclick="iconClose(this)">&#xe5c9;</i>
            <p>{{ session('success') }}</p>
        </div> 
        @endif
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="18%">活動標題</th>
                    <th width="12%">類別</th>
                    <th width="15%">說明</th>
                    <th width="30%">活動照</th>
                    <th width="20%">操作</th>
                </tr>
            </thead>
            <tbody>
            <?php $editActivityId=0 ?>
                <?php $no=1 ?>
                @foreach($paginator as $activity)
                <tr>
                    <td><?php print $no++ ?></td>
                    <td>{{ $activity->date }} <br/> {{ $activity->subject }}</td>
                    <td>{{ $activity -> type_name }}</td>
                    <td>{{ $activity -> desc }}</td>
                    <td>
                        <div style="display:flex; flex-wrap: wrap;width:100%;">
                            @if( $photoCount > 0 )
                            @foreach($photos as $photo)
                            <?php if($photo->activity_id == $activity->id){ ?>
                                <!-- <img src="{{ asset('storage/images/'. $photo->name)}}" alt="" style="width:50px;">&nbsp; -->
                                    <div style="text-align:center;">
                                        <div class="photo">  
                                            <img src="{{ asset('images/activityImg/'. $photo->name)}}" title="{{$photo->name}}">
                                        </div>  
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="delPhoto({{ $photo->id }})"><i class="material-icons icons">&#xe5c9;</i>刪除</button>
                                    </div>&nbsp;&nbsp;
                            <?php } ?>
                            @endforeach
                            @endif
                        </div>
                    </td>
                    <td>
                    <button type="button" class="btn btn-outline-info btn-sm edit" onclick="openModel({{$activity}})"
                        data-id="{{ $activity->id }}"
                        data-subject="{{ $activity->subject }}"
                        data-activity_date="{{ $activity->date }}"
                        data-type_name="{{ $activity->type_name }}"
                        data-desc="{{ $activity->desc }}"><i class="material-icons icons">&#xe254;</i>修改</button>

                        <button type="button" class="btn btn-outline-info btn-sm" onclick="delItem({{ $activity->id }})"><i class="material-icons icons">&#xe5c9;</i>刪除</button>
                        <!-- <a type="button" class="btn btn-outline-info btn-sm" href="{{"deleteActivity/". $activity['id'] }}">--刪除{{ $activity->id }}</a> -->
                    </td>
                </tr>
                @endforeach

                @if($paginator->count() == 0)
                <tr>
                    <td colspan="6">資料查無資料</td>
                </tr>
                @endif
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if(!$paginator->onFirstPage())
                <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
                </li>
                @endif
                @for ($i = $paginator->currentPage() - 2; $i <= $paginator->currentPage() + 2; $i++)
                    @if($i > 0 && $i <= $paginator->lastPage())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($paginator->lastPage() > $paginator->currentPage())
                <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- Modal 新增 -->
        
        <div class="ckeditorModel">
            <div class="ckeditorModelDialog">
                @include('manageActivity/activityUpload')    
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function closeModel(){
            $('.ckeditorModel').hide();
        }
        function openModel(item){
            $('.ckeditorModel').css({"display":"flex"});
        }

        $('#manageActivities').addClass(' active')
        $('input[name="keyword"]').val("{{ $params['keyword'] ?? '' }}");
        $('input[name="start_time"]').val("{{ $params['start_time'] ?? '' }}");
        $('input[name="end_time"]').val("{{ $params['end_time'] ?? '' }}");
        //var editActivityId = "<?php //echo $editActivityId; ?>"
        
        $('#add').on('click', function () {
            Date.prototype.yyyymmdd = function() {
                var mm = this.getMonth() + 1; // getMonth() is zero-based
                var dd = this.getDate();

                return [this.getFullYear(),
                    (mm>9 ? '' : '0') + mm,
                    (dd>9 ? '' : '0') + dd
                ].join('-');
            };
            var date = new Date();
            $('.modal-title').html('新增-活動');
            $('input[name="id"]').val('');
            $('input[name="subject"]').val('');
            $('input[name="activity_date"]').val(date.yyyymmdd());
            $('textarea[name="desc"]').val('');
            //window.desc.setData('');
        })
        $('.edit').on('click', function (item) {
            $('.modal-title').html('修改-活動');
            $('input[name="id"]').val($(item.target).attr('data-id'));
            $('input[name="subject"]').val($(item.target).attr('data-subject'));
            $('select[name="type_name"]').val($(item.target).attr('data-type_name'));
            $('textarea[name="desc"]').val($(item.target).attr('data-desc'));
            console.log($(item.target).attr('data-activity_date').slice(0, 10));
            console.log($(item.target).attr('data-desc'));
            $('input[name="activity_date"]').val($(item.target).attr('data-activity_date').slice(0, 10));
            
            //window.desc.setData($(item.target).attr('data-desc'));
        })
        
        $('.search').on('click', function () {
            console.log($('#search'));
            $('#search').submit();
        });

        function iconClose() {
            $('.alertClose').hide();
        }
        function delPhoto(id) {
            //alert(id)
            if(confirm("確認刪除嗎?")){
                //window.location.href='/deleteActivity/'+id
                $.ajax({
                    type:"get",
                    url:"/manageActivity/deleteImg/"+id,
                    data:{"id": id},
                    dataType:"json",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (result) {
                        console.log(result)
                        if (result != null && result.error == 0) {
                            alert("數據刪除成功！");
                            location.reload();
                        } else {

                            alert("數據刪除失敗");
                        }
                    }
                });
            }
        }
        function delItem(id) {
            //alert(id)
            if(confirm("確認刪除嗎?" + id)){
                //window.location.href='/deleteActivity/'+id
                $.ajax({
                    type:"get",
                    url:"/manageActivity/deleteActivity/"+id,
                    data:{"id": id},
                    dataType:"json",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (result) {
                        console.log(result)
                        if (result != null && result.error == 0) {
                            alert("數據刪除成功！");
                            location.reload();
                        } else {

                            alert("數據刪除失敗");
                        }
                    }
                });
            }
        }
    </script>
    <?php 
        // function editForm(){
        //     var value="abc";   
        //     location.href="test.php?value=" value;   
        // }
    ?>
 
 </x-app-layout>