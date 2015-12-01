<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Tests\Form\Type;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Forms;

/**
 * CKEditor type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class CKEditorTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\Form\FormFactoryInterface */
    private $factory;

    /** @var \Ivory\CKEditorBundle\Form\Type\CKEditorType */
    private $ckEditorType;

    /** @var \Ivory\CKEditorBundle\Model\ConfigManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $configManagerMock;

    /** @var string */
    private $formType;

    /** @var \Ivory\CKEditorBundle\Model\PluginManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $pluginManagerMock;

    /** @var \Ivory\CKEditorBundle\Model\StylesSetManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $stylesSetManagerMock;

    /** @var \Ivory\CKEditorBundle\Model\TemplateManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $templateManagerMock;

    /**
     * {@inheritdooc}
     */
    protected function setUp()
    {
        $this->configManagerMock = $this->getMock('Ivory\CKEditorBundle\Model\ConfigManagerInterface');
        $this->pluginManagerMock = $this->getMock('Ivory\CKEditorBundle\Model\PluginManagerInterface');
        $this->stylesSetManagerMock = $this->getMock('Ivory\CKEditorBundle\Model\StylesSetManagerInterface');
        $this->templateManagerMock = $this->getMock('Ivory\CKEditorBundle\Model\TemplateManagerInterface');

        $this->ckEditorType = new CKEditorType(
            $this->configManagerMock,
            $this->pluginManagerMock,
            $this->stylesSetManagerMock,
            $this->templateManagerMock
        );

        $this->factory = Forms::createFormFactoryBuilder()
            ->addType($this->ckEditorType)
            ->getFormFactory();

        // The form type requires a FQCN in Symfony 3.0 (feature added in Symfony 2.8)
        $preferFQCN = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix');
        $this->formType = $preferFQCN ? 'Ivory\CKEditorBundle\Form\Type\CKEditorType' : 'ckeditor';
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->configManagerMock);
        unset($this->pluginManagerMock);
        unset($this->stylesSetManagerMock);
        unset($this->templateManagerMock);
        unset($this->ckEditorType);
        unset($this->factory);
    }

    public function testInitialState()
    {
        $this->assertTrue($this->ckEditorType->isEnable());
        $this->assertTrue($this->ckEditorType->isAutoload());
        $this->assertTrue($this->ckEditorType->isAutoInline());
        $this->assertFalse($this->ckEditorType->isInline());
        $this->assertFalse($this->ckEditorType->useJquery());
        $this->assertFalse($this->ckEditorType->isInputSync());
        $this->assertSame('bundles/ivoryckeditor/', $this->ckEditorType->getBasePath());
        $this->assertSame('bundles/ivoryckeditor/ckeditor.js', $this->ckEditorType->getJsPath());
        $this->assertSame('bundles/ivoryckeditor/adapters/jquery.js', $this->ckEditorType->getJqueryPath());
        $this->assertSame($this->configManagerMock, $this->ckEditorType->getConfigManager());
        $this->assertSame($this->pluginManagerMock, $this->ckEditorType->getPluginManager());
        $this->assertSame($this->stylesSetManagerMock, $this->ckEditorType->getStylesSetManager());
        $this->assertSame($this->templateManagerMock, $this->ckEditorType->getTemplateManager());
    }

    public function testEnableWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('enable', $view->vars);
        $this->assertTrue($view->vars['enable']);
    }

    public function testEnableWithConfiguredValue()
    {
        $this->ckEditorType->isEnable(false);

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('enable', $view->vars);
        $this->assertFalse($view->vars['enable']);
    }

    public function testEnableWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('enable' => false));
        $view = $form->createView();

        $this->assertArrayHasKey('enable', $view->vars);
        $this->assertFalse($view->vars['enable']);
    }

    public function testAutoloadWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('autoload', $view->vars);
        $this->assertTrue($view->vars['autoload']);
    }

    public function testAutoloadWithConfiguredValue()
    {
        $this->ckEditorType->isAutoload(false);

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('autoload', $view->vars);
        $this->assertFalse($view->vars['autoload']);
    }

    public function testAutoloadWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('autoload' => false));
        $view = $form->createView();

        $this->assertArrayHasKey('autoload', $view->vars);
        $this->assertFalse($view->vars['autoload']);
    }

    public function testAutoInlineWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('auto_inline', $view->vars);
        $this->assertTrue($view->vars['auto_inline']);
    }

    public function testAutoInlineWithConfiguredValue()
    {
        $this->ckEditorType->isAutoInline(false);

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('auto_inline', $view->vars);
        $this->assertFalse($view->vars['auto_inline']);
    }

    public function testAutoInlineWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('auto_inline' => false));
        $view = $form->createView();

        $this->assertArrayHasKey('auto_inline', $view->vars);
        $this->assertFalse($view->vars['auto_inline']);
    }

    public function testInlineWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('inline', $view->vars);
        $this->assertFalse($view->vars['inline']);
    }

    public function testInlineWithConfiguredValue()
    {
        $this->ckEditorType->isInline(true);

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('inline', $view->vars);
        $this->assertTrue($view->vars['inline']);
    }

    public function testInlineWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('inline' => true));
        $view = $form->createView();

        $this->assertArrayHasKey('inline', $view->vars);
        $this->assertTrue($view->vars['inline']);
    }

    public function testJqueryWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('jquery', $view->vars);
        $this->assertFalse($view->vars['jquery']);
    }

    public function testJqueryWithConfiguredValue()
    {
        $this->ckEditorType->useJquery(true);

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('jquery', $view->vars);
        $this->assertTrue($view->vars['jquery']);
    }

    public function testJqueryWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('jquery' => true));
        $view = $form->createView();

        $this->assertArrayHasKey('jquery', $view->vars);
        $this->assertTrue($view->vars['jquery']);
    }

    public function testInputSyncWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('input_sync', $view->vars);
        $this->assertFalse($view->vars['input_sync']);
    }

    public function testInputSyncWithConfiguredValue()
    {
        $this->ckEditorType->isInputSync(true);

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('input_sync', $view->vars);
        $this->assertTrue($view->vars['input_sync']);
    }

    public function testInputSyncWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('input_sync' => true));
        $view = $form->createView();

        $this->assertArrayHasKey('input_sync', $view->vars);
        $this->assertTrue($view->vars['input_sync']);
    }

    public function testBaseAndJsPathWithDefaultValues()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('base_path', $view->vars);
        $this->assertSame('bundles/ivoryckeditor/', $view->vars['base_path']);

        $this->assertArrayHasKey('js_path', $view->vars);
        $this->assertSame('bundles/ivoryckeditor/ckeditor.js', $view->vars['js_path']);
    }

    public function testBaseAndJsPathWithConfiguredValues()
    {
        $this->ckEditorType->setBasePath('foo/base/');
        $this->ckEditorType->setJsPath('foo/ckeditor.js');
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('base_path', $view->vars);
        $this->assertSame('foo/base/', $view->vars['base_path']);

        $this->assertArrayHasKey('js_path', $view->vars);
        $this->assertSame('foo/ckeditor.js', $view->vars['js_path']);
    }

    public function testBaseAndJsPathWithExplicitValues()
    {
        $form = $this->factory->create(
            $this->formType,
            null,
            array(
                'base_path' => 'foo/',
                'js_path'   => 'foo/ckeditor.js',
            )
        );

        $view = $form->createView();

        $this->assertArrayHasKey('base_path', $view->vars);
        $this->assertSame('foo/', $view->vars['base_path']);

        $this->assertArrayHasKey('js_path', $view->vars);
        $this->assertSame('foo/ckeditor.js', $view->vars['js_path']);
    }

    public function testJqueryPathWithDefaultValue()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('jquery_path', $view->vars);
        $this->assertSame('bundles/ivoryckeditor/adapters/jquery.js', $view->vars['jquery_path']);
    }

    public function testJqueryPathWithConfiguredValue()
    {
        $this->ckEditorType->setJqueryPath('foo/jquery.js');
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('jquery_path', $view->vars);
        $this->assertSame('foo/jquery.js', $view->vars['jquery_path']);
    }

    public function testJqueryPathWithExplicitValue()
    {
        $form = $this->factory->create($this->formType, null, array('jquery_path' => 'foo/jquery.js'));
        $view = $form->createView();

        $this->assertArrayHasKey('jquery_path', $view->vars);
        $this->assertSame('foo/jquery.js', $view->vars['jquery_path']);
    }

    public function testDefaultConfig()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('config', $view->vars);
        $this->assertEmpty(json_decode($view->vars['config'], true));
    }

    public function testConfigWithExplicitConfig()
    {
        $options = array(
            'config' => array(
                'toolbar' => array('foo' => 'bar'),
                'uiColor' => '#ffffff',
            ),
        );

        $this->configManagerMock
            ->expects($this->once())
            ->method('setConfig')
            ->with($this->anything(), $this->equalTo($options['config']));

        $this->configManagerMock
            ->expects($this->once())
            ->method('getConfig')
            ->with($this->anything())
            ->will($this->returnValue($options['config']));

        $form = $this->factory->create($this->formType, null, $options);
        $view = $form->createView();

        $this->assertArrayHasKey('config', $view->vars);
        $this->assertSame($options['config'], $view->vars['config']);
    }

    public function testConfigWithConfiguredConfig()
    {
        $config = array(
            'toolbar' => 'default',
            'uiColor' => '#ffffff',
        );

        $this->configManagerMock
            ->expects($this->once())
            ->method('mergeConfig')
            ->with($this->equalTo('default'), $this->equalTo(array()));

        $this->configManagerMock
            ->expects($this->once())
            ->method('getConfig')
            ->with('default')
            ->will($this->returnValue($config));

        $form = $this->factory->create($this->formType, null, array('config_name' => 'default'));
        $view = $form->createView();

        $this->assertArrayHasKey('config', $view->vars);
        $this->assertSame($config, $view->vars['config']);
    }

    public function testConfigWithDefaultConfiguredConfig()
    {
        $options = array(
            'toolbar' => array('foo' => 'bar'),
            'uiColor' => '#ffffff',
        );

        $this->configManagerMock
            ->expects($this->once())
            ->method('getDefaultConfig')
            ->will($this->returnValue('config'));

        $this->configManagerMock
            ->expects($this->once())
            ->method('mergeConfig')
            ->with($this->equalTo('config'), $this->equalTo(array()));

        $this->configManagerMock
            ->expects($this->once())
            ->method('getConfig')
            ->with('config')
            ->will($this->returnValue($options));

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('config', $view->vars);
        $this->assertSame($options, $view->vars['config']);
    }

    public function testConfigWithExplicitAndConfiguredConfig()
    {
        $configuredConfig = array(
            'toolbar' => 'default',
            'uiColor' => '#ffffff',
        );

        $explicitConfig = array('uiColor' => '#000000');

        $this->configManagerMock
            ->expects($this->once())
            ->method('mergeConfig')
            ->with($this->equalTo('default'), $this->equalTo($explicitConfig));

        $this->configManagerMock
            ->expects($this->once())
            ->method('getConfig')
            ->with('default')
            ->will($this->returnValue(array_merge($configuredConfig, $explicitConfig)));

        $form = $this->factory->create(
            $this->formType,
            null,
            array('config_name' => 'default', 'config' => $explicitConfig)
        );

        $view = $form->createView();

        $this->assertArrayHasKey('config', $view->vars);
        $this->assertSame(array_merge($configuredConfig, $explicitConfig), $view->vars['config']);
    }

    public function testDefaultPlugins()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('plugins', $view->vars);
        $this->assertEmpty($view->vars['plugins']);
    }

    public function testPluginsWithExplicitPlugins()
    {
        $plugins = array(
            'wordcount' => array(
                'path'     => '/my/path',
                'filename' => 'plugin.js',
            ),
        );

        $this->pluginManagerMock
            ->expects($this->once())
            ->method('setPlugins')
            ->with($this->equalTo($plugins));

        $this->pluginManagerMock
            ->expects($this->once())
            ->method('getPlugins')
            ->will($this->returnValue($plugins));

        $form = $this->factory->create($this->formType, null, array('plugins' => $plugins));

        $view = $form->createView();

        $this->assertArrayHasKey('plugins', $view->vars);
        $this->assertSame($plugins, $view->vars['plugins']);
    }

    public function testPluginsWithConfiguredPlugins()
    {
        $plugins = array(
            'wordcount' => array(
                'path'     => '/my/path',
                'filename' => 'plugin.js',
            ),
        );

        $this->pluginManagerMock
            ->expects($this->once())
            ->method('getPlugins')
            ->will($this->returnValue($plugins));

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertArrayHasKey('plugins', $view->vars);
        $this->assertSame($plugins, $view->vars['plugins']);
    }

    public function testPluginsWithConfiguredAndExplicitPlugins()
    {
        $configuredPlugins = array(
            'wordcount' => array(
                'path'     => '/my/explicit/path',
                'filename' => 'plugin.js',
            ),
        );

        $explicitPlugins = array(
            'autogrow' => array(
                'path'     => '/my/configured/path',
                'filename' => 'plugin.js',
            ),
        );

        $this->pluginManagerMock
            ->expects($this->once())
            ->method('setPlugins')
            ->with($this->equalTo($explicitPlugins));

        $this->pluginManagerMock
            ->expects($this->once())
            ->method('getPlugins')
            ->will($this->returnValue(array_merge($explicitPlugins, $configuredPlugins)));

        $form = $this->factory->create($this->formType, null, array('plugins' => $explicitPlugins));
        $view = $form->createView();

        $this->assertArrayHasKey('plugins', $view->vars);
        $this->assertSame(array_merge($explicitPlugins, $configuredPlugins), $view->vars['plugins']);
    }

    public function testDefaultStylesSet()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertEmpty($view->vars['styles']);
    }

    public function testPluginsWithExplicitStylesSet()
    {
        $stylesSets = array(
            'default' => array(
                array('name' => 'Blue Title', 'element' => 'h2', 'styles' => array('color' => 'Blue')),
                array('name' => 'CSS Style', 'element' => 'span', 'attributes' => array('class' => 'my_style')),
            ),
        );

        $this->stylesSetManagerMock
            ->expects($this->once())
            ->method('setStylesSets')
            ->with($this->equalTo($stylesSets));

        $this->stylesSetManagerMock
            ->expects($this->once())
            ->method('getStylesSets')
            ->will($this->returnValue($stylesSets));

        $form = $this->factory->create($this->formType, null, array('styles' => $stylesSets));

        $view = $form->createView();

        $this->assertSame($stylesSets, $view->vars['styles']);
    }

    public function testPluginsWithConfiguredStylesSets()
    {
        $stylesSets = array(
            'default' => array(
                array('name' => 'Blue Title', 'element' => 'h2', 'styles' => array('color' => 'Blue')),
                array('name' => 'CSS Style', 'element' => 'span', 'attributes' => array('class' => 'my_style')),
            ),
        );

        $this->stylesSetManagerMock
            ->expects($this->once())
            ->method('getStylesSets')
            ->will($this->returnValue($stylesSets));

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertSame($stylesSets, $view->vars['styles']);
    }

    public function testPluginsWithConfiguredAndExplicitStylesSets()
    {
        $configuredStylesSets = array(
            'foo' => array(
                array('name' => 'Blue Title', 'element' => 'h2', 'styles' => array('color' => 'Blue')),
            ),
        );

        $explicitStylesSets = array(
            'bar' => array(
                array('name' => 'CSS Style', 'element' => 'span', 'attributes' => array('class' => 'my_style')),
            ),
        );

        $this->stylesSetManagerMock
            ->expects($this->once())
            ->method('setStylesSets')
            ->with($this->equalTo($explicitStylesSets));

        $this->stylesSetManagerMock
            ->expects($this->once())
            ->method('getStylesSets')
            ->will($this->returnValue(array_merge($explicitStylesSets, $configuredStylesSets)));

        $form = $this->factory->create($this->formType, null, array('styles' => $explicitStylesSets));
        $view = $form->createView();

        $this->assertSame(array_merge($explicitStylesSets, $configuredStylesSets), $view->vars['styles']);
    }

    public function testDefaultTemplates()
    {
        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertEmpty($view->vars['templates']);
    }

    public function testTemplatesWithExplicitTemplates()
    {
        $templates = array(
            'default' => array(
                'imagesPath' => '/my/path',
                'templates'  => array(
                    array(
                        'title' => 'My Template',
                        'html'  => '<h1>Template</h1><p>Type your text here.</p>',
                    ),
                ),
            ),
        );

        $this->templateManagerMock
            ->expects($this->once())
            ->method('setTemplates')
            ->with($this->equalTo($templates));

        $this->templateManagerMock
            ->expects($this->once())
            ->method('getTemplates')
            ->will($this->returnValue($templates));

        $form = $this->factory->create($this->formType, null, array('templates' => $templates));

        $view = $form->createView();

        $this->assertSame($templates, $view->vars['templates']);
    }

    public function testTemplatesWithConfiguredTemplates()
    {
        $templates = array(
            'default' => array(
                'imagesPath' => '/my/path',
                'templates'  => array(
                    array(
                        'title' => 'My Template',
                        'html'  => '<h1>Template</h1><p>Type your text here.</p>',
                    ),
                ),
            ),
        );

        $this->templateManagerMock
            ->expects($this->once())
            ->method('getTemplates')
            ->will($this->returnValue($templates));

        $form = $this->factory->create($this->formType);
        $view = $form->createView();

        $this->assertSame($templates, $view->vars['templates']);
    }

    public function testTemplatesWithConfiguredAndExplicitTemplates()
    {
        $configuredTemplates = array(
            'default' => array(
                'imagesPath' => '/my/path',
                'templates'  => array(
                    array(
                        'title' => 'My Template',
                        'html'  => '<h1>Template</h1><p>Type your text here.</p>',
                    ),
                ),
            ),
        );

        $explicitTemplates = array(
            'extra' => array(
                'templates'  => array(
                    array(
                        'title' => 'My Extra Template',
                        'html'  => '<h2>Template</h2><p>Type your text here.</p>',
                    ),
                ),
            ),
        );

        $this->templateManagerMock
            ->expects($this->once())
            ->method('setTemplates')
            ->with($this->equalTo($explicitTemplates));

        $this->templateManagerMock
            ->expects($this->once())
            ->method('getTemplates')
            ->will($this->returnValue(array_merge($explicitTemplates, $configuredTemplates)));

        $form = $this->factory->create($this->formType, null, array('templates' => $explicitTemplates));
        $view = $form->createView();

        $this->assertSame(array_merge($explicitTemplates, $configuredTemplates), $view->vars['templates']);
    }

    public function testConfiguredDisable()
    {
        $this->ckEditorType->isEnable(false);

        $options = array(
            'config' => array(
                'toolbar' => array('foo' => 'bar'),
                'uiColor' => '#ffffff',
            ),
            'plugins' => array(
                'wordcount' => array(
                    'path'     => '/my/path',
                    'filename' => 'plugin.js',
                ),
            ),
        );

        $form = $this->factory->create($this->formType, null, $options);
        $view = $form->createView();

        $this->assertArrayHasKey('enable', $view->vars);
        $this->assertFalse($view->vars['enable']);

        $this->assertArrayNotHasKey('autoload', $view->vars);
        $this->assertArrayNotHasKey('config', $view->vars);
        $this->assertArrayNotHasKey('plugins', $view->vars);
        $this->assertArrayNotHasKey('stylesheets', $view->vars);
        $this->assertArrayNotHasKey('templates', $view->vars);
    }

    public function testExplicitDisable()
    {
        $options = array(
            'enable' => false,
            'config' => array(
                'toolbar' => array('foo' => 'bar'),
                'uiColor' => '#ffffff',
            ),
            'plugins' => array(
                'wordcount' => array(
                    'path'     => '/my/path',
                    'filename' => 'plugin.js',
                ),
            ),
        );

        $form = $this->factory->create($this->formType, null, $options);
        $view = $form->createView();

        $this->assertArrayHasKey('enable', $view->vars);
        $this->assertFalse($view->vars['enable']);

        $this->assertArrayNotHasKey('autoload', $view->vars);
        $this->assertArrayNotHasKey('config', $view->vars);
        $this->assertArrayNotHasKey('plugins', $view->vars);
        $this->assertArrayNotHasKey('stylesheets', $view->vars);
        $this->assertArrayNotHasKey('templates', $view->vars);
    }
}
