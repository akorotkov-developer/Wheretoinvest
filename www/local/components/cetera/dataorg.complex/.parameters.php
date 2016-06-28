<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	
	"PARAMETERS" => array(



		"TYPEPAGE" => array(

			"NAME" => "Тип контента",
			"TYPE" => "LIST",
			"VALUES"=>array(
				1=> "Данные партнера",
				2=> "Реквизиты",
				3=> "Предложения",
				4=> "Надежность"

			),
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
