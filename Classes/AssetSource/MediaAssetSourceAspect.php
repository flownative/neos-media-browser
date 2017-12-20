<?php
namespace Flownative\Media\Browser\AssetSource;

/*
 * This file is part of the Flownative.Media.Browser package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 * (c) Robert Lemke, Flownative GmbH - www.flownative.com
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;

/**
 * Introduce the "assetSourceIdentifier" property into the "Asset" domain model of the Media package.
 *
 * This is a preliminary solution which can be removed as soon as the Neos Media package provides that property in a
 * released version.
 *
 * @Flow\Aspect
 * @Flow\Introduce(pointcutExpression="class(Neos\Media\Domain\Model\Asset)", interfaceName="Flownative\Media\Browser\AssetSource\MediaAssetSourceAware")
 */
class MediaAssetSourceAspect
{
    /**
     * @Flow\Introduce(pointcutExpression="class(Neos\Media\Domain\Model\Asset)")
     * @var string
     */
    public $assetSourceIdentifier = 'neos';

    /**
     * @param JoinPointInterface $joinPoint
     * @return string|null
     * @Flow\Around(pointcutExpression="method(Neos\Media\Domain\Model\Asset->getAssetSourceIdentifier())")
     */
    public function getAssetSourceIdentifierAdvice(JoinPointInterface $joinPoint)
    {
        $proxy = $joinPoint->getProxy();
        return $proxy->assetSourceIdentifier ?? null;
    }

    /**
     * @param JoinPointInterface $joinPoint
     * @Flow\Around(pointcutExpression="method(Neos\Media\Domain\Model\Asset->setAssetSourceIdentifier())")
     * @return void
     */
    public function setAssetSourceIdentifierAdvice(JoinPointInterface $joinPoint)
    {
        $proxy = $joinPoint->getProxy();
        $proxy->assetSourceIdentifier = $joinPoint->getMethodArgument('assetSourceIdentifier');
    }
}
