<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Twig;

use Ivory\CKEditorBundle\Templating\CKEditorHelper;

/**
 * CKEditorExtension
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class CKEditorExtension extends \Twig_Extension
{
    /** @var \Ivory\CKEditorBundle\Templating\CKEditorHelper */
    protected $helper;

    /**
     * Creates a CKEditor extension.
     *
     * @param \Ivory\CKEditorBundle\Templating\CKEditorHelper $helper The CKEditor helper.
     */
    public function __construct(CKEditorHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $options = array('is_safe' => array('html'));

        return array(
            new \Twig_SimpleFunction('ckeditor_is_loaded', array($this, 'isLoaded'), $options),
            new \Twig_SimpleFunction('ckeditor_base_path', array($this, 'renderBasePath'), $options),
            new \Twig_SimpleFunction('ckeditor_js_path', array($this, 'renderJsPath'), $options),
            new \Twig_SimpleFunction('ckeditor_replace', array($this, 'renderReplace'), $options),
            new \Twig_SimpleFunction('ckeditor_inline', array($this, 'renderInline'), $options),
            new \Twig_SimpleFunction('ckeditor_destroy', array($this, 'renderDestroy'), $options),
            new \Twig_SimpleFunction('ckeditor_plugin', array($this, 'renderPlugin'), $options),
            new \Twig_SimpleFunction('ckeditor_styles_set', array($this, 'renderStylesSet'), $options),
            new \Twig_SimpleFunction('ckeditor_template', array($this, 'renderTemplate'), $options),
        );
    }

    /**
     * Checks if CKEditor is loaded.
     *
     * @return boolean TRUE if CKEditor is loaded else FALSE.
     */
    public function isLoaded()
    {
        return $this->helper->isLoaded();
    }

    /**
     * Renders the base path.
     *
     * @param string $basePath The base path.
     *
     * @return string The rendered base path.
     */
    public function renderBasePath($basePath)
    {
        return $this->helper->renderBasePath($basePath);
    }

    /**
     * Renders the js path.
     *
     * @param string $jsPath The js path.
     *
     * @return string The rendered js path.
     */
    public function renderJsPath($jsPath)
    {
        return $this->helper->renderJsPath($jsPath);
    }

    /**
     * Renders the replace.
     *
     * @param string $id     The identifier.
     * @param array  $config The config.
     *
     * @return string The rendered replace.
     */
    public function renderReplace($id, array $config)
    {
        return $this->helper->renderReplace($id, $config);
    }

    /**
     * Renders the inline command.
     *
     * @param string $id     The identifier.
     * @param array  $config The config.
     *
     * @return string The rendered inline command.
     */
    public function renderInline($id, array $config)
    {
        return $this->helper->renderInline($id, $config);
    }

    /**
     * Renders the destroy.
     *
     * @param string $id The identifier.
     *
     * @return string The rendered destroy.
     */
    public function renderDestroy($id)
    {
        return $this->helper->renderDestroy($id);
    }

    /**
     * Renders a plugin.
     *
     * @param string $name   The name.
     * @param array  $plugin The plugin.
     *
     * @return string The rendered plugin.
     */
    public function renderPlugin($name, array $plugin)
    {
        return $this->helper->renderPlugin($name, $plugin);
    }

    /**
     * Renders a styles set.
     *
     * @param string $name      The name
     * @param array  $stylesSet The style set.
     *
     * @return string The rendered style set.
     */
    public function renderStylesSet($name, array $stylesSet)
    {
        return $this->helper->renderStylesSet($name, $stylesSet);
    }

    /**
     * Renders a template.
     *
     * @param string $name     The template name.
     * @param array  $template The template.
     *
     * @return string The rendered template.
     */
    public function renderTemplate($name, array $template)
    {
        return $this->helper->renderTemplate($name, $template);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->helper->getName();
    }
}
