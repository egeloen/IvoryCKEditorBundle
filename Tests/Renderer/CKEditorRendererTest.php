<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Tests\Renderer;

use Ivory\CKEditorBundle\Renderer\CKEditorRenderer;
use Ivory\CKEditorBundle\Renderer\CKEditorRendererInterface;
use Ivory\CKEditorBundle\Tests\AbstractTestCase;
use Ivory\JsonBuilder\JsonBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CKEditorRendererTest extends AbstractTestCase
{
    /**
     * @var JsonBuilder
     */
    private $jsonBuilder;

    /**
     * @var Packages|MockObject
     */
    private $packages;

    /**
     * @var Request|MockObject
     */
    private $request;

    /**
     * @var RequestStack|MockObject
     */
    private $requestStack;

    /**
     * @var RouterInterface|MockObject
     */
    private $router;

    /**
     * @var EngineInterface|MockObject
     */
    private $templating;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->jsonBuilder = new JsonBuilder();
        $this->request = $this->createMock(Request::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->packages = $this->getMockBuilder(Packages::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestStack = $this->createMock(RequestStack::class);
        $this->requestStack
            ->expects($this->once())
            ->method('getMasterRequest')
                ->will($this->returnValue($this->request));

        $this->templating = $this->createMock(EngineInterface::class);
    }

    public function testDefaultState()
    {
        $this->assertInstanceOf(CKEditorRendererInterface::class, $this->createRenderer());
    }

    /**
     * @param string $path
     * @param string $asset
     * @param string $url
     *
     * @dataProvider directoryAssetProvider
     */
    public function testRenderBasePath($path, $asset, $url)
    {
        $this->packages
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame($url, $this->createRenderer()->renderBasePath($path));
    }

    public function testRenderJsPath()
    {
        $this->packages
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->createRenderer()->renderJsPath('foo'));
    }

    /**
     * @param string $symfonyLocale
     * @param string $ckEditorLocale
     *
     * @dataProvider languageProvider
     */
    public function testRenderWidgetWithLocaleRequest($symfonyLocale, $ckEditorLocale)
    {
        $this->request
            ->expects($this->exactly(2))
            ->method('getLocale')
            ->will($this->returnValue($symfonyLocale));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"language":"'.$ckEditorLocale.'"});',
            $this->createRenderer()->renderWidget('foo', [])
        );
    }

    /**
     * @param string $symfonyLocale
     * @param string $ckEditorLocale
     *
     * @dataProvider languageProvider
     */
    public function testRenderWidgetWithLocaleParameter($symfonyLocale, $ckEditorLocale)
    {
        $this->request
            ->expects($this->exactly(1))
            ->method('getLocale')
            ->will($this->returnValue(null));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"language":"'.$ckEditorLocale.'"});',
            $this->createRenderer($symfonyLocale)->renderWidget('foo', [])
        );
    }

    /**
     * @param string $symfonyLocale
     * @param string $ckEditorLocale
     *
     * @dataProvider languageProvider
     */
    public function testRenderWidgetWithExplicitLanguage($symfonyLocale, $ckEditorLocale)
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"language":"'.$ckEditorLocale.'"});',
            $this->createRenderer()->renderWidget('foo', ['language' => $symfonyLocale])
        );
    }

    public function testRenderWidgetWithoutLocale()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", []);',
            $this->createRenderer(null)->renderWidget('foo', [])
        );
    }

    /**
     * @param string $path
     * @param string $asset
     * @param string $url
     *
     * @dataProvider fileAssetProvider
     */
    public function testRenderWidgetWithStringContentsCss($path, $asset, $url)
    {
        $this->packages
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"contentsCss":["'.$url.'"]});',
            $this->createRenderer()->renderWidget('foo', ['contentsCss' => $path])
        );
    }

    /**
     * @param string[] $paths
     * @param string[] $assets
     * @param string[] $urls
     *
     * @dataProvider filesAssetProvider
     */
    public function testRenderWidgetWithArrayContentsCss(array $paths, array $assets, array $urls)
    {
        foreach (array_keys($paths) as $key) {
            $this->packages
                ->expects($this->at($key))
                ->method('getUrl')
                ->with($this->equalTo($paths[$key]))
                ->will($this->returnValue($assets[$key]));
        }

        $this->assertSame(
            'CKEDITOR.replace("foo", {"contentsCss":'.json_encode($urls).'});',
            $this->createRenderer()->renderWidget('foo', ['contentsCss' => $paths])
        );
    }

    /**
     * @param string $filebrowser
     *
     * @dataProvider filebrowserProvider
     */
    public function testRenderWidgetWithFileBrowser($filebrowser)
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.$filebrowser.'Url":"'.($url = 'browse_url').'"});',
            $this->createRenderer()->renderWidget('foo', ['filebrowser'.$filebrowser.'Url' => $url])
        );
    }

    public function testRenderWidgetWithCustomFileBrowser()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.($filebrowser = 'VideoBrowse').'Url":"'.($url = 'browse_url').'"});',
            $this->createRenderer()->renderWidget(
                'foo',
                ['filebrowser'.$filebrowser.'Url' => $url],
                ['filebrowsers'                   => [$filebrowser]]
            )
        );
    }

    /**
     * @param string $filebrowser
     *
     * @dataProvider filebrowserProvider
     */
    public function testRenderWidgetWithMinimalRouteFileBrowser($filebrowser)
    {
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->identicalTo($route = 'browse_route'),
                $this->identicalTo([]),
                $this->identicalTo(UrlGeneratorInterface::ABSOLUTE_PATH)
            )
            ->will($this->returnValue('browse_url'));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.$filebrowser.'Url":"browse_url"});',
            $this->createRenderer()->renderWidget('foo', ['filebrowser'.$filebrowser.'Route' => $route])
        );
    }

    /**
     * @param string $filebrowser
     *
     * @dataProvider filebrowserProvider
     */
    public function testRenderWidgetWithMaximalRouteFileBrowser($filebrowser)
    {
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->identicalTo($route = 'browse_route'),
                $this->identicalTo($routeParameters = ['foo' => 'bar']),
                $this->identicalTo($routeType = UrlGeneratorInterface::ABSOLUTE_URL)
            )
            ->will($this->returnValue('browse_url'));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.$filebrowser.'Url":"browse_url"});',
            $this->createRenderer()->renderWidget('foo', [
                'filebrowser'.$filebrowser.'Route'           => $route,
                'filebrowser'.$filebrowser.'RouteParameters' => $routeParameters,
                'filebrowser'.$filebrowser.'RouteType'       => $routeType,
            ])
        );
    }

    /**
     * @param string $filebrowser
     *
     * @dataProvider filebrowserProvider
     */
    public function testRenderWidgetWithRouteFileBrowserHandler($filebrowser)
    {
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('browse_route'),
                $this->equalTo(['foo' => 'bar']),
                $this->equalTo(UrlGeneratorInterface::ABSOLUTE_URL)
            )
            ->will($this->returnValue('browse_url'));

        $this->assertSame(
            'CKEDITOR.replace("foo", {"filebrowser'.$filebrowser.'Url":"browse_url"});',
            $this->createRenderer()->renderWidget('foo', [
                'filebrowser'.$filebrowser.'Handler' => function (RouterInterface $router) {
                    return $router->generate('browse_route', ['foo' => 'bar'], UrlGeneratorInterface::ABSOLUTE_URL);
                },
            ])
        );
    }

    public function testRenderWidgetWithProtectedSource()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"protectedSource":[/<\?[\s\S]*?\?>/g,/<%[\s\S]*?%>/g]});',
            $this->createRenderer()->renderWidget('foo', [
                'protectedSource' => [
                    '/<\?[\s\S]*?\?>/g',
                    '/<%[\s\S]*?%>/g',
                ],
            ])
        );
    }

    public function testRenderWidgetWithStylesheetParserSkipSelectors()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"stylesheetParser_skipSelectors":/(^body\.|^caption\.|\.high|^\.)/i});',
            $this->createRenderer()->renderWidget('foo', [
                'stylesheetParser_skipSelectors' => '/(^body\.|^caption\.|\.high|^\.)/i',
            ])
        );
    }

    public function testRenderWidgetWithStylesheetParserValidSelectors()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"stylesheetParser_validSelectors":/\^(p|span)\.\w+/});',
            $this->createRenderer()->renderWidget('foo', [
                'stylesheetParser_validSelectors' => '/\^(p|span)\.\w+/',
            ])
        );
    }

    public function testRenderWidgetWithCKEditorConstants()
    {
        $this->assertSame(
            'CKEDITOR.replace("foo", {"config":{"enterMode":CKEDITOR.ENTER_BR,"shiftEnterMode":CKEDITOR.ENTER_BR}});',
            $this->createRenderer()->renderWidget('foo', [
                'config' => [
                    'enterMode'      => 'CKEDITOR.ENTER_BR',
                    'shiftEnterMode' => 'CKEDITOR.ENTER_BR',
                ],
            ])
        );
    }

    public function testRenderWidgetWithoutAutoInline()
    {
        $this->assertSame(
            'CKEDITOR.disableAutoInline = true;'."\n".'CKEDITOR.replace("foo", []);',
            $this->createRenderer()->renderWidget('foo', [], ['auto_inline' => false])
        );
    }

    public function testRenderWidgetWithInline()
    {
        $this->assertSame(
            'CKEDITOR.inline("foo", []);',
            $this->createRenderer()->renderWidget('foo', [], ['inline' => true])
        );
    }

    public function testRenderWidgetWithInputSync()
    {
        $this->assertSame(
            'var ivory_ckeditor_foo = CKEDITOR.replace("foo", []);'."\n".
            'ivory_ckeditor_foo.on(\'change\', function() { ivory_ckeditor_foo.updateElement(); });',
            $this->createRenderer()->renderWidget('foo', [], ['input_sync' => true])
        );
    }

    public function testRenderDestroy()
    {
        $this->assertSame(
            'if (CKEDITOR.instances["foo"]) { '.
            'CKEDITOR.instances["foo"].destroy(true); '.
            'delete CKEDITOR.instances["foo"]; '.
            '}',
            $this->createRenderer()->renderDestroy('foo')
        );
    }

    /**
     * @param string $path
     * @param string $asset
     * @param string $url
     *
     * @dataProvider directoryAssetProvider
     */
    public function testRenderPlugin($path, $asset, $url)
    {
        $this->packages
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame(
            'CKEDITOR.plugins.addExternal("foo", "'.$url.'", "bat");',
            $this->createRenderer()->renderPlugin('foo', ['path' => $path, 'filename' => 'bat'])
        );
    }

    public function testRenderStylesSet()
    {
        $this->assertSame(
            'if (CKEDITOR.stylesSet.get("foo") === null) { CKEDITOR.stylesSet.add("foo", {"foo":"bar"}); }',
            $this->createRenderer()->renderStylesSet('foo', ['foo' => 'bar'])
        );
    }

    /**
     * @param string $path
     * @param string $asset
     * @param string $url
     *
     * @dataProvider directoryAssetProvider
     */
    public function testRenderTemplate($path, $asset, $url)
    {
        $templates = [
            [
                'title' => 'Template title',
                'html'  => '<p>Template content</p>',
            ],
        ];

        $this->packages
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $json = json_encode(['imagesPath' => $url, 'templates' => $templates]);

        $this->assertSame(
            'CKEDITOR.addTemplates("foo", '.$json.');',
            $this->createRenderer()->renderTemplate('foo', ['imagesPath' => $path, 'templates' => $templates])
        );
    }

    /**
     * @param string $path
     * @param string $asset
     * @param string $url
     *
     * @dataProvider directoryAssetProvider
     */
    public function testRenderTemplateWithAnEngine($path, $asset, $url)
    {
        $templates = [
            [
                'title'               => 'Template title',
                'template'            => $template = 'template_name',
                'template_parameters' => $templateParameters = ['foo' => 'bar'],
            ],
        ];

        $processedTemplates = [
            [
                'title' => 'Template title',
                'html'  => $html = '<p>Template content</p>',
            ],
        ];

        $this->packages
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->templating
            ->expects($this->once())
            ->method('render')
            ->with($this->identicalTo($template), $this->identicalTo($templateParameters))
            ->will($this->returnValue($html));

        $json = json_encode(['imagesPath' => $url, 'templates'  => $processedTemplates]);

        $this->assertSame(
            'CKEDITOR.addTemplates("foo", '.$json.');',
            $this->createRenderer()->renderTemplate('foo', ['imagesPath' => $path, 'templates' => $templates])
        );
    }

    /**
     * @return array
     */
    public function languageProvider()
    {
        return [
            ['en', 'en'],
            ['pt_BR', 'pt-br'],
        ];
    }

    /**
     * @return array
     */
    public function directoryAssetProvider()
    {
        return [
            ['directory/', 'url/', 'url/'],
            ['directory/', 'url/?v=2', 'url/'],
        ];
    }

    /**
     * @return array
     */
    public function fileAssetProvider()
    {
        return [
            ['file.js', 'url.js', 'url.js'],
            ['file.js', 'url.js?v=2', 'url.js?v=2'],
        ];
    }

    /**
     * @return array
     */
    public function filesAssetProvider()
    {
        return [
            [['file'], ['url'], ['url']],
            [['file'], ['url?v=2'], ['url?v=2']],
            [['file1', 'file2'], ['url1', 'url2'], ['url1', 'url2']],
            [['file1', 'file2'], ['url1?v=2', 'url2'], ['url1?v=2', 'url2']],
            [['file1', 'file2'], ['url1', 'url2?v=2'], ['url1', 'url2?v=2']],
            [['file1', 'file2'], ['url1?v=2', 'url2?v=2'], ['url1?v=2', 'url2?v=2']],
        ];
    }

    /**
     * @return array
     */
    public function filebrowserProvider()
    {
        return [
            ['Browse'],
            ['FlashBrowse'],
            ['ImageBrowse'],
            ['ImageBrowseLink'],
            ['Upload'],
            ['FlashUpload'],
            ['ImageUpload'],
        ];
    }

    /**
     * @param string|null $localeParameter
     * @return CKEditorRenderer
     */
    private function createRenderer($localeParameter = null)
    {
        return new CKEditorRenderer(
            $this->jsonBuilder,
            $this->router,
            $this->requestStack,
            $this->packages,
            $this->templating,
            $localeParameter
        );
    }
}
