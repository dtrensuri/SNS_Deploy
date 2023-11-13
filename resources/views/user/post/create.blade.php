@extends('layouts.user')
@section('content')
    <div class="main-content">
        <div class="container pt-2">
            <header class="pb-1">
                <h4 style="font-weight: 600">作成</h4>
            </header>
            <div class="row d-flex align-items-center pb-2">
                <div class="select-platform col-2">
                    <div class="platform-box">
                        <select name="select-platform" id="select-platform" class="p-2 w-100" title="Select platform">
                            <option value="">All</option>
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
            <div class=" modal fade add_modal" id="modal-create-post" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="row modal-dialog" role="document">
                    <div class="modal-content p-4" id = "modal-content"></div>
                </div>
            </div>

            <div class="table-data-post">
                <table id="datatable" class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Ngày đăng</th>
                            <th scope="col">Nền tảng</th>
                            <th scope="col">Channel</th>
                            <th scope="col">Người đăng</th>
                            <th scope="col">Nội dung</th>
                            <th scope="col">Đường dẫn</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        {{ view('table.createdPost', ['data' => $postData]) }}
                    </tbody>
                </table>
                @if (isset($postData))
                    {{ $postData->links('pagination::default') }}
                @endif
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            selectPlatform.change(async function() {
                const selectedPlatform = selectPlatform.val();
                showLoading();
                tableBody.html('');
                fetchUrl(selectedPlatform);
            });

            $('body').on('click', '.openaddmodal', function() {
                var id = $(this).data('id');
                if (id == '') {
                    $('.modal-title').text("Create");
                } else {
                    $('.modal-title').text("Update");
                }
                $.ajax({
                    url: "{{ env('APP_ENV') == 'production' ? secure_url(route('get-create-modal')) : route('get-create-modal') }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    success: function(data) {
                        modalContent.html(data);
                        modal.modal('show');
                    },
                });
            });
        });

        const selectPlatform = $("#select-platform");
        const tableBody = $("#table-body");
        const loadingElement = $("#loading");
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const modalContent = $("#modal-content");
        const modal = $('#modal-create-post');

        function showLoading() {
            if (!loadingElement.hasClass("loading")) {
                loadingElement.addClass("loading");
            }
        }

        function hideLoading() {
            loadingElement.removeClass('loading');
        }


        function fetchUrl(platform) {
            $.ajax({
                url: "{{ env('APP_ENV') == 'production' ? secure_url(route('get-url-platform')) : route('get-url-platform') }}",
                method: 'post',
                data: {
                    _token: csrfToken,
                    platform: platform,
                    action: "create",
                },
                success: function(response) {
                    const url = response.url;
                    window.location.href = url;
                },
                error: function() {
                    return null;
                }
            });
        }

        function closeModal() {
            modal.modal('hide');
        }
    </script>
@endpush
