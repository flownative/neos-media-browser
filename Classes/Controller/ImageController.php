<?php
namespace Flownative\Media\Browser\Controller;

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
use Neos\Media\Domain\Repository\ImageRepository;

/**
 * Controller for browsing images in the ImageEditor
 */
class ImageController extends AssetController
{
    /**
     * @Flow\Inject
     * @var ImageRepository
     */
    protected $assetRepository;

    /**
     * @param string $assetSourceIdentifier
     * @param string $assetProxyIdentifier
     * @return void
     */
    public function editAction(string $assetSourceIdentifier, string $assetProxyIdentifier)
    {
//        if ($assetProxy instanceof NeosAssetProxy && $assetProxy->getAsset() instanceof ImageVariant) {
// FIXME
//            $assetProxy = $assetProxy->getAsset()->getOriginalAsset();
//        }
        parent::editAction($assetSourceIdentifier, $assetProxyIdentifier);
    }
}
