## Table of Contents
- [SimpleWeb for LoRA Files](#simpleweb-for-lora-files)
- [DL-Script](#dl-script)
- [GL-Script](#gl-script)
- [Dataset-Viewer](#dataset-viewer)


# SimpleWeb for LoRA Files
Simple Web Scripts for Simple Webs. No Mysql required.

## DL-Script
Simple Scripts for LoRA Safetension Files (dl-script)
![grafik](https://github.com/AsaTyr2018/SimpleWeb/assets/43162495/d46e34c4-4933-466a-8de5-49162b066388)

Demo: https://test.lenz-host.de/dl-script/

Just place the Loras and the Preview Images in the content_dl directory. The Script index them automaticly.

## GL-Script
Same as the DL-Script. This is a simple Gallery Script. It reads out the content of content_gl.
Folders within content_gl are used as categories and the images inside gets randomly selected as thumbnail for the categorie.
clicking on the image opens the lightbox. Clicking on the Titel opens the folder/Gallery itself.

Demo: https://test.lenz-host.de/gl-script/

![grafik](https://github.com/AsaTyr2018/SimpleWeb/assets/43162495/0f4504e4-0285-4317-8a05-f737308eefd9)

## Dataset-Viewer
This script dynamically generates an image gallery from a specified directory and its subdirectories. It serves as an interface to view images and associated metadata, with added functionalities as highlighted below:

Directory Handling:

The base directory is set as 'datasets'.
When provided with a 'folder' parameter through a GET request, the script scans this folder to fetch image files and their associated '.txt' sidecar files.
Supported Formats:

The gallery supports various image formats: PNG, JPG, JPEG, GIF, and WEBP.
Recursive Scanning:

The scanFolder function recursively scans directories to fetch both image and '.txt' files.
Image Card Creation:

For every image, an image card is created on the gallery page. If a sidecar '.txt' file exists for an image, an info icon is shown at the bottom-right of the image.
Image Lightbox:

Clicking on an image opens it in a modal (lightbox) view for a more detailed inspection.
Character Filter:

If image files are organized under character-named subdirectories, users can filter the gallery view by character.
Safetensors Download Links:

The script lists downloadable '.safetensors' files (if available) from the 'output' subdirectory, sorted by epoch.
Styling & Interactivity:

The script includes CSS for styling the gallery and JavaScript for interactive elements, such as the modal view and character filter functionality.

Demo: https://test.lenz-host.de/Dataset-Viewer/

![grafik](https://github.com/AsaTyr2018/SimpleWeb/assets/43162495/92e4944a-bb61-4612-b47a-060abcd2d32a)
![grafik](https://github.com/AsaTyr2018/SimpleWeb/assets/43162495/bdca2218-c625-450e-b7da-1ec20030e50f)

