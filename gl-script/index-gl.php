
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gallery</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<style>
    body {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin: 0;
        transition: background-color 0.3s;
        background-color: #fff;
        color: #000;
    }
    body.dark-mode {
        background-color: #121212;
        color: #fff;
    }

    .category {
        width: 200px;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
    }
    .category img {
        width: 100%;
    }
    .category .label {
        position: absolute;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        width: 100%;
        text-align: center;
        padding: 10px;
        cursor: pointer;
    }
    .dark-mode-toggle {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 60px;
        height: 30px;
        background: #121212;
        color: #fff;
        border: none;
        border-radius: 15px;
        cursor: pointer;
    }
</style>
<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>
</head>
<body>

<div class="header"></div>

<button class="dark-mode-toggle" onclick="toggleDarkMode()">Dark Mode</button>

<?php
    $dir = "content_gl/"; // Image directory

    if (isset($_GET['category'])) {
        $category = basename($_GET['category']);
        echo '<a href="index-gl.php">Zurück zu den Kategorien</a>';
        $files = glob($dir . $category . '/*.{jpg,jpeg,png}', GLOB_BRACE);
        foreach ($files as $file) {
            echo '<div class="category">';
            echo '<a href="' . $file . '" data-lightbox="gallery" data-title="' . $category . '">';
            echo '<img src="' . $file . '" alt="' . $category . '" />';
            echo '</a>';
            echo '<div class="label">' . $category . '</div>';
            echo '</div>';
        }
    } else {
        $categories = glob($dir . '*', GLOB_ONLYDIR);

        foreach ($categories as $category) {
            $files = glob($category . '/*.{jpg,jpeg,png}', GLOB_BRACE);
            if (count($files) > 0) {
                $random_image = $files[array_rand($files)];
                $category_name = basename($category);
                echo '<div class="category">';
                echo '<a href="' . $random_image . '" data-lightbox="gallery-' . $category_name . '" data-title="' . $category_name . '">';
                echo '<img src="' . $random_image . '" alt="' . $category_name . '" />';
                echo '</a>';
                echo '<div class="label" onclick="window.location.href=\'index-gl.php?category=' . urlencode($category_name) . '\'">' . $category_name . '</div>';
                echo '</div>';
            }
        }
    }
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>
</body>
</html>