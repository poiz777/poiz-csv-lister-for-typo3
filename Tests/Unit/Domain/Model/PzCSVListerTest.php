<?php
namespace POiz\PoizCsvLister\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Poiz Campbelll <p.campbell@complot.ch>
 */
class PzCSVListerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \POiz\PoizCsvLister\Domain\Model\PzCSVLister
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \POiz\PoizCsvLister\Domain\Model\PzCSVLister();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function dummyTestToNotLeaveThisFileEmpty()
    {
        self::markTestIncomplete();
    }
}
