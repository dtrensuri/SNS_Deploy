@foreach ($data as $index => $postDetail)
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
                    <img src="{{ $postDetail->img->image_url }}" alt="Image" class="img-fluid" width="120px"
                        height="100px">
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
@endforeach
