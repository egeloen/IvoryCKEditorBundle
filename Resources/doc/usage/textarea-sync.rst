Synchronize the textarea
========================

When the textarea is transformed into a CKEditor widget, the textarea value is
no more populated except when the form is submitted. Then, it leads to issues
when you try to serialize form or you try to rely on the textarea value in
javascript. To automatically synchronize the textarea value, you can do it in
your configuration or in your widget:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ivory_ck_editor:
            input_sync: true

    .. code-block:: php

        $builder->add('field', 'ckeditor', array('input_sync' => true));
