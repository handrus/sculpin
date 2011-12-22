<?php

/*
 * This file is a part of Sculpin.
 *
 * (c) Dragonfly Development Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dflydev\sculpin\console;

use dflydev\sculpin\configuration\Util;

use dflydev\sculpin\configuration\Configuration;

use dflydev\sculpin\configuration\YamlConfigurationBuilder;

use dflydev\sculpin\Sculpin;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Sculpin', Sculpin::VERSION);
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->add(new command\InitCommand());
        $this->add(new command\GenerateCommand());
        return parent::doRun($input, $output);
    }

    public function createSculpin() {
        $defaultConfiguration = new Configuration(array(
            'site' => array(
                'name' => 'Sculpin Site',
            ),
        ));
        $siteConfigurationBuilder = new YamlConfigurationBuilder(array(
            'sculpin.yml.dist',
            'sculpin.yml',
        ));
        $siteConfiguration = $siteConfigurationBuilder->build();
        $configuration = Util::MERGE_CONFIGURATIONS(array(
            $defaultConfiguration,
            $siteConfiguration,
        ));
        return new Sculpin($configuration);
    }

}