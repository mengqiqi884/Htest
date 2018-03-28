<?php
return [
    'adminEmail' => 'admin@example.com',
    'base_file'=>explode('/',$_SERVER["REQUEST_URI"])[1],
    'base_url'=>'http://'.$_SERVER["HTTP_HOST"].'/',
];
