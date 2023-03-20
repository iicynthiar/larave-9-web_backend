<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CKEditor 5 – Classic editor</title>
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script> -->
    <style type="text/css">
        .ck-body-wrapper .ck-balloon-panel {z-index:9998 !important}
    </style>
</head>
<body>
<div class="modal-content">
    <form id="add_edit" method="post" action="" enctype="multipart/form-data">
    @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">新增-焦點新聞</h5>
            <button type="button" class="close closeModel" onclick="closeEditModel()">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <input type="hidden" name="id" value="">
            <div class="form-group">
                <!-- <label for="type_name">標題</label> -->
                <input type="text" name="subject" class="form-control" value="" placeholder="輸入標題">
            </div>
            <div class="form-group">
                <!-- <label for="type_name">日期</label> -->
                <input type="datetime-local" name="date" class="form-control">
            </div>
            <div class="form-group">
                <!-- <label for="type_name">類別</label> -->
                <select class="form-control" name="type_name" value="焦點新聞" placeholder="選擇類別">
                    <option value="焦點新聞">焦點新聞</option>
                </select>
            </div>
            <div class="form-group">
                <label for="type_name">發布內容</label>
                <textarea name="editor" id="editor" class="form-control" placeholder="輸入發布內容">
                </textarea>
                <!-- <div id="editor">This is some sample content.</div> -->
            </div>
            <!-- <div class="d-flex justify-content-end">
                <input type="button" text="清除關閉" class="btn btn-secondary m-1"/>
                <input type="submit" text="確認送出" class="btn btn-primary m-1"/>
            </div> -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary closeModel" onclick="closeEditModel()">清除關閉</button>
            <button type="submit" class="btn btn-info">確認儲存</button>
        </div>
    </form>
</div>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('ckeditor5-build-classic/ckeditor.js') }}"></script>
    <script>
        $('.edit').on('click', function (item) {
            openModel();
            $('.modal-title').html('修改-最新消息');
            $('input[name="id"]').val($(item.target).attr('data-id'));
            $('input[name="subject"]').val($(item.target).attr('data-subject'));
            $('select[name="type_name"]').val($(item.target).attr('data-type_name'));
            $('input[name="date"]').val($(item.target).attr('data-date'));
            window.editor.setData($(item.target).attr('data-editor'));
        })
        $("#add_edit").on("submit",function(event){
            if (!$('input[name="subject"]').val()) {
                alert('標題不能為空')
                return false;
            }
            $.ajax({
                type:"post",
                url:"/manageNews/manageNews",
                data:{
                    'id': $('input[name="id"]').val(),
                    'subject': $('input[name="subject"]').val(),
                    'date': $('input[name="date"]').val(),
                    'type_name': $('select[name="type_name"]').val(),
                    'editor': window.editor.getData(),
                },
                dataType:"json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (result) {
                    if (result != null && result.error == 0) {
                        alert("存储成功！");
                        location.reload();
                    } else {
                        alert("存储失败！");
                    }
                }
            });
            return false;
        })
            // ClassicEditor
            //         .create( document.querySelector( '#editor' ),{
            //             ckfinder:{
            //                 uploadUrl:'{{ route('ckeditor.upload').'?_token='.csrf_token()}}'
            //             }
            //         })
            //         .then( editor => {
            //                 console.log( editor );
            //         } )
            //         .catch( error => {
            //                 console.error( error );
            //         } );
            /////////////////////////////
        // Data Picker Initialization        
        // ClassicEditor
        //     .create( document.querySelector( '#editor' ) )
        //     .catch( error => {
        //         console.error( error );
        //     } );
        class MyUploadAdapter {
        constructor( loader ) {
            // The file loader instance to use during the upload.
            this.loader = loader;
            this.url = '{{ route('ckeditor.upload') }}';
        }

        // Starts the upload process.
        upload() {
            return this.loader.file
                .then( file => new Promise( ( resolve, reject ) => {
                    this._initRequest();
                    this._initListeners( resolve, reject, file );
                    this._sendRequest( file );
                } ) );
        }

        // Aborts the upload process.
        abort() {
            if ( this.xhr ) {
                this.xhr.abort();
            }
        }

        // Initializes the XMLHttpRequest object using the URL passed to the constructor.
        _initRequest() {
            const xhr = this.xhr = new XMLHttpRequest();

            // Note that your request may look different. It is up to you and your editor
            // integration to choose the right communication channel. This example uses
            // a POST request with JSON as a data structure but your configuration
            // could be different.
            xhr.open( 'POST', this.url, true );
            xhr.setRequestHeader('x-csrf-token', '{{ csrf_token() }}');
            xhr.responseType = 'json';
        }

        // Initializes XMLHttpRequest listeners.
        _initListeners( resolve, reject, file ) {
            const xhr = this.xhr;
            const loader = this.loader;
            const genericErrorText = `Couldn't upload file: ${ file.name }.`;

            xhr.addEventListener( 'error', () => reject( genericErrorText ) );
            xhr.addEventListener( 'abort', () => reject() );
            xhr.addEventListener( 'load', () => {
                const response = xhr.response;

                // This example assumes the XHR server's "response" object will come with
                // an "error" which has its own "message" that can be passed to reject()
                // in the upload promise.
                //
                // Your integration may handle upload errors in a different way so make sure
                // it is done properly. The reject() function must be called when the upload fails.
                if ( !response || response.error ) {
                    alert('上傳失敗！')
                    return reject( response && response.error ? response.error.message : genericErrorText );
                }

                // If the upload is successful, resolve the upload promise with an object containing
                // at least the "default" URL, pointing to the image on the server.
                // This URL will be used to display the image in the content. Learn more in the
                // UploadAdapter#upload documentation.

                //resolve( response );
                resolve( {
                    default: response.url
                } );
            } );

            // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
            // properties which are used e.g. to display the upload progress bar in the editor
            // user interface.
            if ( xhr.upload ) {
                xhr.upload.addEventListener( 'progress', evt => {
                    if ( evt.lengthComputable ) {
                        loader.uploadTotal = evt.total;
                        loader.uploaded = evt.loaded;
                    }
                } );
            }
        }

        // Prepares the data and sends the request.
        _sendRequest( file ) {
            // Prepare the form data.
            const data = new FormData();

            data.append( 'upload', file );

            // Important note: This is the right place to implement security mechanisms
            // like authentication and CSRF protection. For instance, you can use
            // XMLHttpRequest.setRequestHeader() to set the request headers containing
            // the CSRF token generated earlier by your application.

            // Send the request.
            this.xhr.send( data );
        }
    }

    // ...

    function SimpleUploadAdapterPlugin( editor ) {
        editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
            // Configure the URL to the upload script in your back-end here!
            return new MyUploadAdapter( loader );
        };
    }

    // ...

    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            extraPlugins: [ SimpleUploadAdapterPlugin ],
            
            link: {
                addTargetToExternalLinks: false
            }

            // ...
        } )
        .then( editor => {
            window.editor = editor;
        } )
        .catch( error => {
            console.log( error );
        } );
    </script>
</body>
</html>