<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Announcement | Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl() ?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/doctor/addannouncement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>
    <main>
        <h2>Add New Announcement</h2>
        <div class="container">
            <a href="<?= baseurl() ?>/pages/manageservices" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>

            <form action="<?= baseurl() ?>/admin/addservice" method="post" enctype="multipart/form-data">
                
                <label for="name">Service Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="description">Short Description:</label>
                <textarea name="description" id="description"></textarea>

                <label for="long_description">Long Description:</label>
                <textarea name="long_description" id="long_description"></textarea>

                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" required>

                <label for="category">Category:</label>
                <input type="text" name="category" id="category">

                <label for="image">Image:</label>
                <input type="file" name="image" id="image">
                <input type="hidden" name="image_url" id="supabase_image_url">

                <label for="is_active">Active:</label>
                <input type="checkbox" name="is_active" id="is_active" checked>

                <button type="submit">Add</button>
            </form>
        </div>
    </main>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
<script>
    const { createClient } = window.supabase;
    const supabase = createClient('https://vmkcpsojjqyodnaflgof.supabase.co', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZta2Nwc29qanF5b2RuYWZsZ29mIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcxMjM4ODEsImV4cCI6MjA2MjY5OTg4MX0.FejG_y8Klqc0Xhs-3cKGf0rWoCtnchJktpW3ZgVGS9E');

    const fileInput = document.getElementById('image');
    const submitBtn = document.querySelector('button[type="submit"]');
    const hiddenInput = document.getElementById('supabase_image_url');

    fileInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        submitBtn.disabled = true;
        const filePath = Date.now() + '_' + file.name;
        const { data, error } = await supabase.storage
            .from('services')
            .upload(filePath, file, { upsert: true });

        console.log('Upload data:', data, 'error:', error); // <-- Add this line

        if (!error && data && data.path) {
            const { data: urlData, error: urlError } = supabase.storage.from('services').getPublicUrl(data.path);
            if (!urlError && urlData && urlData.publicUrl) {
                hiddenInput.value = urlData.publicUrl;
                alert('Image uploaded successfully!');
            } else {
                alert('Failed to get public URL!');
            }
        } else {
            alert('Image upload failed!');
        }
        submitBtn.disabled = false;
    });

    // Prevent form submission if image_url is empty
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!hiddenInput.value) {
            e.preventDefault();
            alert('Please wait for the image to finish uploading.');
        }
    });
</script>
</body>
</html>

