@foreach ($channels as $index => $channel)
    <tr>
        <td class="w-40px">
            {{-- <a target="_blank" class="" href="https://twitter.com/o_thanh2502">
                <div class="sc-bczRLJ kYQZlO">
                    <div class="symbol symbol-no-variant symbol-circle"><span
                            class="symbol-label font-weight-bold font-size-h5"><i
                                class="fa-brands fa-twitter text-white icon-md"></i></span><i
                            class="symbol-badge symbol-badge-bottom bg-success"></i>
                    </div>
                </div>
            </a> --}}
        </td>
        <td class="pl-1">
            <div>
                <div class="text-elipsis text-muted">{{ isset($channel->id_channel) ? $channel->id_channel : '...' }}
                </div><a target="_blank" class="font-weight-bold text-elipsis"
                    href="https://{{ $channel->platform }}.com/{{ $channel->name_channel }}">{{ isset($channel->name_channel) ? $channel->name_channel : '...' }}</a>
                <div class="font-size-xs line-height-sm mt-1 text-elipsis text-muted">
                    <time datetime="{{ $channel->created_at }}">
                        {{ $channel->created_at }}
                    </time>,
                    {{ $channel->name_channel }}
                </div>
            </div>
        </td>
        <td class="pl-1 d-none d-md-table-cell">
            <div>
                <div class="text-elipsis text-muted"><span>-</span></div>
            </div>
        </td>
        <td class="text-center d-none d-sm-table-cell">
            <i class="bi bi-check-circle icon-md text-success"></i>
        </td>
        <td class="text-center d-none d-sm-table-cell">
            <i class="bi bi-check-circle icon-md text-success"></i>
        </td>
        <td class="text-center d-none d-sm-table-cell">
            <i class="bi bi-check-circle icon-md text-success"></i>
        </td>
        <td class="text-center d-none d-md-table-cell">
            <i class="bi bi-ban icon-md "></i>
        </td>
        <td class="text-right pr-3">
            <div class="dropdown-inline dropdown">
                <button type="button" class="btn-icon btn-circle d-none d-md-inline-block btn btn-light btn-md">
                    <i class="  bi bi-pencil icon-md"></i></button>
                <button type="button" class="btn-icon btn-circle my-1 ml-2 btn btn-light btn-md">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
        </td>
    </tr>
@endforeach
