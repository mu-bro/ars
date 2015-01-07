<?php

$homepage = file_get_contents($_GET['url']);
//echo json_encode($homepage);
echo $homepage;
die;
?>