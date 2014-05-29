<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
</head>
<body>
	<?php
		
		var_dump($_GET);
		var_dump($_POST);
		$items = ["Take out trash", "Go to the grocery store", "write a better todo list"];
		$filename = "data/groceries.txt";

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

		$new_items = open_file($filename);

	
	?>


	<h3>TODO List</h3>

	<?php		
		foreach($items as $index => $value)
		{
			echo "<ul><li>$value</li></ul>";
		}
		foreach($new_items as $index => $value)
		{
			echo "<ul><li>$value</li></ul>";
		}	
	?>

	<form method="POST">
		<label for="new_item">Add TODO Item</label><br>
		<input type="text" name="new_item" id="new_item" placeholder="Add New Item">
	
		<button type="submit" name="submit">Submit</button>
		
	</form>

</body>
</html>
