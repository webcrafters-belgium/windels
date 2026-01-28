<style>
    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;

        label {
            font-weight: bold;
        }

        input, textarea, select {
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
            width: 100%;
        }

        button {
            align-self: flex-start;
            background-color: #2563eb;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;

            &:hover {
                background-color: darken(#2563eb, 10%);
            }
        }
    }

</style>
<form method="post" action="/API/blog/add_blog_post.php" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title">Titel</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="author" class="form-label">Auteur</label>
        <input type="text" class="form-control" name="author" id="author" value="<?= $_SESSION['user']['name'] ?? 'Gast' ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="content">Inhoud</label>
        <textarea name="content" id="content" class="form-control" rows="10">
            <?= htmlspecialchars($content ?? '') ?>
        </textarea>
    </div>

    <div class="mb-3">
        <label for="image">Afbeelding (optioneel)</label>
        <input type="file" name="image" id="image" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Publiceren</button>
</form>

<!-- TinyMCE init -->
<script src="https://cdn.tiny.cloud/1/xdjt6vqjx1h0oh9f9f084xr4z88g2dppg86b1c9atd4dvhfn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'link image code lists table',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
        height: 400,
        language: 'nl',
        entity_encoding: 'raw'
    });
</script>
