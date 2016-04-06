<?php

require_once 'degoo.class.php';
try{
    $degoo = new Degoo('email@email.com', 'XXXXXXX' );
    var_dump($degoo->register(9));
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}
