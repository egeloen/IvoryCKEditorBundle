Disable auto inline
===================

By default, CKEditor enables the auto inline feature meaning that any ``contenteditable`` attribute sets to ``true``
will be converted to CKEditor instance automatically. If you want to disable it, you can do it in your configuration or
in your widget:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ivory_ck_editor:
            auto_inline: false

    .. code-block:: php

        $builder->add('field', 'ckeditor', array('auto_inline' => false));

.. note::

    This option will only disable the CKEditor auto inline feature not the browser one if it supports it.
