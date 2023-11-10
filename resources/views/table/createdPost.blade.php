@isset($data)
    @foreach ($data as $index => $postDetail)
        <tr>
            <td>
                @php
                    $time = new \DateTime($postDetail->created_at);
                    echo $time->format('d/m/Y');
                @endphp
            </td>
            <td>
                @php
                    $time = new \DateTime($postDetail->posted_time);
                    echo $time->format('d/m/Y');
                @endphp
            </td>

            <td>
                {{ $postDetail->platform }}
            </td>
            <td>

            </td>
            <td>
                {{ $postDetail->user['name'] }}
            </td>
            <td>
                <div class="content-{{ $postDetail->post_id }} d-flex">
                    @if (isset($postDetail->img))
                        <div class="img"
                            style="width: 60px; height: 40px; background-size: cover; background-image: url('{{ $postDetail->img->image_url }}')">
                        </div>
                    @endif

                    @if (isset($postDetail->content))
                        <p class="ms-1" style="max-width: 100%;">
                            {!! $postDetail->content !!}
                        </p>
                    @endif
                </div>
            </td>
            <td class="truncate-cell">
                <a href="{{ $postDetail->link }}">
                    {{ $postDetail->link }}
                </a>
            </td>
            <td>
                {{ $postDetail->status }}
            </td>
        </tr>
    @endforeach
@endisset
