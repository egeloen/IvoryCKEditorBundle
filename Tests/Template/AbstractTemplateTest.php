<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Tests\Template;

use Ivory\CKEditorBundle\Templating\CKEditorHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract template test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractTemplateTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\CKEditorBundle\Templating\CKEditorHelper */
    protected $helper;

    /** @var \Symfony\Component\Templating\Helper\CoreAssetsHelper|\PHPUnit_Framework_MockObject_MockObject */
    private $assetsHelperMock;

    /** @var \Symfony\Component\Routing\RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $routerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->routerMock = $this->getMock('Symfony\Component\Routing\RouterInterface');

        $this->assetsHelperMock = $this->getMockBuilder('Symfony\Component\Templating\Helper\CoreAssetsHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assetsHelperMock
            ->expects($this->any())
            ->method('getUrl')
            ->will($this->returnArgument(0));

        $this->helper = new CKEditorHelper($this->routerMock, $this->assetsHelperMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->routerMock);
        unset($this->assetsHelperMock);
        unset($this->helper);
    }

    public function testRenderWithSimpleWidget()
    {
        $expected = <<<EOF
<textarea >&lt;p&gt;value&lt;/p&gt;</textarea>
<script type="text/javascript">
var CKEDITOR_BASEPATH = "base_path";
</script>
<script type="text/javascript" src="js_path"></script>
<script type="text/javascript">
if (CKEDITOR.instances["id"]) {
delete CKEDITOR.instances["id"];
}
CKEDITOR.replace("id", []);
</script>

EOF;

        $this->assertTemplate($expected, $this->getContext());
    }

    public function testRenderWithFullWidget()
    {
        $context = array(
            'inline' => true,
            'jquery' => true,
            'input_sync' => true,
            'config' => array('foo' => 'bar'),
            'plugins' => array(
                'foo' => array('path' => 'path', 'filename' => 'filename'),
            ),
            'styles' => array(
                'default' => array(
                    array('name' => 'Blue Title', 'element' => 'h2', 'styles' => array('color' => 'Blue')),
                ),
            ),
            'templates' => array(
                'foo' => array(
                    'imagesPath' => 'path',
                    'templates' => array(
                        array(
                            'title' => 'My Template',
                            'html' => '<h1>Template</h1>',
                        ),
                    ),
                ),
            ),
        );

        $expected = <<<EOF
<textarea >&lt;p&gt;value&lt;/p&gt;</textarea>
<script type="text/javascript">
var CKEDITOR_BASEPATH = "base_path";
</script>
<script type="text/javascript" src="js_path"></script>
<script type="text/javascript" src="jquery_path"></script>
<script type="text/javascript">
if (CKEDITOR.instances["id"]) {
delete CKEDITOR.instances["id"];
}
CKEDITOR.plugins.addExternal("foo", "path", "filename");
if (CKEDITOR.stylesSet.get("default") === null) { CKEDITOR.stylesSet.add("default", [{"name":"Blue Title","element":"h2","styles":{"color":"Blue"}}]); }
CKEDITOR.addTemplates("foo", {"imagesPath":"path","templates":[{"title":"My Template","html":"<h1>Template<\/h1>"}]});
var ivory_ckeditor_id = CKEDITOR.inline("id", {"foo":"bar"});
ivory_ckeditor_id.on('change', function(){ ivory_ckeditor_id.updateElement(); });
</script>

EOF;

        $this->assertTemplate($expected, array_merge($this->getContext(), $context));
    }

    public function testRenderWithNotAutoloadedWidget()
    {
        $expected = <<<EOF
<textarea >&lt;p&gt;value&lt;/p&gt;</textarea>
<script type="text/javascript">
if (CKEDITOR.instances["id"]) {
delete CKEDITOR.instances["id"];
}
CKEDITOR.replace("id", []);
</script>

EOF;

        $this->assertTemplate($expected, array_merge($this->getContext(), array('autoload' => false)));
    }

    public function testRenderWithDisableWidget()
    {
        $expected = <<<EOF
<textarea >&lt;p&gt;value&lt;/p&gt;</textarea>

EOF;

        $this->assertTemplate($expected, array_merge($this->getContext(), array('enable' => false)));
    }

    /**
     * Renders a template.
     *
     * @param array $context The template context.
     *
     * @return string The template output.
     */
    abstract protected function renderTemplate(array $context = array());

    /**
     * Gets the context.
     *
     * @return array The context.
     */
    private function getContext()
    {
        return array(
            'form'        => $this->getMock('Symfony\Component\Form\FormView'),
            'id'          => 'id',
            'value'       => '<p>value</p>',
            'enable'      => true,
            'autoload'    => true,
            'inline'      => false,
            'jquery'      => false,
            'input_sync'  => false,
            'base_path'   => 'base_path',
            'js_path'     => 'js_path',
            'jquery_path' => 'jquery_path',
            'config'      => array(),
            'plugins'     => array(),
            'styles'      => array(),
            'templates'   => array(),
        );
    }

    /**
     * Asserts a template.
     *
     * @param string $expected The expected template.
     * @param array  $context  The context.
     */
    private function assertTemplate($expected, array $context)
    {
        $this->assertSame($this->normalizeOutput($expected), $this->normalizeOutput($this->renderTemplate($context)));
    }

    /**
     * Normalizes the output by removing the heading whitespaces.
     *
     * @param string $output The output.
     *
     * @return string The normalized output.
     */
    private function normalizeOutput($output)
    {
        return str_replace(PHP_EOL, '', str_replace(' ', '', $output));
    }
}
