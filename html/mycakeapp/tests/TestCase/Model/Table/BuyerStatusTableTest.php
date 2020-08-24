<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BuyerStatusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BuyerStatusTable Test Case
 */
class BuyerStatusTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BuyerStatusTable
     */
    public $BuyerStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.BuyerStatus',
        'app.Buyers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BuyerStatus') ? [] : ['className' => BuyerStatusTable::class];
        $this->BuyerStatus = TableRegistry::getTableLocator()->get('BuyerStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BuyerStatus);

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
