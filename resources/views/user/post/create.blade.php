@extends('layouts.user')
@section('content')
    <div class="main-content">
        <div class="container pt-2">
            <header class="pb-1">
                <h4 style="font-weight: 600">一覧</h4>
            </header>
            <div class="select-platform col-2">
                <div class="platform-box">
                    <select name="select-platform" id="select-platform" class="p-2 w-100" title="Select platform">
                        <option value="instagram" {{ $platform == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="facebook" {{ $platform == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="twitter" {{ $platform == 'twitter' ? 'selected' : '' }}>Twitter</option>
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
                        @php
                            switch ($platform) {
                                case 'facebook':
                                    view('user.post.table-post', ['data' => $postData]);
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
