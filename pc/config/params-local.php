<?php
return [
    'img_path'=>'http://'.$_SERVER["HTTP_HOST"].'/'.explode('/',$_SERVER["REQUEST_URI"])[1], //不是‘/’结尾的
    'base_file'=>explode('/',$_SERVER["REQUEST_URI"])[1],
    'base_url'=>'http://'.$_SERVER["HTTP_HOST"].'/',
    'pwd_pre'=>'parking_pre_',
    'adminEmail' => 'admin@example.com',
    'intro'=>'Hello!欢迎进入《鲜橙置换》后台管理系统',
];
