<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FeedsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FeedsTable Test Case
 */
class FeedsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FeedsTable
     */
    public $Feeds;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.feeds',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Feeds') ? [] : ['className' => FeedsTable::class];
        $this->Feeds = TableRegistry::getTableLocator()->get('Feeds', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Feeds);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
