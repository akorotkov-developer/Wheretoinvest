<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	
	"PARAMETERS" => array(
		"HIGHLOAD_ID" => array(

			"NAME" => GetMessage("HIGHLOAD_ID"),
			"TYPE" => "STRING",



		),

		"METHOD" => array(

			"NAME" => "Вид вложения(для редактирования)",
			"TYPE" => "LIST",
			"VALUES"=>array(
				3=> "Банковские вклады",

				6=> "Займы",


			)



		),

	)
);
?>
