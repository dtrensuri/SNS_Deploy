<form action="{{ route('user.handle-create-post') }}" method="POST" class="formsubmit d-flex" id="form-create-post"
    enctype="multipart/form-data">

    <div class="">
        @csrf
        @if (isset($data) && !empty($data->id))
            <input type="hidden" name="id" value="{{ encrypt($data->id) }}">
        @endif


        <div class="form-group">
            <label for="title">Title <span class="require">*</span></label>
            <input type="text" class="form-control" name="title" />
        </div>

        <div class="form-group">
            <label for="description">Content</label>
            <textarea rows="5" class="form-control" name="description"></textarea>
        </div>

        <div class="form-group row">
            &nbsp;&nbsp; Image
            <div class="col-md-12">
                <div class="avatar-upload" style="margin: 2px;">
                    <div class="avatar-edit">
                        <input type="file" id="formFile" name="image" onchange="preview()" />
                        {{-- <div onclick="clearImage()" class="btn btn-primary mt-3">Clear</div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content">
                    <img id="frame" src="" class="img-fluid" />
                </div>
            </div>
            <script></script>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Create
            </button>
            <button type="button" class="btn btn-default" onclick="cancelAndClearForm()">
                Cancel
            </button>
        </div>
    </div>

    <div class="p-2 mx-2">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="all" disabled name="all">
            <label class="form-check-label" for="exampleCheck1">--ALL--</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="twitter" name="twitter">
            <label class="form-check-label" for="exampleCheck1">Twitter</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="facebook" disabled name="facebook">
            <label class="form-check-label" for="exampleCheck1">Facebook</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="instagram" disabled name="instagram">
            <label class="form-check-label" for="exampleCheck1">Instagram</label>
        </div>

    </div>

</form>
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
</script>
