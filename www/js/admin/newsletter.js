document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('newsletter-admin-form');
    const previewButton = document.getElementById('preview-button');
    const statusDiv = document.getElementById('newsletter-status');
    const previewBox = document.getElementById('newsletter-preview');
    const previewSubject = document.getElementById('preview-subject');
    const previewContent = document.getElementById('preview-content');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const data = {
            subject: form.subject.value.trim(),
            message: form.message.value.trim()
        };

        const response = await fetch('/functions/newsletter/save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        statusDiv.textContent = result.message;
        statusDiv.style.color = result.success ? 'green' : 'red';
        if (result.success) form.reset();
    });

    previewButton.addEventListener('click', function () {
        previewSubject.textContent = form.subject.value.trim();
        previewContent.innerHTML = form.message.value.trim();
        previewBox.classList.remove('d-none');
        window.scrollTo({ top: previewBox.offsetTop - 80, behavior: 'smooth' });
    });

    // ✅ Alleen toevoegen als dropdown bestaat
    const templateSelect = document.getElementById('template');
    if (templateSelect) {
        templateSelect.addEventListener('change', function () {
            const template = this.value;
            if (!template) return;

            fetch(`/admin/pages/newsletter/get_template.php?file=${template}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('message').value = html;
                    document.getElementById('subject').value = 'Nieuwsbrief - ' + template.replace('.php', '').replace('_', ' ');
                })
                .catch(err => alert('Fout bij laden van template.'));
        });
    }
});
