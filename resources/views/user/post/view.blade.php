@extends('layouts.user')
@section('content')
    <div class="main-content">
        <div class="container p-4">
            <header class="p-2">
                <h3 style="font-weight: 600">一覧</h3>
            </header>
            <div class="select-platform col-2">
                <div class="platform-box">
                    <select name="select-platform" id="select-platform" class="p-2 w-100" title="Select platform">
                        <option value="instagram" selected>Instagram</option>
                        <option value="facebook">Facebook</option>
                        <option value="twitter">Twitter</option>
                    </select>
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
                    </tbody>
                </table>
                {{ view('component.loading') }}
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const selectPlatform = $("#select-platform");
            const tableBody = $("#table-body");
            const loadingElement = $("#loading");

            function fetchData(platform) {
                if (platform === 'facebook') {
                    $.ajax({
                        url: "{{ route('user.table.fb-post-info') }}",
                        method: 'get',
                        data: {
                            platform: platform
                        },
                        success: function(response) {
                            loadingElement.removeClass('loading');
                            tableBody.html(response);
                        },
                        error: function() {
                            loadingElement.removeClass('loading');
                            alert('Error fetching data.');
                        }
                    });
                }
            }

            selectPlatform.change(function() {
                const selectedPlatform = selectPlatform.val();
                if (!loadingElement.hasClass("loading")) {
                    loadingElement.addClass("loading");
                }
                tableBody.html('');
                fetchData(selectedPlatform);
            });

            fetchData(selectPlatform.val());
        });
    </script>
@endsection
