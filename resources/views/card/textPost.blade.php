<input type="hidden" name="typePost" value="text" />

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
