<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Tests\Templating;

use Ivory\CKEditorBundle\Templating\CKEditorHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * CKEditor helper test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class CKEditorHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\CKEditorBundle\Templating\CKEditorHelper */
    protected $helper;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $containerMock;

    /** @var \Symfony\Component\Templating\Helper\CoreAssetsHelper|\PHPUnit_Framework_MockObject_MockObject */
    protected $assetsHelperMock;

    /** @var \Symfony\Component\Routing\RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $routerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->assetsHelperMock = $this->getMockBuilder('Symfony\Component\Templating\Helper\CoreAssetsHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->routerMock = $this->getMock('Symfony\Component\Routing\RouterInterface');

        $this->containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->containerMock
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap(array(
                array(
                    'templating.helper.assets',
                    ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                    $this->assetsHelperMock,
                ),
                array(
                    'router',
                    ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                    $this->routerMock
                ),
            )));

        $this->helper = new CKEditorHelper($this->containerMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->helper);
        unset($this->containerMock);
        unset($this->routerMock);
        unset($this->assetsHelperMock);
    }

    /**
     * Gets the url.
     *
     * @return array The url.
     */
    public function pathProvider()
    {
        return array(
            array('path', 'url', 'url'),
            array('path', 'url?v=2', 'url'),
        );
    }

    /**
     * Gets the urls.
     *
     * @return array The urls.
     */
    public function pathsProvider()
    {
        return array(
            array(array('path'), array('url'), array('url')),
            array(array('path'), array('url?v=2'), array('url')),
            array(array('path1', 'path2'), array('url1', 'url2'), array('url1', 'url2')),
            array(array('path1', 'path2'), array('url1?v=2', 'url2'), array('url1', 'url2')),
            array(array('path1', 'path2'), array('url1', 'url2?v=2'), array('url1', 'url2')),
            array(array('path1', 'path2'), array('url1?v=2', 'url2?v=2'), array('url1', 'url2')),
        );
    }

    /**
     * Gets the filebrowsers keys.
     *
     * @return array The filebrowsers keys.
     */
    public function filebrowserProvider()
    {
        return array(
            array('Browse'),
            array('FlashBrowse'),
            array('ImageBrowse'),
            array('ImageBrowseLink'),
            array('Upload'),
            array('FlashUpload'),
            array('ImageUpload'),
        );
    }

    /**
     * @dataProvider pathProvider
     */
    public function testRenderBasePath($path, $asset, $url)
    {
        $this->assetsHelperMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame($url, $this->helper->renderBasePath($path));
    }

    public function testRenderJsPath()
    {
        $this->assetsHelperMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->helper->renderJsPath('foo'));
    }

    /**
     * @dataProvider pathProvider
     */
    public function testRenderReplaceWithStringContentsCss($path, $asset, $url)
    {
        $this->assetsHelperMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"contentsCss":['.json_encode($url).']});',
            $this->helper->renderReplace('foo', array('contentsCss' => $path))
        );
    }

    /**
     * @dataProvider pathsProvider
     */
    public function testRenderReplaceWithArrayContentsCss(array $paths, array $assets, array $urls)
    {
        foreach (array_keys($paths) as $key) {
            $this->assetsHelperMock
                ->expects($this->at($key))
                ->method('getUrl')
                ->with($this->equalTo($paths[$key]))
                ->will($this->returnValue($assets[$key]));
        }

        $this->assertSame(
            'CKEDITOR.replace("foo", {"contentsCss":'.json_encode($urls).'});',
            $this->helper->renderReplace('foo', array('contentsCss' => $paths))
        );
    }

    /**
     * @dataProvider filebrowserProvider
     */
    public function testRenderReplaceWithFileBrowser($filebrowser)
    {
        $this->routerMock
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('browse_route'),
                $this->equalTo(array('foo' => 'bar')),
                $this->equalTo(true)
            )
            ->will($this->returnValue('browse_url'));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.$filebrowser.'Url":"browse_url"});',
            $this->helper->renderReplace('foo', array(
                'filebrowser'.$filebrowser.'Route'           => 'browse_route',
                'filebrowser'.$filebrowser.'RouteParameters' => array('foo' => 'bar'),
                'filebrowser'.$filebrowser.'RouteAbsolute'   => true,
            ))
        );
    }

    /**
     * @dataProvider filebrowserProvider
     */
    public function testRenderReplaceWithFileBrowserHandler($filebrowser)
    {
        $this->routerMock
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('browse_route'),
                $this->equalTo(array('foo' => 'bar')),
                $this->equalTo(true)
            )
            ->will($this->returnValue('browse_url'));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.$filebrowser.'Url":"browse_url"});',
            $this->helper->renderReplace('foo', array(
                'filebrowser'.$filebrowser.'Handler' => function (RouterInterface $router) {
                    return $router->generate('browse_route', array('foo' => 'bar'), true);
                },
            ))
        );
    }

    public function testRenderReplaceWithProtectedSource()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"protectedSource":[/<\?[\s\S]*?\?>/g,/<%[\s\S]*?%>/g]});',
            $this->helper->renderReplace('foo', array(
                'protectedSource' => array(
                    '/<\?[\s\S]*?\?>/g',
                    '/<%[\s\S]*?%>/g',
                )
            ))
        );
    }

    public function testRenderReplaceWithStylesheetParserSkipSelectors()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"stylesheetParser_skipSelectors":/(^body\.|^caption\.|\.high|^\.)/i});',
            $this->helper->renderReplace('foo', array(
                'stylesheetParser_skipSelectors' => '/(^body\.|^caption\.|\.high|^\.)/i',
            ))
        );
    }

    public function testRenderReplaceWithStylesheetParserValidSelectors()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"stylesheetParser_validSelectors":/\^(p|span)\.\w+/});',
            $this->helper->renderReplace('foo', array(
                'stylesheetParser_validSelectors' => '/\^(p|span)\.\w+/',
            ))
        );
    }

    public function testRenderReplaceWithCKEditorConstants()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"config":{"enterMode":CKEDITOR.ENTER_BR,"shiftEnterMode":CKEDITOR.ENTER_BR}});',
            $this->helper->renderReplace('foo', array(
                'config' => array(
                    'enterMode'      => 'CKEDITOR.ENTER_BR',
                    'shiftEnterMode' => 'CKEDITOR.ENTER_BR',
                ),
            ))
        );
    }

    public function testRenderDestroy()
    {
        $this->assertSame(
            'if (CKEDITOR.instances["foo"]) { delete CKEDITOR.instances["foo"]; }',
            $this->helper->renderDestroy('foo')
        );
    }

    /**
     * @dataProvider pathProvider
     */
    public function testRenderPlugin($path, $asset, $url)
    {
        $this->assetsHelperMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame(
            'CKEDITOR.plugins.addExternal("foo", '.json_encode($url).', "bat");',
            $this->helper->renderPlugin('foo', array('path' => $path, 'filename' => 'bat'))
        );
    }

    public function testRenderStylesSet()
    {
        $this->assertSame(
            'if (CKEDITOR.stylesSet.get("foo") === null) { CKEDITOR.stylesSet.add("foo", {"foo":"bar"}); }',
            $this->helper->renderStylesSet('foo', array('foo' => 'bar'))
        );
    }

    /**
     * @dataProvider pathProvider
     */
    public function testRenderTemplate($path, $asset, $url)
    {
        $this->assetsHelperMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame(
            'CKEDITOR.addTemplates("foo", {"imagesPath":'.json_encode($url).',"filename":"bat"});',
            $this->helper->renderTemplate('foo', array('imagesPath' => $path, 'filename' => 'bat'))
        );
    }

    public function testName()
    {
        $this->assertSame('ivory_ckeditor', $this->helper->getName());
    }
}
