<?php
namespace POiz\PoizCsvLister\Domain\Model;

/***
 *
 * This file is part of the "Poiz CSV Lister" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017 Poiz Campbelll <p.campbell@complot.ch>, Commix AG Bern
 *
 ***/

/**
 * PzCSVRender
 */
class PzCSVRender extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * fileId
     *
     * @var int
     * @validate NotEmpty
     */
    protected $fileId = 0;

    /**
     * payload
     *
     * @var string
     */
    protected $payload = '';

    /**
     * data
     *
     * @var string
     */
    protected $data = '';

    /**
     * Returns the fileId
     *
     * @return int $fileId
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Sets the fileId
     *
     * @param int $fileId
     * @return void
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * Returns the payload
     *
     * @return string $payload
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Sets the payload
     *
     * @param string $payload
     * @return void
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns the data
     *
     * @return string $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data
     *
     * @param string $data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
