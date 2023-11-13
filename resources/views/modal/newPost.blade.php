<div class=" min-h-60px min-h-lg-70px py-2 justify-content-center d-flex">
    <div class=" h4">Create A Post</div>
</div>

<div class="scrollable modal-post-preview-scrollable scrollable-stretch">
    <div class="card card-stretch card-custom">
        <div class="card-body position-relative">
            <div class="scrollable scrollable-stretch scrollable-spacing" id="post_editor_scroll">
                <form
                    action="{{ env('APP_ENV') == 'production' ? secure_url(route('user.handle-create-post')) : route('user.handle-create-post') }}"
                    method="POST" class="formsubmit d-flex row" id="form-create-post" enctype="multipart/form-data">

                    <div class="">
                        @csrf
                        @if (isset($data) && !empty($data->id))
                            <input type="hidden" name="id" value="{{ encrypt($data->id) }}">
                        @endif

                        <div class="form-group">
                            <div class="input-group-solid input-group">
                                <div class="input-group-prepend"><button type="button"
                                        class="btn-icon h-100 btn btn-light btn-md">
                                        <i class="bi bi-folder2-open icon-md"></i>
                                    </button></div>

                                <div class="form-control dropdown css-ngzdig-container d-flex" type="button"
                                    data-bs-toggle="dropdown" aria="false">
                                    <div class="react-select__control css-pqor6n-control">
                                        <div
                                            class="react-select__value-container react-select__value-container--is-multi ">
                                            <div class="react-select__placeholder" id="react-select-50-placeholder">
                                                Channels</div>

                                        </div>
                                    </div>

                                </div>
                                <ul class="dropdown-menu" id="list-channel">
                                    <div class="p-2 mx-2 drop-item">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="all" disabled
                                                name="all">
                                            <label class="form-check-label" for="all">--ALL--</label>
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="twitter"
                                                name="twitter">
                                            <label class="form-check-label" for="twitter">Twitter</label>
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="facebook"
                                                name="facebook">
                                            <label class="form-check-label" for="facebook">Facebook</label>
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="instagram" disabled
                                                name="instagram">
                                            <label class="form-check-label" for="instagram">Instagram</label>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x flex-nowrap w-100 nav-tabs-side-lines nav-tabs-bordered"
                                role="tablist">
                                <li class="nav-item w-100" id ="imageCardBody">
                                    <div class="nav-link h-100 m-0 justify-content-center text-center cursor-pointer active"
                                        option="image">
                                        <span class="nav-icon">
                                            <i class="bi bi-image icon-lg"></i>
                                        </span>
                                    </div>
                                </li>
                                <li class="nav-item w-100" id= "videoCardBody">
                                    <div class="nav-link h-100 m-0 justify-content-center text-center cursor-pointer"
                                        option = "video">
                                        <span class="nav-icon">
                                            <i class="bi bi-camera-reels-fill icon-lg"></i>
                                        </span>
                                    </div>
                                </li>
                                <li class="nav-item w-100" id = "linkCardBody">
                                    <div class="nav-link h-100 m-0 justify-content-center text-center cursor-pointer"
                                        option = "link">
                                        <span class="nav-icon">
                                            <i class="bi bi-link icon-lg"></i>
                                        </span>
                                    </div>
                                </li>
                                <li class="nav-item w-100" id = "textCardBody">
                                    <div class="nav-link h-100 m-0 justify-content-center text-center cursor-pointer"
                                        option = "text">
                                        <span class="nav-icon">
                                            <i class="bi bi-type icon-lg"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="nav-item w-100" id = "storyCardBody">
                                    <div class="nav-link h-100 m-0 justify-content-center text-center cursor-pointer"
                                        option = "story">
                                        <span class="nav-icon">
                                            <i class="bi bi-phone icon-lg"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="nav-item w-100" id = "reelCardBody">
                                    <div class="nav-link h-100 m-0 justify-content-center text-center cursor-pointer"
                                        option = "real">
                                        <span class="nav-icon">
                                            <i class="bi bi-film icon-lg"></i>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex flex-column mt-4 " id="card-body">

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function preview() {
        frame.src = URL.createObjectURL(event.target.files[0]);
    }

    function clearImage() {
        document.getElementById('formFile').value = null;
        frame.src = "";
    }

    function cancelAndClearForm() {
        $('#exampleModal').modal('hide');
        document.getElementById('form-create-post').reset();
    }

    function getCardCreatePort(option = null) {
        let cardbody = $('#card-body');
        $.ajax({
            url: "{{ env('APP_ENV') == 'production' ? secure_url(route('user.facebookCreateCard')) : route('user.facebookCreateCard') }}",
            method: 'get',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                option: option
            },
            success: function(response) {
                console.log(response);
                cardbody.html(response);
            },
            error: function() {
                return null;
            }
        });
    }


    $(document).ready(function() {

        getCardCreatePort();
        $('.nav-link').on('click', function() {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            var optionValue = $(this).attr('option');
            console.log(optionValue);
            getCardCreatePort(optionValue);
        });
    });
</script>
