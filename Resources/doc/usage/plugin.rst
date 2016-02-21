Plugin support
==============

The bundle offers you the ability to manage extra plugins. To understand how it
works, you will enable the `Wordcount`_ plugin for our CKEditor widget.

Install the Plugin
------------------

First, you need to download and extract it in the web directory. For that, you
have two possibilities:

#. Directly put the plugin in the web directory (``/web/ckeditor/plugins/`` for
   example).
#. Put the plugin in the ``/Resources/public/`` directory of any of your bundles.

Register the Plugin
-------------------

In order to load it, you need to specify its location. For that, you can do it
in your configuration or in your widget:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ivory_ck_editor:
            default_config: my_config
            config:
                extraPlugins: "wordcount"
            plugins:
                wordcount:
                    path:     "/bundles/mybundle/wordcount/"
                    filename: "plugin.js"

    .. code-block:: php

        $builder->add('field', 'ckeditor', array(
            'config' => array(
                'extraPlugins' => 'wordcount',
            ),
            'plugins' => array(
                'wordcount' => array(
                    'path'     => '/bundles/mybundle/wordcount/',
                    'filename' => 'plugin.js',
                ),
            ),
        ));

.. _`Wordcount`: http://ckeditor.com/addon/wordcount
