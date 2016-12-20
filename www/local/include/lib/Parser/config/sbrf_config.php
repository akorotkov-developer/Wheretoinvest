var page = require('webpage').create();

<? if (isset($userAgent)) : ?>
page.settings.userAgent = '<?= $userAgent ?>';
<? endif ?>

<? if (isset($clipOptions)) : ?>
page.clipRect = <?= json_encode($clipOptions) ?>;
<? endif ?>

<? if (isset($timeout)) : ?>
page.settings.resourceTimeout = <?= $timeout ?>;
<? endif ?>

page.open('<?= $url ?>', function (status) {
    if (status !== 'success') {
        console.log('Unable to load the address!');
        phantom.exit(1);
    }

    <? if (isset($includedJsScripts)) : ?>
    <? foreach ($includedJsScripts as $script) : ?>
    page.injectJs('<?= $script ?>');
    <? endforeach ?>
    <? endif ?>

    page.evaluate(function () {
        <? if (isset($backgroundColor)) : ?>
        /* This will set the page background color */
        if (document && document.body) {
            document.body.bgColor = '<?= $backgroundColor ?>';
        }
        <? endif ?>

        <? if (isset($includedJsSnippets)) : ?>
        <? foreach ($includedJsSnippets as $script) : ?>
        <?= $script ?>
        <? endforeach ?>
        <? endif ?>
    });
    phantom.exit();
});
