<!DOCTYPE html>
<html>

<?php
	var_dump($_GET);
	var_dump($_POST);	
?>
<head>
	<title>TODO List</title>
</head>
<body>
	<h3>TODO List</h3>	
	<?php

		$items = ["Take out trash", "Go to the grocery store", "write a better todo list"];
		foreach($items as $index => $value)
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
