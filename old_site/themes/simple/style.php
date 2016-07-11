<?php
header('Content-Type: text/css');
$data = file_get_contents(__DIR__.'/css/style.css');

$trans = array(
	'#f04124' => $t->config->primary_color,
	'#008cba' => $t->config->secondary_color,
	'#efefee' => $t->config->body_color,
	'#555556' => $t->config->text_color,
	'#FFFFF0' => $t->config->text_secondary_color,
	'#000001' => $t->config->heading_color,
);

echo strtr($data, $trans);