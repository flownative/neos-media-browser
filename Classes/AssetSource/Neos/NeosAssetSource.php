<?php
namespace Flownative\Media\Browser\AssetSource\Neos;

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

use Flownative\Media\Browser\AssetSource\AssetProxyRepositoryInterface;
use Flownative\Media\Browser\AssetSource\AssetSourceInterface;

final class NeosAssetSource implements AssetSourceInterface
{
    /**
     * @var NeosAssetProxyRepository
     */
    protected $assetBrowser;

    public function getLabel(): string
    {
        return 'Neos';
    }

    /**
     * @return AssetProxyRepositoryInterface
     */
    public function getAssetProxyRepository(): AssetProxyRepositoryInterface
    {
        if ($this->assetBrowser === null) {
            $this->assetBrowser = new NeosAssetProxyRepository();
        }

        return $this->assetBrowser;
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }
}
