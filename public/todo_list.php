<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
</head>
<body>
	<?php
		
		var_dump($_GET);
		var_dump($_POST);

		// load the file
		$items = ["Take out trash", "Go to the grocery store", "write a better todo list"];
		$filename = "data/list.txt";
		$new_items = open_file($filename);

/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
// 		                    ** FUNCTIONS BELOW ** 
		
		function open_file($filename) 
		{
		    $handle = fopen($filename, "r");
		    $contents = fread($handle, filesize($filename));
		    $contents_array = explode("\n", $contents);
		    fclose($handle);
		    return $contents_array;
		}

		function save_file($filename, $data_to_save)
		{
            $handle = fopen($filename, 'w');
            $contents = implode("\n", $data_to_save);
            fwrite($handle, $contents);
            fclose($handle);
		}

		if(!empty($_POST))
		{
			$new_items[] = $_POST['new_item'];	
		}

	?>

	<h3>TODO List</h3>

	<?php	

		foreach($items as $item)
		{
			echo "<ul><li>$item</li></ul>";
		}
		foreach($new_items as $value)
		{	
			echo "<ul><li>$value</li></ul>";
		}	
		save_file($filename, $new_items);

	?>

	<form method="POST">
		<label for="new_item">Add TODO Item</label><br>
		<input type="text" name="new_item" id="new_item" placeholder="Add New Item">
	
		<button type="submit" name="submit">Submit</button>	
	</form>

</body>
</html>

<!-- var_dump($_GET);

// sample_post.php?action=remove&index=0 -->













