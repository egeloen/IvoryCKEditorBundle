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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Ivory\CKEditorBundle\Templating\CKEditorAssetHelper;

/**
 * CKEditor asset helper test.
 *
 * @author Adam Misiorny <adam.misiorny@gmail.com>
 */
class CKEditorAssetHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\Templating\Helper\CoreAssetsHelper|\PHPUnit_Framework_MockObject_MockObject */
    private $assetHelper;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $containerMock;

    /** @var \Symfony\Component\Asset\Packages|\PHPUnit_Framework_MockObject_MockObject */
    private $assetsPackagesMock;

    /**
     * {@inheritdoc}
     *
     * @todo replace \Symfony\Component\Templating\Asset\PackageInterface with \Symfony\Component\Asset\Packages
     */
    protected function setUp()
    {
        $this->assetsHelperMock = $this->getMockBuilder('Symfony\Component\Templating\Helper\CoreAssetsHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assetsPackagesMock = $this->getMockBuilder('Symfony\Component\Templating\Asset\PackageInterface')
            ->disableOriginalConstructor()
            ->getMock();

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
                    'assets.packages',
                    ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                    $this->assetsPackagesMock,
                ),
            )));

        $this->assetHelper = new CKEditorAssetHelper($this->containerMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->assetHelper);
        unset($this->containerMock);
        unset($this->assetsPackagesMock);
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
            array('path2', 'url2', 'url2'),
        );
    }

    /**
     * @dataProvider pathProvider
     */
    public function testGettingUrlFromAssetsPackage($path, $asset, $url)
    {
        $this->containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo('assets.packages'))
            ->will($this->returnValue(true));

        $this->assetsPackagesMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame($url, $this->assetHelper->getUrl($path));
    }

    /**
     * @dataProvider pathProvider
     */
    public function testGettingUrlFromAssetsHelper($path, $asset, $url)
    {
        $this->containerMock
            ->expects($this->at(0))
            ->method('has')
            ->with($this->equalTo('assets.packages'))
            ->will($this->returnValue(false));

        $this->containerMock
            ->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('templating.helper.assets'))
            ->will($this->returnValue(true));

        $this->assetsHelperMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($this->equalTo($path))
            ->will($this->returnValue($asset));

        $this->assertSame($url, $this->assetHelper->getUrl($path));
    }

    /**
     * @expectedException \Ivory\CKEditorBundle\Exception\AssetHelperException
     * @expectedExceptionMessage Could not get any service "assets.packages, templating.helper.assets" from service container.
     */
    public function testMissingService()
    {
        $this->assetHelper->getUrl('path');
    }
}
