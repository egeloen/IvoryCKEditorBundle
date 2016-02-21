Fallback to textarea
====================

Sometimes, you don't want to use the CKEditor widget but a simple textarea (e.g
testing purpose). As CKEditor uses an iFrame to render the widget, it can be
difficult to automate something on it. To disable CKEditor and fallback on the
parent widget (textarea), simply disable it in your configuration file or in
your widget:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config_test.yml
        ivory_ck_editor:
            enable: false

    .. code-block:: php

        $builder->add('field', 'ckeditor', array('enable' => false));
