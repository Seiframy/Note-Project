<?php
$data = file_get_contents("php://input");
file_put_contents("testlog.txt", $data);
echo json_encode(["received" => $data]);
?>
