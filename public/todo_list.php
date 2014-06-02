<?php

$filename = 'list.txt';
$newitems = getfile($filename);
$errorMessage = '';

function savefile($savefilepath, $array) 
{
    $filename = $savefilepath;
    if (is_writable($filename)) 
    {
        $handle = fopen($filename, 'w');
        foreach($array as $item) 
        {
            fwrite($handle, PHP_EOL . $item);
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
    }
    return $contents;
} 

// check if we need to remove an item from the list
if (isset($_GET['removeitem'])) 
{
    $removeindex = $_GET['removeitem'];
    unset($newitems[$removeindex]);
    savefile($filename, $newitems);
}

// do we need to add a new item?
if (!empty($_POST['todoitem'])) 
{
    array_push($newitems, $_POST['todoitem']);
    savefile($filename, $newitems);
}

// Verify there were uploaded files and no errors
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

        savefile($filename, $newitems);

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
</head>
<body>
    <h3>My TODO List</h3>
    
    <? if(!empty($errorMessage)) : ?>
        <?= $errorMessage; ?>
    <? endif; ?>

    <ul>
    <? foreach($newitems as $index => $items) : ?>
    <li><?= $items; ?><a href='?removeitem=$index'> Mark Complete</a></li>
    <? endforeach; ?>
    </ul>

    <h3>Please add an item to do the todo list!</h3>
    <form method="POST" action="/todo_list.php">
        <p>
            <label for="todoitem">Add Todo Item</label>
            <input id="todoitem" name="todoitem" type="text" placeholder="Enter Your Item">
        </p>
            <input type="submit" value="Submit">
        </p>
    </form>

    <h3>Upload File</h3>
    <form method="POST" enctype="multipart/form-data">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

</body>
</html>