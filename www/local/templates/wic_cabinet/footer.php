<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

    </div>
    <? if (!empty($left_menu)): ?>
        <div class="column small-12 medium-4 large-3 accord show-for-small-only">
        <br><br>
            <?= $left_menu ?>
        </div>
    <? endif; ?>

    <div class="columns small-12 medium-5 large-4"></div>
    </div>

<?php
require($_SERVER['DOCUMENT_ROOT'] . WIC_TEMPLATE_PATH . '/footer.php');