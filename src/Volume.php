<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   MIT
 */

namespace craft\cloudinary;

use Craft;
use craft\base\FlysystemVolume;
use Enl\Flysystem\Cloudinary\ApiFacade as CloudinaryClient;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
// use OpenCloud\Common\Service\Catalog;
// use OpenCloud\Common\Service\CatalogItem;
// use OpenCloud\Identity\Resource\Token;
// use OpenCloud\OpenStack;
// use OpenCloud\Rackspace;
use yii\base\UserException;

/**
 * Class Volume
 *
 * @property null|string $settingsHtml
 * @property string      $rootUrl
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
class Volume extends FlysystemVolume
{
    /**
     * Cache key to use for caching purposes
     */
    const CACHE_KEY_PREFIX = 'cloudinary.';

    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Cloudinary';
    }

    // Properties
    // =========================================================================

    /**
     * @var bool Whether this is a local source or not. Defaults to false.
     */
    protected $isSourceLocal = false;

    /**
     * @var string Path to the root of this sources local folder.
     */
    public $subfolder = '';

    /**
     * @var string Cloudinary API key
     */
    public $apiKey = '';

    /**
     * @var string Cloudinary API secret
     */
    public $apiSecret = '';

    /**
     * @var string Cloudinary cloud name to use
     */
    public $cloudName = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->foldersHaveTrailingSlashes = false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['cloudName', 'apiKey'], 'required'];

        return $rules;
    }

    /**
     * @inheritdoc
     *
     * @return string|null
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('cloudinary/volumeSettings', [
            'volume' => $this
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getRootUrl()
    {
        return rtrim(rtrim($this->url, '/').'/'.$this->subfolder, '/').'/';
    }


    // Overrides to ensure whitespaces and non-ASCII characters work.
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getFileMetadata(string $uri): array
    {
        return parent::getFileMetadata(urlencode($uri));
    }

    /**
     * @inheritdoc
     */
    public function createFileByStream(string $path, $stream, array $config)
    {
        parent::createFileByStream(urlencode($path), $stream, $config);
    }

    /**
     * @inheritdoc
     */
    public function updateFileByStream(string $path, $stream, array $config)
    {
        parent::updateFileByStream(urlencode($path), $stream, $config);
    }

    /**
     * @inheritdoc
     */
    public function createDir(string $path)
    {
        parent::createDir(urlencode($path));
    }

    /**
     * @inheritdoc
     */
    public function fileExists(string $path): bool
    {
        return parent::fileExists(urlencode($path));
    }

    /**
     * @inheritdoc
     */
    public function folderExists(string $path): bool
    {
        return parent::folderExists(urlencode($path));
    }

    /**
     * @inheritdoc
     */
    public function renameFile(string $path, string $newPath)
    {
        parent::renameFile(urlencode($path), urlencode($newPath));
    }

    /**
     * @inheritdoc
     */
    public function deleteFile(string $path)
    {
        parent::deleteFile(urlencode($path));
    }

    /**
     * @inheritdoc
     */
    public function copyFile(string $path, string $newPath)
    {
        parent::copyFile(urlencode($path), urlencode($newPath));
    }

    /**
     * @inheritdoc
     */
    public function getFileStream(string $uriPath)
    {
        return parent::getFileStream(urlencode($uriPath));
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     *
     * @return CloudinaryAdapter
     */
    protected function createAdapter()
    {
        $client = static::client([
            'cloud_name' => $this->cloudName,
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'overwrite' => $this->overwrite,
        ]);

        return new CloudinaryAdapter($client);
    }

    protected static function client(array $config = []): CloudinaryClient
    {
        return new CloudinaryClient($config);
    }
}
