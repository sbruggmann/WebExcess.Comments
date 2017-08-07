<?php

namespace WebExcess\Comments\Tests\Functional;

/*
 * This file is part of the WebExcess.Comments package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use ReflectionMethod;
use Neos\Neos\Tests\Functional\AbstractNodeTest;
use Neos\Flow\Security\Authorization\TestingPrivilegeManager;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\Neos\Domain\Service\SiteImportService;

/**
 * Execute with:
 * bin/phpunit --colors -c Build/BuildEssentials/PhpUnit/FunctionalTests.xml --filter "WebExcess.Comments" --testdox
 *
 * @group large
 */
class AbstractTest extends AbstractNodeTest
{
    /**
     * @var \Neos\ContentRepository\Domain\Model\NodeInterface
     */
    protected $nodeInTestWorkspace;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $privilegeManager = $this->objectManager->get(TestingPrivilegeManager::class);
        $privilegeManager->setOverrideDecision(true);

        $liveWorkspace = $this->node->getWorkspace();
        $personalWorkspace = new Workspace('user-test', $liveWorkspace);
        $this->objectManager->get(WorkspaceRepository::class)->add($personalWorkspace);
        $this->persistenceManager->persistAll();

        $this->nodeInTestWorkspace = $this->getNodeWithContextPath('/sites/example/home@user-test');

        // --

        $contentContext = $this->contextFactory->create(array('workspaceName' => 'live'));
        $siteImportService = $this->objectManager->get(SiteImportService::class);
        $siteImportService->importFromFile(__DIR__ . '/' . $this->fixtureFileName, $contentContext);
        $this->persistenceManager->persistAll();
    }
}
