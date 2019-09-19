<?php
$to = 'al.tolerant@gmail.com';
$subject = 'test';
$message = 'hello';
$headers = 'From: info@valuta.kz';

mail($to, $subject, $message, $headers);