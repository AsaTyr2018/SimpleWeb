<?php
$baseDir = 'datasets';
$folders = [];

foreach (new DirectoryIterator($baseDir) as $fileInfo) {
    if ($fileInfo->isDir() && !$fileInfo->isDot()) {
        $folders[] = $fileInfo->getFilename();
    }
}

function getRandomImageFromFolder($folderPath) {
    $images = glob($folderPath . '/*.{png}', GLOB_BRACE);
    

    if ($images) {
        return $images[array_rand($images)];
    }


    foreach (glob($folderPath . '/*', GLOB_ONLYDIR) as $subDir) {
        $image = getRandomImageFromFolder($subDir);
        if ($image) {
            return $image;
        }
    }

    return null;
}

function getSafetensorFiles($outputDir) {
    $safetensorFiles = [];
    if (is_dir($outputDir)) {
        foreach (new DirectoryIterator($outputDir) as $file) {
            if (!$file->isDot() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'safetensors') {
                $safetensorFiles[] = $file->getPathname();
            }
        }
    }
    return $safetensorFiles;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Datasets Overview</title>
    <style>
        body {
            background-color: #181818;
            color: #E0E0E0;
            font-family: Arial, sans-serif;
        }

        a {
            color: #BB86FC; 
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            padding: 16px;
        }

        .dataset-card {
            border: 1px solid #333; 
            padding: 16px;
            text-align: center;
            transition: transform .2s;
            background-color: #242424; 
        }

        .dataset-card:hover {
            transform: scale(1.05);
            background-color: #2d2d2d; 
        }

.dataset-card img {
    width: auto;
    max-width: 100%;
    max-height: 300px;
    margin-bottom: 8px;
    border-radius: 8px;
    display: block;
    margin-left: auto;
    margin-right: auto;
	height: 200px;  
    object-fit: contain;  
    object-position: center;  
}

.download-links {
    margin-top: 10px;
    font-size: 0.9em;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.download-button {
    width: 40px; 
    height: 40px;
    border: 1px solid #555;
    border-radius: 4px;
    background-color: #333;
    transition: background-color 0.2s;
    text-align: center;
    line-height: 40px; 
    display: inline-block;
}

.download-button:hover {
    background-color: #444;
}
        
        .download-links a {
            margin-right: 10px;
        }
		       
        #banner {
            width: 100%;
            height: 200px;  
            background-color: #333;  
            background-image: url('images/banner.png'); 
            background-size: cover;
            background-position: center;
            margin-bottom: 20px; 
            border-radius: 8px; 
        }
		       
        .safetensors-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase; 
            letter-spacing: 1px;
        }

        .safetensor-button {
            padding: 5px 8px;
            background-color: #333;
            border: 1px solid #555;
            border-radius: 4px;
            margin-right: 4px;
            color: #BB86FC;
            transition: background-color 0.2s;
        }

        .safetensor-button:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

<div id="banner"></div>  

<div class="grid-container">
    <?php foreach ($folders as $folder): ?>
        <div class="dataset-card">
            <?php
            $randomImage = getRandomImageFromFolder($baseDir . '/' . $folder);
            if ($randomImage) {
                echo '<img src="' . $randomImage . '" alt="' . $folder . '">';
            }
            ?>
            <h2><a href="gallery.php?folder=<?= urlencode($folder) ?>"><?= htmlspecialchars($folder) ?></a></h2>
            
           
<?php 
$safetensorFiles = getSafetensorFiles($baseDir . '/' . $folder . '/output');
if (!empty($safetensorFiles)): ?>
    <div class="download-links">
        <div class="safetensors-title">Safetensors</div>
        <?php foreach ($safetensorFiles as $file): ?>
            <?php 
            preg_match('/(\d+)$/', pathinfo($file, PATHINFO_FILENAME), $matches);
            if (isset($matches[1])) {
                $epochNumber = str_pad($matches[1], 2, '0', STR_PAD_LEFT); 
            } else {
                $epochNumber = "Final"; 
            }
            ?>
            <a href="<?= $file ?>" download class="safetensor-button"><?= $epochNumber ?></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
