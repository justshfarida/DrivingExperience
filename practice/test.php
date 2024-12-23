<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
    </style>
</head>
<body>
    <?php
    $currentTS=time();
    echo"<p>The timestamp is $currentTS.
        The current date is ".date("Y-m-d",$currentTS).
    "</p>";
    echo"<p> The current date is ".date("Y-m-d H:i:s")."</p>";
    $givenTS=mktime(0,0,0,12,31,2019);
    echo"<p> The current date is ".date("Y-M-D",$givenTS )."</p>";
    $sentenceTS=strtotime("+1 week", $givenTS);
    echo"<p>A week from the given date is ".date("Y-m-d H:i:s", $sentenceTS)."</p>";
    
    setlocale(LC_TIME, 'fr_FR');
    echo "Current date (French): " . strftime('%A %d %B %Y') . "<br>";
    ?>
</body>
</html>