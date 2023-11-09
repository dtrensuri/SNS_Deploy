@extends('layouts.user')

@section('content')
    <div class="main-content">
        <div class="d-flex flex-column flex-row-fluid">
            <div class="card card-stretch card-custom">
                <div class="card-header py-3 py-lg-5 h-auto px-6 px-lg-9">
                    <div class="row-marginless-half align-items-center flex-wrap flex-root row">
                        <div class="col-xl2-auto col-xl3-6 d-flex flex-wrap align-items-center">
                            <button type="button" class="min-w-xl-70px mr-2 my-1 my-xl-0 btn btn-success btn-md openmodal"
                                data-toggle="modal" data-target="#modal-channel" id="show-modal">
                                <i class="bi bi-lightning-charge-fill pe-1"></i>Connect
                            </button>
                            <div class="d-none d-sm-inline-block">
                                <button type="button"
                                    class="min-w-xl-70px btn-hover-facebook mr-2 my-1 my-xl-0 btn btn-light btn-md"
                                    id="add-fb-page">
                                    <i class="bi bi-facebook pe-1"></i>Facebook (Page)
                                </button>
                                <button type="button"
                                    class="min-w-xl-70px btn-hover-instagram mr-2 my-1 my-xl-0 btn btn-light btn-md">
                                    <i class="bi bi-instagram pe-1"></i>Instagram (Business)
                                </button>
                            </div>
                        </div>
                        <div class="col-md d-flex flex-wrap align-items-center justify-content-xl2-end text-xl2-right">
                            <div
                                class="separator w-100 w-100 d-xl2-none separator-default separator-solid separator-border-1 my-4">
                            </div>
                            <div class="flex-1 mr-3">
                                <div class="input-group-solid input-group">
                                    <input placeholder="Search" autocomplete="off" autocapitalize="off" autocorrect="off"
                                        spellcheck="false" type="input" class="form-control" value=""
                                        data-listener-added_68177c20="true">
                                    <div class="input-group-append">
                                        <div class="input-group-icon d-flex px-2">
                                            <i class=" p-2 bi bi-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-icon btn-circle btn btn-light btn-md">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="scrollable scrollable-stretch scrollable-spacing">
                        <div class="scrollable scrollable-stretch scrollable-spacing scrollable-no-margin">
                            <div class="react-bootstrap-table table-responsive">
                                <table class="table table-head-custom table-vertical-center overflow-hidden table-separate">
                                    <thead>
                                        <tr>
                                            <th tabindex="0" class="w-40px"></th>
                                            <th tabindex="0" class="pl-1">NAME</th>
                                            <th tabindex="0" class="pl-1 d-none d-md-table-cell">RENEWAL AT</th>
                                            <th tabindex="0" class="w-80px d-none d-sm-table-cell">
                                                <div class="d-flex flex-center text-center">
                                                    <i class="bi bi-chat-dots-fill icon-lg"></i>
                                                </div>
                                            </th>
                                            <th tabindex="0" class="w-80px d-none d-sm-table-cell">
                                                <div class "d-flex flex-center text-center">
                                                    <i class="bi bi-eye-fill icon-lg"></i>
                                                </div>
                                            </th>
                                            <th tabindex="0" class="w-80px d-none d-sm-table-cell">
                                                <div class="d-flex flex-center text-center">
                                                    <i class="bi bi-send-fill icon-lg"></i>
                                                </div>
                                            </th>
                                            <th tabindex="0" class="w-80px d-none d-md-table-cell">
                                                <div class="d-flex flex-center text-center">
                                                    <i class="bi bi-pie-chart-fill icon-lg"></i>
                                                </div>
                                            </th>
                                            <th tabindex="0" class="text-right pr-3 w-100px">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="added-channel"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="position-relative d-flex flex-wrap justify-content-between align-items-center pt-2">
                            <div class="d-flex flex-wrap mr-3 disabled" style="gap: 5px;">
                                <button type="button" class="btn-icon disabled btn btn-light btn-sm">
                                    <i class="bi bi-chevron-double-left icon-1x"></i>
                                </button>
                                <button type="button" class="btn-icon disabled btn btn-light btn-sm">
                                    <i class="bi bi-chevron-left icon-1x"></i>
                                </button>
                                <button type="button"
                                    class="btn-icon border-0 active cursor-default btn btn-light btn-sm">1
                                </button>
                                <button type="button" class="btn-icon disabled btn btn-light btn-sm">
                                    <i class="bi bi-chevron-right icon-1x"></i>
                                </button>
                                <button type="button" class="btn-icon disabled btn btn-light btn-sm">
                                    <i class="bi bi-chevron-double-right icon-1x"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center justify-content-end ml-auto">
                                <select class="form-control form-control-sm font-weight-bold border-0 bg-light"
                                    style="width: 75px;">
                                    <option class="">10</option>
                                    <option class="">25</option>
                                    <option class="">50</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-channel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog container" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center modal-title h4">Connect</div>
                        <button type="button" class="btn-icon btn-circle btn btn-light btn-md">
                            <i class="fa-solid fa-xmark icon-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="min-height: 450px; overflow:scroll" id="model-body"></div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId: "{{ env('FB_APP_ID') }}",
                    cookie: true,
                    xfbml: true,
                    version: "{{ env('FB_GRAPH_VERSION', 'v18.0') }}"
                });
            }

            $(document).ready(function() {
                $('#add-fb-page').click(function() {
                    facebookLoginAndRetrievePages();
                });
            });

            function facebookLoginAndRetrievePages() {
                FB.login(function(response) {
                    if (response.authResponse) {
                        FB.api('/me/accounts', 'GET', function(pagesResponse) {
                            console.log(pagesResponse);
                        });
                    } else {
                        console.log('Login failed');
                    }
                }, {
                    scope: 'pages_manage_ads,pages_manage_metadata,pages_read_engagement,pages_read_user_content,pages_manage_posts,pages_manage_engagement,pages_messaging,pages_show_list,read_insights,email,instagram_basic,instagram_content_publish,instagram_manage_messages,instagram_manage_comments,instagram_manage_insights,instagram_shopping_tag_products,publish_to_groups'
                });

                // FB.login(function(response) {
                //     if (response.authResponse) {
                //         FB.api('/me/accounts', 'GET', function(pagesResponse) {
                //             console.log(pagesResponse);
                //         });
                //     } else {
                //         console.log('Login failed');
                //     }
                // }, {
                //     scope: 'manage_pages',
                //     return_scopes: true
                // });
            };

            function getAddedChannel() {
                $.ajax({
                    url: "{{ secure_url(route('channel.added')) }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#added-channel').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX request to getAddedChannel failed: " + error);
                    }
                });
            }

            function showPlatformModal() {
                $.ajax({
                    url: "{{ secure_url(route('get-platform-modal')) }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('.modal-body').html(data);
                        $('#modal-channel').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX request to showPlatformModal failed: " + error);
                    }
                });
            }
        </script>
    @endpush
@endsection
