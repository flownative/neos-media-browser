<?php
namespace Flownative\Media\Browser\Domain\Model;

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

use Neos\Flow\Annotations\Entity;
use Neos\Flow\Annotations\Identity;

/**
 * @Entity
 */
final class ImportedAsset
{
    /**
     * @Identity()
     * @var string
     */
    protected $assetSourceIdentifier;

    /**
     * @Identity()
     * @var string
     */
    protected $remoteAssetIdentifier;

    /**
     * @var string
     */
    protected $localAssetIdentifier;

    /**
     * @var \DateTimeImmutable
     */
    protected $importedAt;

    /**
     * @param string $assetSourceIdentifier
     * @param string $remoteAssetIdentifier
     * @param string $localAsset
     * @param \DateTimeImmutable $importedAt
     */
    public function __construct(
        string $assetSourceIdentifier,
        string $remoteAssetIdentifier,
        string $localAsset,
        \DateTimeImmutable $importedAt
    ) {
        $this->assetSourceIdentifier = $assetSourceIdentifier;
        $this->remoteAssetIdentifier = $remoteAssetIdentifier;
        $this->localAssetIdentifier = $localAsset;
        $this->importedAt = $importedAt;
    }

    /**
     * @return string
     */
    public function getAssetSourceIdentifier(): string
    {
        return $this->assetSourceIdentifier;
    }

    /**
     * @param string $assetSourceIdentifier
     */
    public function setAssetSourceIdentifier(string $assetSourceIdentifier): void
    {
        $this->assetSourceIdentifier = $assetSourceIdentifier;
    }

    /**
     * @return string
     */
    public function getRemoteAssetIdentifier(): string
    {
        return $this->remoteAssetIdentifier;
    }

    /**
     * @param string $remoteAssetIdentifier
     */
    public function setRemoteAssetIdentifier(string $remoteAssetIdentifier): void
    {
        $this->remoteAssetIdentifier = $remoteAssetIdentifier;
    }

    /**
     * @return string
     */
    public function getLocalAssetIdentifier(): string
    {
        return $this->localAssetIdentifier;
    }

    /**
     * @param string $localAssetIdentifier
     */
    public function setLocalAssetIdentifier(string $localAssetIdentifier): void
    {
        $this->localAssetIdentifier = $localAssetIdentifier;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getImportedAt(): \DateTimeImmutable
    {
        return $this->importedAt;
    }

    /**
     * @param \DateTimeImmutable $importedAt
     */
    public function setImportedAt(\DateTimeImmutable $importedAt): void
    {
        $this->importedAt = $importedAt;
    }
}
