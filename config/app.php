<?php

$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');  // This will return /testphp if in a subfolder

return [
    'base_url'    => $baseUrl,
    'app_mode'    => 'development',
    // 'app_mode'    => 'production',
    'view_folder' => 'htmls/views',
    'timezone'    => 'Asia/Dhaka'
];
