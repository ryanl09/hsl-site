<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>test</h2>

    <?php 

        require_once($_SERVER['DOCUMENT_ROOT'] . '/TECDB.php');

        $db = new tecdb();

        $query = "SELECT * FROM `stats`";

        $res = $db->query($query)->fetchAll();

        print_r($res);
    
    ?>
</body>
</html>