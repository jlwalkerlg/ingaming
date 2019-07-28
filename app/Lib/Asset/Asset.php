<?php

namespace App\Lib\Asset;

/**
 * Asset class.
 *
 * Used for retrieving the URLs of public assets such as images, scripts, and stylesheets.
 * Only necessary when renaming files between entry and output, such as when using content hashes
 * through webpack, since the hash would have to be known to include the proper URLs into the PHP script.
 * Works with the webpack manifest.json file to determine the correct URL of the assets output by webpack.
 */
class Asset
{
    /** @var object $assets Object of asset src names mapped to their output names. */
    protected $assets;


    /**
     * Retrieve URL of asset.
     *
     * @param string $assetKey Source name of asset.
     * @return string URL of asset output.
     **/
    public function get(string $assetKey)
    {
        $this->decodeAssetManifest();

        if (property_exists($this->assets, $assetKey)) {
            return $this->assets->$assetKey;
        }

        return null;
    }


    /**
     * Decode manifest.json file output by webpack.
     **/
    private function decodeAssetManifest()
    {
        if (isset($this->assets)) return;

        $manifestJson = file_get_contents(PROJECT_ROOT . '/manifest.json');

        $this->assets = json_decode($manifestJson);
    }
}
