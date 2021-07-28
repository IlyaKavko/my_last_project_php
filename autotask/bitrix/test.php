<?php

$date = "2021-06-09T18:00:00+03:00"; // Исходная дата
$date_sec = strtotime($date);

echo (int)$date_sec;