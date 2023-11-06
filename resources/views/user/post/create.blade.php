@extends('layouts.user')
@section('content')
    <div class="main-content">
        <div class="container pt-2">
            <header class="pb-1">
                <h4 style="font-weight: 600">作成</h4>
            </header>
            <div class="row d-flex align-items-center">
                <div class="select-platform col-2">
                    <div class="platform-box">
                        <select name="select-platform" id="select-platform" class="p-2 w-100" title="Select platform">
                            <option value="instagram" {{ $platform == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="facebook" {{ $platform == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="twitter" {{ $platform == 'twitter' ? 'selected' : '' }}>Twitter</option>
                        </select>

                    </div>

                </div>
                <div class="col-2">
                    <a href="javascript:void(0)" data-id="" class="btn btn-outline-primary openaddmodal">Add post</a>
                </div>
            </div>
            <div class=" modal fade add_modal" id="exampleModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="row modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body addbody">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-data-post">
                <table id="datatable" class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th scope="col">公開日時</th>
                            <th scope="col">投稿内容</th>
                            <th scope="col">インプ</th>
                            <th scope="col">リーチ</th>
                            <th scope="col">いいね</th>
                            <th scope="col">コメント</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @php
                            switch ($platform) {
                                case 'facebook':
                                    echo view('user.post.table-post', ['data' => $postData]);
                                    break;
                                case 'twitter':
                                    break;
                                case 'instagram':
                                default:
                                    break;
                            }
                        @endphp
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('body').on('click', '.openaddmodal', function() {
                var id = $(this).data('id');
                if (id == '') {
                    $('.modal-title').text("Create");
                } else {
                    $('.modal-title').text("Update");
                }
                $.ajax({
                    url: "{{ route('user.get-create-modal') }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    success: function(data) {
                        $('.addbody').html(data);
                        $('.add_modal').modal('show');
                    },
                });
            });

            // $('body').on('submit', '.formsubmit', function(e) {
            //     e.preventDefault();
            //     $.ajax({
            //         url: $(this).attr('action'),
            //         data: new FormData(this),
            //         type: 'POST',
            //         contentType: false,
            //         cache: false,
            //         processData: false,
            //         beforeSend: function() {
            //             $('.spinner').html('<i class="fa fa-spinner fa-spin"></i>')
            //         },
            //         success: function(data) {
            //             if (data.status == 400) {
            //                 $('.spinner').html('');

            //             }
            //             if (data.status == 200) {
            //                 $('.spinner').html('');
            //                 $('.add_modal').modal('hide');

            //                 $("#datatable").DataTable().ajax.reload();
            //             }
            //         }
            //     });
            // });

            // $('body').on('click', '.publishToProfile', function() {
            //     var id = $(this).data('id');
            //     $.ajax({
            //         url: "{{ url('page') }}",
            //         type: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
            //         },
            //         data: {
            //             id: id
            //         },
            //         success: function(data) {
            //             if (data.status == 200) {
            //                 $.confirm({
            //                     title: 'Success!',
            //                     content: data.msg,
            //                     autoClose: 'cancelAction|3000',
            //                     buttons: {
            //                         cancelAction: function(e) {}
            //                     }
            //                 })
            //             }
            //             if (data.status == 400) {
            //                 $.alert({
            //                     title: 'Alert!',
            //                     content: data.msg,
            //                 });
            //             }
            //         }
            //     });
            // });
        });
    </script>
@endpush
