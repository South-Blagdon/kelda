<?php

/*
 * This file is part of the Yosymfony\Spress.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Spress\Composer\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Installer for Spress 2 add-ons.
 *
 * @author Victor Puertas <vpuertas@gmail.com>
 */
class SpressInstaller extends LibraryInstaller
{
    /** @var string */
    const TYPE_PLUGIN = 'spress-plugin';

    /** @var string */
    const TYPE_THEME = 'spress-theme';

    /** @var string */
    const EXTRA_SPRESS_SITE_DIR = 'spress_site_dir';

    /**
     * {@inheritdoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        if ($package->getType() === self::TYPE_PLUGIN) {
            return $this->getSpressSiteDir().'src/plugins/'.$package->getPrettyName();
        }

        return $this->getSpressSiteDir().'src/themes/'.$package->getPrettyName();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($packageType)
    {
        return in_array($packageType, [
            self::TYPE_PLUGIN,
            self::TYPE_THEME,
        ], true);
    }

    /**
     * Returns the Spress site directory. If the extra attributte
     * "spress_site_dir" is not presents in the "extra" section of the root
     * package,.
     *
     * @return string If the extra attributte
     *                "spress_site_dir" is not presents in the "extra" section of the root
     *                package, an empty string will be return
     */
    protected function getSpressSiteDir()
    {
        $rootPackage = $this->composer->getPackage();
        $extras = $rootPackage->getExtra();

        return isset($extras[self::EXTRA_SPRESS_SITE_DIR]) ? $extras[self::EXTRA_SPRESS_SITE_DIR].'/' : '';
    }
}
