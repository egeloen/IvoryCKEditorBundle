Define reusable configuration
=============================

The CKEditor bundle provides an advanced configuration which can be reused on multiple CKEditor instance. Instead of
duplicate the configuration on each form builder, you can directly configure it once & reuse it all the time. The
bundle allows you to define as many configurations as you want.

All CKEditor configuration options are available here: http://docs.ckeditor.com/#!/api/CKEDITOR.config

Define a configuration
----------------------

.. code-block:: yaml

    # app/config/config.yml
    ivory_ck_editor:
        configs:
            my_config:
                toolbar:                [ [ "Source", "-", "Save" ], "/", [ "Anchor" ], "/", [ "Maximize" ] ]
                uiColor:                "#000000"
                filebrowserUploadRoute: "my_route"
                extraPlugins:           "wordcount"
                # ...

Use a configuration
-------------------

When you have defined a config, you can use it with the ``config_name`` option:

.. code-block:: php

    $builder->add('field', 'ckeditor', array(
        'config_name' => 'my_config',
    ));

Override a configuration
------------------------

If you want to override some parts of the defined config, you can still use the ``config`` option:

.. code-block:: php

    $builder->add('field', 'ckeditor', array(
        'config_name' => 'my_config',
        'config'      => array('uiColor' => '#ffffff'),
    ));

Define default configuration
----------------------------

If you want to define globally your configuration in order to make it used by default without having to use the
``config_name`` option, you can use the ``default_config`` node:

.. code-block:: yaml

    # app/config/config.yml
    ivory_ck_editor:
        default_config: my_config
        configs:
            my_config:
                # ...
