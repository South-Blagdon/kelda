<?php

/*
 * This file is part of the Yosymfony\Spress.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Spress\Composer;

use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Yosymfony\Spress\Composer\Installer\SpressInstaller;

class SpressPlugin implements PluginInterface
{
    /**
     * Apply plugin modifications to composer.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new SpressInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
