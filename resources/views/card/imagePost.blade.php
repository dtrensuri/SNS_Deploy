<input type="hidden" name="typePost" value="image" />

<div class="d-flex flex-column mt-2">

    <div class="dropzone-msg">
        <h3 class="dropzone-msg-title text-dark-75">Drop files here or click to upload.</h3>
        <span class="dropzone-msg-desc text-muted">Only image files are allowed for upload. (Max 25MB)</span>
    </div>
    <form enctype="multipart/form-data" action="/" method="POST" class="dropzone" id="my-awesome-dropzone">
        <input type="hidden" name="MAX_FILE_SIZE" value="25000" />
        @csrf
    </form>

</div>


<div class="form-group">
    <label for="title">Title <span class="require">*</span></label>
    <input type="text" class="form-control" name="title" />
</div>

<div class="form-group">
    <label for="description">Content</label>
    <textarea rows="5" class="form-control" name="description"></textarea>
</div>


<div class="form-group">
    <button type="submit" class="btn btn-primary">
        Create
    </button>
    <button type="button" class="btn btn-default" onclick="cancelAndClearForm()">
        Cancel
    </button>
</div>

<script>
    Dropzone.discover();
</script>
