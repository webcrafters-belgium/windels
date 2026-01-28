<?php include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/zje5a800hqt96bgn4ex8jhddjx4tpnoht9c98reetr69igg6/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>


<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Pagina succesvol toegevoegd.</div>
<?php endif; ?>


<div class="container mt-5">
    <h2>Nieuwe Pagina</h2>
    <form method="POST" action="/admin/functions/add_pages/save_page.php">
        <div class="mb-3">
            <label>Titel</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Slug (bv. over-ons)</label>
            <input type="text" name="slug" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Inhoud</label>
            <textarea name="content" class="form-control" rows="10"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Opslaan</button>
    </form>
</div>

<script>
    tinymce.init({
        selector: 'textarea[name="content"]',
        height: 400,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic underline | \
              alignleft aligncenter alignright alignjustify | \
              bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
