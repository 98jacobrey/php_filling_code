<!DOCTYPE html>
<head>
</head>
<body>
    #This writes a new element for each row in a database
    <?php
        $connection = new mysqli("localhost", "root", "password", "database_name");
        $query = mysqli_query($connection , "SELECT * FROM `table_name` ORDER BY `date_posted` DESC");

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                echo '<div>'.$row['column_data'].'</div>';
            }
        };
    ?>

    #This fills a page based on the web id
    <?php
    $connection = new mysqli("localhost", "root", "password", "database_name");
    
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    $query = 'SELECT * FROM "table_name" WHERE `id` = ".$id" LIMIT 1 ';
    $result = mysqli_query($connection, $query)

    ?>
    <?php
    $query = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $qs = array();
    foreach ($query as $key=>$value) $qs[$key] = urldecode(htmlspecialchars($value));
    array_shift($qs); #clear blank first element
    
    # Parse URLs with the following format:
    # /search/{$field1}/{$value1}/{$field2}/{$value2}/.../{$fieldN}/{$valueN}
    switch ($val = array_shift($qs)) {
        case "search":
            $params = array(); # query array
            $field = true; # switch between field or value
            while (!is_null($val=array_shift($qs))) {
                if ($field) $params[-1] = $val;
                else $params[$params[-1]] = urldecode($val);
                $field = !$field;
            };
        
            unset($params[-1]);
            $result = $app->Property->search($params);
            header('Content-type: application/json');
            echo json_encode($result, JSON_FORCE_OBJECT);
            exit; #don't load template after exporting JSON object
            break;
    }
        ?>
</body>