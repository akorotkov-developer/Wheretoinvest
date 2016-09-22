<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы");
?>

<a href="#add_review" class="reviews-add js-open-review-form">Добавить отзыв</a>

<div id="add_review" style="display: none;">
	<?$APPLICATION->IncludeComponent(
		"bitrix:iblock.element.add.form",
		"add_review",
		array(
			"SEF_MODE" => "Y",
			"IBLOCK_TYPE" => "reviews",
			"IBLOCK_ID" => "9",
			"PROPERTY_CODES" => array(
				0 => "1",
				1 => "2",
				2 => "NAME",
				3 => "PREVIEW_TEXT",
			),
			"PROPERTY_CODES_REQUIRED" => array(
				0 => "2",
				1 => "NAME",
			),
			"GROUPS" => array(
				0 => "2",
			),
			"STATUS_NEW" => "NEW",
			"STATUS" => "INACTIVE",
			"LIST_URL" => "",
			"ELEMENT_ASSOC" => "CREATED_BY",
			"ELEMENT_ASSOC_PROPERTY" => "1",
			"MAX_USER_ENTRIES" => "100000",
			"MAX_LEVELS" => "100000",
			"LEVEL_LAST" => "Y",
			"USE_CAPTCHA" => "Y",
			"USER_MESSAGE_EDIT" => "",
			"USER_MESSAGE_ADD" => "",
			"DEFAULT_INPUT_SIZE" => "30",
			"RESIZE_IMAGES" => "Y",
			"MAX_FILE_SIZE" => "0",
			"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
			"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
			"CUSTOM_TITLE_NAME" => "",
			"CUSTOM_TITLE_TAGS" => "",
			"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
			"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
			"CUSTOM_TITLE_IBLOCK_SECTION" => "",
			"CUSTOM_TITLE_PREVIEW_TEXT" => "",
			"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
			"CUSTOM_TITLE_DETAIL_TEXT" => "",
			"CUSTOM_TITLE_DETAIL_PICTURE" => "",
			"SEF_FOLDER" => "/",
			"COMPONENT_TEMPLATE" => "add_review"
		),
		false
	);?>
</div>

<?if (isset($_GET["strIMessage"]) && !empty($_GET["strIMessage"])) :?>
	<div id="reviews-modal">
		<div id="reviews-modal_notification">
			<span id="reviews-modal_notification-text">
				Спасибо, что делаете нас лучше!<br />
				Ваш отзыв скоро будет опубликован.
			</span>
		</div>
		<div id="reviews-modal_close">
			<a id="reviews-modal_close-button" href="/feedback/">OK</a>
		</div>
	</div>
	<div id="reviews-overlay"></div>
<?endif;?>

<?$APPLICATION->IncludeComponent("bitrix:news.list","feedback_reviews",Array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_MODE" => "Y",
		"IBLOCK_TYPE" => "reviews",
		"IBLOCK_ID" => "9",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Отзывы",
		"PAGER_SHOW_ALWAYS" => "Т",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "Y",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"PAGER_BASE_LINK" => "",
		"PAGER_PARAMS_NAME" => "arrPager",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"FIELD_CODE" => Array(
			0 => "ID",
			1 => "DATE_CREATE",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "REVIEW_AUTHOR_NAME",
			1 => "REVIEW_RATING",
			2 => "",
		)
	)
);?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>