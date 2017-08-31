<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Scan Url</title>
</head>
<body>
    <?php foreach ($invalidUlrs as $key=>$url):?>
        <p>无效ULR为：<?=$url['url'];?></p>
        <p>响应状态码为：<?=$url['status'];?></p>
    <?php endforeach;?>
</body>
</html>