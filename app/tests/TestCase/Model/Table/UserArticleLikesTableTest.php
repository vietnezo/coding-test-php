<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserArticleLikesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserArticleLikesTable Test Case
 */
class UserArticleLikesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UserArticleLikesTable
     */
    protected $UserArticleLikes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.UserArticleLikes',
        'app.Users',
        'app.Articles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UserArticleLikes') ? [] : ['className' => UserArticleLikesTable::class];
        $this->UserArticleLikes = $this->getTableLocator()->get('UserArticleLikes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UserArticleLikes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UserArticleLikesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\UserArticleLikesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
