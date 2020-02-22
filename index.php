<?php
include "Libs/init.php";
if (isset($_POST['Btn_Sub'])){
    $result =  BackEnd::GetLastPosts();
    $data = $result['result']['post_list'];
    foreach ($data as $item => $key) {

        echo($key['title'])." : " .BackEnd::GetNumber($data[0]['token'])."<br>";
    }
}



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="#" method="post">
    <input type="submit" value="Get Last Posts" name="Btn_Sub">
</form>
<a href="Catering.php"><button>تالار و مجالس</button></a>

</body>
</html>
