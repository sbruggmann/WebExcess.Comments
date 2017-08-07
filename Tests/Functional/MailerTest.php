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

class MailerTest extends AbstractTest
{
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function theTestEnvironmentHasComments()
    {
        $this->assertNotNull($this->nodeInTestWorkspace->getNode('feedbacks/comments/comment1'), 'comment 1 does not exist');
        $this->assertNotNull($this->nodeInTestWorkspace->getNode('feedbacks/comments/comment2'), 'comment 2 does not exist');
        $this->assertNotNull($this->nodeInTestWorkspace->getNode('feedbacks/comments/comment3'), 'comment 3 does not exist');
    }

    /**
     * @test
     */
    public function theMailerServiceIsSendingEmails()
    {
        $commentModel1 = new \WebExcess\Comments\Domain\Model\Comment();
        $commentModel1->setFirstname('Third Firstname');
        $commentModel1->setLastname('Third Lastname');
        $commentModel1->setMessage('Third Message');
        $commentNode1 = $this->nodeInTestWorkspace->getNode('feedbacks/comments/comment1');

        $mailerService = new \WebExcess\Comments\Service\Mailer();
        $this->assertNull($mailerService->sendCommentCreatedEmails($commentModel1, $commentNode1));
    }

    /**
     * @test
     */
    public function theMailerFindsTheCorrectRecipients()
    {
        $commentModel1 = new \WebExcess\Comments\Domain\Model\Comment();
        $commentModel1->setFirstname('Third Firstname');
        $commentModel1->setLastname('Third Lastname');
        $commentModel1->setMessage('Third Message');
        $commentNode1 = $this->nodeInTestWorkspace->getNode('feedbacks/comments/comment1');

        $mailerService = $this->objectManager->get(\WebExcess\Comments\Service\Mailer::class);

        $method = new ReflectionMethod(
            \WebExcess\Comments\Service\Mailer::class, 'collectRecipientsByCommentNode'
        );

        $method->setAccessible(true);

        $result = $method->invoke($mailerService, $commentNode1);
        $this->assertEquals(1, count($result));

        foreach ($result as $item) {
            $this->assertEquals('another.email@address.com', $item->getProperty('email'));
            break;
        }
    }
}
