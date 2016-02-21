Use inline editing
==================

By default, the bundle uses a `Classic Editing`_ which relies on
``CKEDITOR.replace``. If you want to use the `Inline Editing`_ which relies on
``CKEDITOR.inline``, you can configure it in your configuration or in your
widget:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ivory_ck_editor:
            inline: true

    .. code-block:: php

        $builder->add('field', 'ckeditor', array('inline' => true));

.. _`Classic Editing`: http://docs.ckeditor.com/#!/guide/dev_framed
.. _`Inline Editing`: http://docs.ckeditor.com/#!/guide/dev_inline
