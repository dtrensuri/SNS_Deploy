@extends('layouts.user')
@section('content')
    <div class="main-content">
        <div class="container pt-2">
            <header class="pb-1">
                <h4 style="font-weight: 600">一覧</h4>
            </header>
            <div class="row d-flex align-items-center justify-content-between">
                <div class="select-platform col-2">
                    <div class="platform-box">
                        <select name="select-platform" id="select-platform" class="p-2 w-100" title="Select platform">
                            <option value="instagram" {{ $platform == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="facebook" {{ $platform == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="twitter" {{ $platform == 'twitter' ? 'selected' : '' }}>Twitter</option>
                        </select>
                    </div>
                </div>
                <div class="refresh-btn col-1">
                    <button type="button" class="btn btn-outline-primary" onclick="refreshData()">
                        <div class="refresh-icon " id = "refresh-icon">
                            <i class="bi bi-arrow-repeat "></i>
                        </div>
                    </button>
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
                                    echo view('table.facebookPost', ['data' => $postData]);
                                    break;
                                case 'twitter':
                                    echo view('table.twitterPost', ['data' => $postData]);
                                    break;
                                case 'instagram':
                                default:
                                    break;
                            }
                        @endphp
                    </tbody>
                </table>
                {{ view('component.loading') }}
                @if (isset($postData))
                    {{ $postData->links('pagination::default') }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function refreshData() {
            const refreshButton = document.getElementById('refresh-icon');
            refreshButton.classList.add('refreshing');
            $.ajax({
                url: "{{ env('APP_ENV') == 'production' ? secure_url(route('user.facebookRefresh')) : route('user.facebookRefresh') }}",
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    refreshButton.classList.remove('refreshing');
                    location.reload();
                },
                error: function() {
                    refreshButton.classList.remove('refreshing');
                }
            });
        }
        const selectPlatform = $("#select-platform");
        const tableBody = $("#table-body");
        const loadingElement = $("#loading");
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    platform: platform
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


        $(document).ready(function() {

            selectPlatform.change(async function() {
                const selectedPlatform = selectPlatform.val();
                showLoading();
                tableBody.html('');
                fetchUrl(selectedPlatform);
            });

        });
    </script>
@endpush
