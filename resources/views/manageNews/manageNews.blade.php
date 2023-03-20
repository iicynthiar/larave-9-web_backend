<x-app-layout>
     <div>
        <div class="d-flex p-2">
            <h5 class="mr-auto text-primary">最新消息</h5>
            <button type="button" id="add" class="btn btn-info btn-sm" onclick="openModel('Add')">
            <i class="material-icons icons">&#xe147;</i>
            新增
            </button>
        </div>
        <div class="searchGrp">
            <form id="search" class="form-inline">
                <!-- <input type="text" name="dialog"/> -->
                <select class="form-control form-control-sm" name="year" id="" placeholder="選擇類別">
                    <option>年度查詢</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <input class="formTxt form-control-sm ml-2 w-25" name="keyword" type="text" placeholder="標題、內文查詢">
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
                    <th scope="col">標題</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1 ?>
                @foreach($paginator->items() as $item)
                <tr>
                    <td><?php print $no++ ?></td>
                    <td>{{ strtr($item->date, 'T', ' ') }}-{{ $item->subject }}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm edit" data-toggle="modal" data-target="#exampleModalCenter"
                                data-id="{{ $item->id }}"
                                data-subject="{{ $item->subject }}"
                                data-date="{{ $item->date }}"
                                data-type_name="{{ $item->type_name }}"
                                data-editor="{{ $item->desc }}"><i class="material-icons icons">&#xe254;</i>修改</button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="delItem({{ $item->id }})"><i class="material-icons icons">&#xe5c9;</i>刪除</button>
                    </td>
                </tr>
                @endforeach
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
            @include('manageNews/ckeditor5')
            
                <!-- @if($message_sent == 'Add') -->
                
                <!-- @endif -->
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript">
        function closeEditModel(){
            $('.ckeditorModel').hide();
        }
        function openModel(item){
            $('.ckeditorModel').css({"display":"flex"});
        }
        $('#manageNews').addClass(' active')
        $('select[name="year"]').val("{{ $params['year'] ?? '' }}");
        $('input[name="keyword"]').val("{{ $params['keyword'] ?? '' }}");
        $('input[name="start_time"]').val("{{ $params['start_time'] ?? '' }}");
        $('input[name="end_time"]').val("{{ $params['end_time'] ?? '' }}");
        function delItem(id) {
            if(confirm("確認刪除嗎?")){
                $.ajax({
                    type:"delete",
                    url:"/manageNews/manageNews",
                    data:{"id": id},
                    dataType:"json",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (result) {
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
        $('.search').on('click', function () {
            console.log($('#search'));
            $('#search').submit();
        });


        // $('.close').on('click', function () {
        //     $('.ckeditorModel').fadeOut("slow").css({"display":"none"});
        // });

        // function openModel(id){
        //     //alert(id);
        //     $('.ckeditorModel').fadeTo("slow",1).css({"display":"flex"});
        //     $('.ckeditorModel .ckeditorModelDialog').slideDown("slow");
        //     // $.ajax({
        //     //     type:"get",
        //     //     url:"/openModel/"+ id,
        //     //     data:{"id": id},
        //     //     dataType:"json",
        //     //     headers: {
        //     //         'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //     //     },
        //     //     success: function (result) {
        //     //         if (result != null && result.error == 0) {
        //     //             console.log(result)
        //     //             alert("數據刪除成功！");
        //     //             location.reload();
        //     //         } else {
        //     //             console.log(result)
        //     //             alert("數據刪除失敗");
        //     //         }
        //     //     }
        //     // });
        // }
        // $('#add').on('click', function () {            
            
        //     // openModel();
        //     // Date.prototype.yyyymmdd = function() {
        //     //     var mm = this.getMonth() + 1; // getMonth() is zero-based
        //     //     var dd = this.getDate();

        //     //     return [this.getFullYear(),
        //     //         (mm>9 ? '' : '0') + mm,
        //     //         (dd>9 ? '' : '0') + dd
        //     //     ].join('-');
        //     // };
        //     // var date = new Date();
        //     // $('.modal-title').html('新增-最新消息');
        //     // $('input[name="id"]').val('');
        //     // $('input[name="subject"]').val('');
        //     // $('input[name="date"]').val(date.yyyymmdd() + "T00:00");
        //     // window.editor.setData('');
        // });
        // $('.edit').on('click', function (item) {
        //     openModel();
        //     $('.modal-title').html('修改-最新消息');
        //     $('input[name="id"]').val($(item.target).attr('data-id'));
        //     $('input[name="subject"]').val($(item.target).attr('data-subject'));
        //     $('select[name="type_name"]').val($(item.target).attr('data-type_name'));
        //     $('input[name="date"]').val($(item.target).attr('data-date'));
        //     window.editor.setData($(item.target).attr('data-editor'));
        // })
    </script>  
</x-app-layout>