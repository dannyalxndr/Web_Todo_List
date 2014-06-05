<?php

$filename = 'list.txt';
$newitems = getfile($filename);
$errorMessage = '';

function save_file($filename, $array)
{
    if (is_writable($filename)) 
    {
        $handle = fopen($filename, 'w');
        foreach($array as $items)
        {
            fwrite($handle, PHP_EOL . $items);
        }
        fclose($handle);
    }
}

function getfile($filename) 
{
    $contents = [];
    if (is_readable($filename) && filesize($filename) > 0)
    {
        $handle = fopen($filename, 'r');
        $bytes = filesize($filename);
        $contents = trim(fread($handle, $bytes));
        fclose($handle);
        $contents = explode("\n", $contents);
        save_file($filename, $contents);
    }
    return $contents;
} 

if (isset($_GET['removeitem'])) 
{
    $removeindex = $_GET['removeitem'];
    unset($newitems[$removeindex]);
    save_file($filename, $newitems);
}

if (!empty($_POST['todoitem'])) 
{
    array_push($newitems, $_POST['todoitem']);
    save_file($filename, $newitems);
}

if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) 
{

    if ($_FILES['file1']['type'] == 'text/plain') 
    {
        // Set the destination directory for uploads
        $upload_dir = '/vagrant/sites/todo.dev/public/uploads/';
        // Grab the filename from the uploaded file by using basename
        $uploadedFilename = basename($_FILES['file1']['name']);
        // Create the saved filename using the file's original name and our upload directory
        $saved_filename = $upload_dir . $uploadedFilename;
        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);

        $textfile = $saved_filename;
        $newfile = getfile($textfile);
        $newitems = array_merge($newfile, $newitems);

        save_file($filename, $newitems);

    } 
    else 
    {
        $errorMessage = "Not a valid file. Please use only a plain text file";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Todo PHP</title>
    <link rel="stylesheet" href="/css/stylesheet.css">
</head>
<body>
    <h3 class="main-header">My TODO List</h3>
    
    <? if(!empty($errorMessage)) : ?>
        <?= $errorMessage; ?>
    <? endif; ?>

    <h3 class="header">Please add an item to do the todo list!</h3>
    <form class="form" method="POST" action="/todo_list.php">
        <p>
<!--             <label class="label"for="todoitem">Add Todo Item</label>
 -->            <input class="input"id="todoitem" name="todoitem" type="text" placeholder="Enter Your Item">
        </p>
            <input type="submit" value="Submit">
        </p>
    </form>

    <h3 class="header">Upload File</h3>
    <form class="form" method="POST" enctype="multipart/form-data">
        <p>
            <label class="label" for="file1">File to upload: </label>
            <input class="input" type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

     <ul>
        <? foreach($newitems as $index => $items) : ?>
        <li id="items"><?= htmlspecialchars(strip_tags($items)); ?><a href='?removeitem=$index'> Mark Complete</a></li>
        <? endforeach; ?>
    </ul>

</body>
</html>