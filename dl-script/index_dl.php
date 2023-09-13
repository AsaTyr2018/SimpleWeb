<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloadbereich</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

    
    .banner h1 {
        color: #fff; 
        background-color: rgba(0, 0, 0, 0.5); 
        padding: 10px;
        border-radius: 5px;
    }
        }
        .search-bar {
            flex-grow: 1;
            margin: 0 20px;
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
		.theme-toggle {
        cursor: pointer;
        background-color: var(--box-bg-color);
        padding: 10px 20px;
        border: none;
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s;
		}
    
		.theme-toggle:hover {
        background-color: var(--hover-bg-color);
		}
    
		:root {
        --bg-color: #f4f4f4;
        --text-color: #000;
        --box-bg-color: #fff;
        --hover-bg-color: #ddd;
		}

		body.dark-mode {
        --bg-color: #121212;
        --text-color: #fff;
        --box-bg-color: #1e1e1e;
        --hover-bg-color: #333;
    }
        .category {
            margin-bottom: 30px;
        }
        .category h2 {
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
.file-box {
    background-color: var(--box-bg-color);
    padding: 15px;
    margin-bottom: 15px;
    margin-right: 30px; 
    border: 1px solid #ddd;
    border-radius: 4px;
    display: grid;
    align-items: center;
    width: 200px;
    box-sizing: border-box; 
}

        .file-box .thumbnail-container {
            position: relative;
            max-width: 100px;
            margin-right: 20px;
        }
        .file-box img.thumbnail {
            width: 100px;
            cursor: pointer;
        }
        .file-box img.thumbnail:hover + .hover-preview {
            visibility: visible;
            opacity: 1;
        }
		.file-grid {
    display: grid;
		grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
		gap: 15px;
		}

        .hover-preview {
            visibility: hidden;
            opacity: 0;
            position: fixed;
            top: 50%;
            left: 50%;
            max-height: 736px;
            max-width: none;
            transform: translate(-50%, -50%);
            transition: visibility 0s, opacity 0.5s linear;
            z-index: 1000;
        }
        .file-info {
            flex: 1;
        }
        .file-info h3 {
            margin-top: 0;
        }

        :root {
            --bg-color: #f4f4f4;
            --text-color: #000;
            --box-bg-color: #fff;
        }

        body.dark-mode {
            --bg-color: #121212;
            --text-color: #fff;
            --box-bg-color: #1e1e1e;
        }
    </style>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }

        function searchFiles() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const files = document.querySelectorAll('.file-box');
            files.forEach(file => {
                if (file.querySelector('.file-info h3').innerText.toLowerCase().includes(query)) {
                    file.style.display = '';
                } else {
                    file.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
<div class="banner"></div>

<div class="header">
    <div></div>
    <div class="search-bar">
        <input type="text" id="search-input" onkeyup="searchFiles()" placeholder="Dateien suchen...">
    </div>
    <div class="theme-toggle" onclick="toggleDarkMode()">Light / Dark Mode</div>
</div>

<?php
$directory = 'content_dl/'; // Path to Content
$NoPic = "images/placeholder.png"; // Placeholder if no image is availible

$categories = glob($directory . '/*' , GLOB_ONLYDIR);

foreach ($categories as $category) {
    $fileCount = count(glob($category . '/*.safetensors')); // Counts the availible safetensors
    echo '<div class="category">';
    echo '<h2>' . basename($category) . ' (' . $fileCount . ')</h2>'; 

    echo '<div class="file-grid">'; // Grid container für die Dateien
    $files = glob($category . '/*.safetensors');
    foreach ($files as $file) {
        $basename = basename($file, '.safetensors');
        $thumb = $category . '/' . $basename . '.thumb.jpg';
        $highres = $category . '/' . $basename . '.png';

        // Thumbnail Check - Else Placeholder
        if (!file_exists($thumb)) {
            $thumb = $NoPic;
        }

        if (!file_exists($highres)) {
            $highres = $NoPic;
        }

        echo '<div class="file-box">';
        echo '<div class="thumbnail-container">';
        echo '<img src="' . $thumb . '" class="thumbnail" alt="Thumbnail">';
        echo '<img src="' . $highres . '" class="hover-preview" alt="HighRes Preview">';
        echo '</div>';
        echo '<div class="file-info">';
        echo '<h3>' . $basename . '</h3>';
        echo '<a href="' . $file . '" download>Download .safetensors</a>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>'; 
    echo '</div>'; 
}
?>


</body>
</html>
