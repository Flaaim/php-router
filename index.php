<?php

require_once "router.php";
Router::add('/test/(test1)', function($test1){
    echo "test".$test1;
}, 'post'
);

Router::run();
