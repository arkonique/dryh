<?php
$f=json_encode($_GET);
file_put_contents('player.JSON',$f);
?>
