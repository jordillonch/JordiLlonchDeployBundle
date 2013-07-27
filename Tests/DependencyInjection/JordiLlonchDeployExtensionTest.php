<?php

/**
 * This file is part of the JordiLlonchDeployBundle
 *
 * (c) Jordi Llonch <llonch.jordi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JordiLlonch\Bundle\DeployBundle\Tests\DependencyInjection;

use JordiLlonch\Bundle\DeployBundle\DependencyInjection\JordiLlonchDeployExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class JordiLlonchDeployExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $config = Yaml::parse($this->getBundleConfig());
        $this->extension = new JordiLlonchDeployExtension();
        $this->extension->load(array($config), $this->container);
    }

    public function testContainerDefinitionAndLoadExtension()
    {
        $this->assertTrue($this->container->has('jordillonch_deployer.configure'));
    }

    protected function getBundleConfig()
    {
        return <<<'EOF'
config:
    project: MyProject
    mail_from: iamrobot@me.com
    mail_to:
        - jordi.llonch@me.com
    local_repository_dir: /tmp/deployer_local_repository
    clean_before_days: 7
    ssh:
        user: myuser
        public_key_file: '~/.ssh/id_rsa.pub'
        private_key_file: '~/.ssh/id_rsa'
zones:
    test:
        deployer: test
        environment: prod
        urls:
            - jllonch@testserver1
        checkout_url: 'git@github.com:jordillonch/JordiLlonchDeployBundle.git'
        checkout_branch: master
        repository_dir: /var/www/production/test/deploy
        production_dir: /var/www/production/test/code
EOF;
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->container = null;
        $this->extension = null;
    }
}
