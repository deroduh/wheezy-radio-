<h3>Create Post</h3>

<div class="form-box">
    <form method="POST" action="index.php?action=savepost" name="form-post">
        <div class="form-element">
            <label for="title">Title</label>
            <input type="text" value="<?php echo (isset($post->title)) ? $post->title : ''; ?>" name="form[title]" />
        </div>

        <div class="form-element">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.0/js/froala_editor.pkgd.min.js"></script>
</script>
<label for="text">Text</label>
  <textarea name="form[text]" id="myEditor" value="">><?php echo (isset($post->text)) ? $post->text : ''; ?></textarea>
<script>
  $(function() {
    $('#myEditor').froalaEditor({toolbarInline: false})
  });
</script>
        </div>

         <div class="form-element">
            <label for="text">Text</label>
            <textarea value="" name="form[description]"><?php echo (isset($post->description)) ? $post->description : ''; ?></textarea>
        </div>
        <div class="form-element">
            <input type="hidden" value="<?php echo (isset($post->id)) ? $post->id : 0; ?>" name="form[id]" />
            <input type="submit" value="Save" name="submit" />
            <input type="submit" value="Cancel" name="cancel" />
        </div>
    </form>
</div>
