<?php
include "Libs/init.php";
$data = BackEnd::GetCatering();
foreach ($data['widget_list'] as $item) {
    echo BackEnd::AddToDb($item['data']['token'],$item['data']['title'],$item['data']['district'])."<br>";
}