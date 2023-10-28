<?php
$baseDir = 'datasets';
$folderName = $_GET['folder'] ?? '';
$items = [];

if ($folderName && is_dir($baseDir . '/' . $folderName)) {
    function scanFolder($dir) {
        $data = [];
        $supportedImageFormats = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        foreach (new DirectoryIterator($dir) as $item) {
            if (!$item->isDot()) {
                if ($item->isDir() && $item->getFilename() !== 'output') {
                    $data[$item->getFilename()] = scanFolder($item->getPathname());
                } else {
                    $extension = strtolower(pathinfo($item->getFilename(), PATHINFO_EXTENSION));
                    if (in_array($extension, $supportedImageFormats) || $extension == 'txt') {
                        $data[] = $item->getPathname();
                    }
                }
            }
        }
        return $data;
    }

    $items = scanFolder($baseDir . '/' . $folderName);
}

function txtFileExists($imagePath) {
    $txtFilePath = pathinfo($imagePath, PATHINFO_DIRNAME) . '/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.txt';
    return file_exists($txtFilePath) ? $txtFilePath : false;
}


$characters = array_filter(array_keys($items), function($item) {
    return !in_array(pathinfo($item, PATHINFO_EXTENSION), ['png', 'txt']);
});

$supportedImageFormats = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
$allImages = [];
array_walk_recursive($items, function($value, $key) use (&$allImages, $supportedImageFormats) {
    if (in_array(pathinfo($value, PATHINFO_EXTENSION), $supportedImageFormats)) {
        $allImages[] = $value;
    }
});

$outputDir = $baseDir . '/' . $folderName . '/output';
$safetensorFiles = [];
if (is_dir($outputDir)) {
    foreach (new DirectoryIterator($outputDir) as $file) {
        if (!$file->isDot() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'safetensors') {
            $safetensorFiles[] = $file->getPathname();
        }
    }
}

usort($safetensorFiles, function($a, $b) {
    return intval(pathinfo($a, PATHINFO_FILENAME)) - intval(pathinfo($b, PATHINFO_FILENAME));
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery: <?= htmlspecialchars($folderName) ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #242424; 
            color: #FFFFFF; 
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 20px;
        }

        .image-card {
			position: relative;
            max-width: 300px; 
            max-height: 300px;
            overflow: hidden; 
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #characterFilter {
            margin-bottom: 20px;
            color: #FFFFFF;
        }

        /* Stilisierung für die Lightbox */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.9); /* Fallback color */
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }
		.info-icon {
			position: absolute;
			bottom: 10px;
			right: 10px;
			width: 20px; // oder die gewünschte Größe
			height: 20px;
			z-index: 10;
			cursor: pointer; // optional, um den Mauszeiger in einen Zeiger zu ändern
		}


        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Gallery: <?= htmlspecialchars($folderName) ?></h1>
<a href="index.php" style="color: #FFFFFF;">Back to Overview</a>

<?php if ($characters): ?>
    <div id="characterFilter">
        Filter by Character:
        <select onchange="filterByCharacter(this.value)">
            <option value="">All</option>
            <?php foreach ($characters as $character): ?>
                <option value="<?= htmlspecialchars($character) ?>"><?= htmlspecialchars($character) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

<div class="grid-container">
    <?php foreach ($allImages as $imagePath): ?>
        <div class="image-card" data-character="<?= htmlspecialchars(dirname($imagePath)) ?>">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= pathinfo($imagePath, PATHINFO_BASENAME) ?>" onclick="openModal();currentSlide(this)" class="hover-shadow">
            <?php if (txtFileExists($imagePath)): ?>

                <img src="images/info.png" alt="Info" class="info-icon" data-txt-file="<?= htmlspecialchars(txtFileExists($imagePath)) ?>" onclick="showTxtContent(event, this)">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>



<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>

<?php if (!empty($safetensorFiles)): ?>
    <h2>Download Safetensors</h2>
    <ul>
        <?php foreach ($safetensorFiles as $file): ?>
            <?php 
            preg_match('/(\d+)$/', pathinfo($file, PATHINFO_FILENAME), $matches);
            $epochNumber = isset($matches[1]) ? intval($matches[1]) : '';
            $epochLabel = $epochNumber === 10 ? 'Epoch ' . $epochNumber : 'Epoch 0' . $epochNumber;
            ?>
            <li><a href="<?= $file ?>" download><?= $epochLabel ?> (<?= basename($file) ?>)</a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<script>
    function filterByCharacter(character) {
        const cards = document.querySelectorAll('.image-card');
        cards.forEach(card => {
            if (character === '' || card.getAttribute('data-character').endsWith(character)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function openModal() {
        document.getElementById("myModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    function currentSlide(element) {
        document.getElementById("img01").src = element.src;
        document.getElementById("caption").innerHTML = element.alt;
    }


    document.querySelector('.close').addEventListener('click', closeModal);
	
	function showTxtContent(event, element) {
    event.stopPropagation(); 
    const txtFilePath = element.getAttribute('data-txt-file');
    fetch(txtFilePath)
        .then(response => response.text())
        .then(data => {
            alert(data); 
        });
}

</script>

</body>
</html>
