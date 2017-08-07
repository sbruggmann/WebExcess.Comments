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

class CommentModelTest extends AbstractTest
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
    public function storeUserContentSanitized()
    {
        $evilText = '<script type="application/javascript">evil js</script>';
        $validText = 'evil js';

        $comment = new \WebExcess\Comments\Domain\Model\Comment();

        $comment->setFirstname($evilText);
        $this->assertEquals($validText, $comment->getFirstname());

        $comment->setLastname($evilText);
        $this->assertEquals($validText, $comment->getLastname());

        $comment->setMessage($evilText);
        $this->assertEquals($validText, $comment->getMessage());
    }
}
