Skin support
============

Install your Skin
-----------------

First of all, you need to download and extract your skin in the web directory.
For that, you have two possibilities:

#. Directly put it in the web directory (``/web/ckeditor/`` for example).
#. Put it in the ``/Resources/public/`` directory of any of your bundles and
   install the assets.

Register your Skin
------------------

Then, to use your skin, just need to register it in your configuration or in
your widget:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ivory_ck_editor:
            default_config: my_config
            configs:
                my_config:
                    skin: "skin_name,ckeditor/skins/skin_name/"

    .. code-block:: php

        $builder->add('field', 'ckeditor', array(
            'config' => array('skin' => 'skin_name,ckeditor/skins/skin_name/'),
        ));
