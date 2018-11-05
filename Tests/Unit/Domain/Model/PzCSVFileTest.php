<?php
namespace POiz\PoizCsvLister\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Poiz Campbelll <p.campbell@complot.ch>
 */
class PzCSVFileTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \POiz\PoizCsvLister\Domain\Model\PzCSVFile
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \POiz\PoizCsvLister\Domain\Model\PzCSVFile();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getFilePathReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFilePath()
        );

    }

    /**
     * @test
     */
    public function setFilePathForStringSetsFilePath()
    {
        $this->subject->setFilePath('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'filePath',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getListRenderReturnsInitialValueForPzCSVRender()
    {
        self::assertEquals(
            null,
            $this->subject->getListRender()
        );

    }

    /**
     * @test
     */
    public function setListRenderForPzCSVRenderSetsListRender()
    {
        $listRenderFixture = new \POiz\PoizCsvLister\Domain\Model\PzCSVRender();
        $this->subject->setListRender($listRenderFixture);

        self::assertAttributeEquals(
            $listRenderFixture,
            'listRender',
            $this->subject
        );

    }
}
