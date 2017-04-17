<html>
<head>
    <meta charset="utf-8"/>
    <title><?= $title ?></title>
    <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
</head>
<body>
<h1>Slim</h1>
<div>home.php</div>
<?php
foreach ($rows as $row):
    echo '<div>'.$row[0].'</div>';
endforeach
?>
</body>
</html>