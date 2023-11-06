@foreach ($data as $index => $postDetail)
    <tr>
        <td>
            @php
                $time = new \DateTime($postDetail->posted_at);
                echo $time->format('d/m/Y');
            @endphp
        </td>
        <td>
            <div class="content-{{ $postDetail->post_id }} d-flex">
                @if (isset($postDetail->img))
                    <div class="img"
                        style="width: 60px;  height: 40px; background-size:cover ; background-image: url('{{ $postDetail->img->image_url }}')">
                    </div>
                @endif

                @if (isset($postDetail->content))
                    <p class="ms-2">
                        {!! $postDetail->content !!}
                        <br>
                        @isset($postDetail->link)
                            <a href="{{ $postDetail->link }}">>>View</a>
                        @endisset
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
                    {{ $postDetail->total_comment }}
                </p>
            </div>
        </td>
    </tr>
@endforeach
