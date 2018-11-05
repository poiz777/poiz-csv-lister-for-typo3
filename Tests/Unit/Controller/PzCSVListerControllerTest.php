<?php
namespace POiz\PoizCsvLister\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Poiz Campbelll <p.campbell@complot.ch>
 */
class PzCSVListerControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \POiz\PoizCsvLister\Controller\PzCSVListerController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\POiz\PoizCsvLister\Controller\PzCSVListerController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

}
