<textarea <?php echo $view['form']->renderBlock('attributes') ?>><?php echo $value ?></textarea>

<script type="text/javascript">
    var CKEDITOR_BASEPATH = '<?php echo $view['ivory_ck_editor.trim_asset_version']->trim($view['assets']->getUrl('bundles/ivoryckeditor/')) ?>';
</script>

<script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/ivoryckeditor/ckeditor.js') ?>"></script>

<script type="text/javascript">
    var instance = CKEDITOR.instances['<?php echo $id ?>'];
    if (instance) {
        instance.destroy(true);
    }

    <?php foreach ($plugins as $pluginName => $plugin) : ?>
        CKEDITOR.plugins.addExternal('<?php echo $pluginName ?>', '<?php echo $view['ivory_ck_editor.trim_asset_version']->trim($view['assets']->getUrl($plugin['path'])) ?>', '<?php echo $plugin['filename'] ?>');
    <?php endforeach; ?>

    CKEDITOR.replace("<?php echo $id ?>"<?php if (!empty($config)) : ?>, <?php echo json_encode($config) ?><?php endif; ?>);
</script>
