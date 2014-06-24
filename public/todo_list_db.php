<?php

require_once('php/filestore_db.php');

// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=todo_list', 'daniel', 'letmein');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$errorMessage = '';

function getOffset() {

    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    return ($page - 1) * 4;
}

function getList($dbc) {
    $page = getOffset();
    $stmt = $dbc->prepare('SELECT * FROM todo_items LIMIT :LIMIT OFFSET :OFFSET');

    $stmt->bindValue(':LIMIT', 4, PDO::PARAM_INT);
    $stmt->bindValue(':OFFSET', $page, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $stmt;
}

$count = $dbc->query('SELECT count(*) FROM todo_items')->fetchColumn();

$numPages = ceil($count / 4);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$nextPage = $page + 1;
$prevPage = $page - 1;

//////////////////////////////////////////////////////////////////////////

if(!empty($_POST['todoItem'])) {
    
    $stmt = $dbc->prepare('INSERT INTO todo_items (todo_items) VALUES (:todo_items)');

        $stmt->bindValue(':todo_items', $_POST['todoItem'], PDO::PARAM_STR);

        $stmt->execute();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Todo PHP</title>
    <link rel="stylesheet" href="/css/stylesheet_db.css">

</head>
<body>
    <h3 class="main-header">My TODO List</h3>
    
    <? if(!empty($errorMessage)) : ?>
        <?= $errorMessage; ?>
    <? endif; ?>

    <form class="form" method="POST" action="/todo_list_db.php">
        <label class="addItemHeader" for="todoItem">Add Item</label><br>
        <input id="todoItem" name="todoItem" type="text" placeholder="Enter Your Item">
        <input type="submit" value="Submit">
    </form>

    <table class="table table-hover" border="1px solid black">
            <tr>
                <th>ID</th>
                <th>Item</th>
            </tr>
            <? foreach (getList($dbc) as $key => $items) : ?>
            <tr>
                <? foreach ($items as $item): ?>
                    <td><?= htmlspecialchars(strip_tags($item)); ?></td>
                <? endforeach; ?>
            </tr>
            <? endforeach; ?>
        </table>

</body>
</html>