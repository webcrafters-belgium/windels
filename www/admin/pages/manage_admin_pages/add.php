<?php


require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $icon_class = $_POST['icon_class'];
    $url = $_POST['url'];
    $section = $_POST['section'];
    $visibility = $_POST['visibility'];
    $roles = $_POST['roles'];
    $display_order = (int)$_POST['display_order'];
    //$user_id = (int)$_SESSION['user']['id'];
    $user_id = 0;

    $stmt = $conn->prepare("INSERT INTO admin_pages (title, icon_class, url, section, visibility, roles, display_order, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $title, $icon_class, $url, $section, $visibility, $roles, $display_order, $user_id);
    if ($stmt->execute()) {
        header("Location: /admin/");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Fout bij toevoegen.</div>";
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

?>

<div class="container mt-5">
    <h2>➕ Adminpagina toevoegen</h2>
    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="title" class="form-label">Titel</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="icon_class" class="form-label">Icon Class (bijv. bi-box)</label>
            <input type="text" name="icon_class" id="icon_class" class="form-control">
        </div>

        <div class="mb-3">
            <label for="url" class="form-label">URL (relatief)</label>
            <input type="text" name="url" id="url" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="section" class="form-label">Sectie</label>
            <input type="text" name="section" id="section" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="visibility" class="form-label">Zichtbaarheid</label>
            <select name="visibility" id="visibility" class="form-select">
                <option value="visible">Zichtbaar</option>
                <option value="hidden">Verborgen</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="roles" class="form-label">Toegestane Rol</label>
            <select name="roles" id="roles" class="form-select">
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="user">User</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="display_order" class="form-label">Volgorde</label>
            <input type="number" name="display_order" id="display_order" class="form-control" value="0">
        </div>

        <button type="submit" class="btn btn-success">✅ Opslaan</button>
        <a href="/admin/" class="btn btn-secondary">Annuleren</a>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
