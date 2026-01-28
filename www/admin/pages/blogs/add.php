<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

?>


<div class="container mt-4">
    <h1 class="mb-4">Nieuwe blogpost toevoegen</h1>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/forms/blog_add_form.php'; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
