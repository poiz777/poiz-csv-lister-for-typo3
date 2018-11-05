<?php
namespace POiz\PoizCsvLister\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Poiz Campbelll <p.campbell@complot.ch>
 */
class PzCSVRenderTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \POiz\PoizCsvLister\Domain\Model\PzCSVRender
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \POiz\PoizCsvLister\Domain\Model\PzCSVRender();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getFileIdReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setFileIdForIntSetsFileId()
    {
    }

    /**
     * @test
     */
    public function getPayloadReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPayload()
        );

    }

    /**
     * @test
     */
    public function setPayloadForStringSetsPayload()
    {
        $this->subject->setPayload('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'payload',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getDataReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getData()
        );

    }

    /**
     * @test
     */
    public function setDataForStringSetsData()
    {
        $this->subject->setData('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'data',
            $this->subject
        );

    }
}
