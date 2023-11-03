@extends('layouts.user')
@section('content')
    <div class="main-content">
        <div class="container p-4">
            <header class="p-2">
                <h3 style="font-weight: 600">一覧</h3>
            </header>
            <div class="select-platform col-2">
                <div class="platform-box">
                    <select name="select-platform" id="select-platform" class="p-2 w-100">
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
                        {{-- @foreach ($data as $index => $postDetail)
                            <tr>
                                <td>
                                    @php
                                        $time = new \DateTime($postDetail->created_time);
                                        echo $time->format('d/m/Y H:i:s');
                                    @endphp
                                </td>
                                <td>
                                    <div class="content-{{ $postDetail->post_id }} d-flex">
                                        @if (isset($postDetail->img))
                                            <img src="{{ $postDetail->img->image_url }}" alt="Image" class="img-fluid"
                                                width="120px" height="100px">
                                        @endif

                                        @if (isset($postDetail->content))
                                            <p class="ms-2">
                                                {!! $postDetail->content !!}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="impressions-{{ $postDetail->post_id }}">
                                        <p>
                                            {{ $postDetail->total_impressions }}

                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="engaged-{{ $postDetail->post_id }}">
                                        <p>
                                            {{ $postDetail->total_engaged }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="reaction-{{ $postDetail->post_id }}">
                                        <p>
                                            {{ $postDetail->total_reactions }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="comment-{{ $postDetail->post_id }}">
                                        <p>
                                            {{ $postDetail->total_shares }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach --}}
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

            selectPlatform.change(function() {
                const selectedPlatform = selectPlatform.val();
                if (!loadingElement.hasClass("loading")) {
                    loadingElement.addClass("loading");
                }
                tableBody.html('');

                if (selectedPlatform === 'facebook') {
                    $.ajax({
                        url: 'http://127.0.0.1:8000/fb-post',
                        method: 'get',
                        data: {
                            platform: selectedPlatform
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
            });
        });
    </script>
@endsection
