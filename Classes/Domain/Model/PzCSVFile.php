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
 * PzCSVFile
 */
class PzCSVFile extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * filePath
     *
     * @var string
     * @validate NotEmpty
     */
    protected $filePath = '';
    
    /**
     * name
     *
     * @var string
     * @validate NotEmpty
     */
    protected $fileName = '';

    /**
     * listRender
     *
     * @var \POiz\PoizCsvLister\Domain\Model\PzCSVRender
     */
    protected $listRender = null;

    /**
     * Returns the filePath
     *
     * @return string $filePath
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
	
	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}
    

    /**
     * Sets the filePath
     *
     * @param string $filePath
     * @return void
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Returns the listRender
     *
     * @return \POiz\PoizCsvLister\Domain\Model\PzCSVRender $listRender
     */
    public function getListRender()
    {
        return $this->listRender;
    }

    /**
     * Sets the listRender
     *
     * @param \POiz\PoizCsvLister\Domain\Model\PzCSVRender $listRender
     * @return void
     */
    public function setListRender(\POiz\PoizCsvLister\Domain\Model\PzCSVRender $listRender)
    {
        $this->listRender = $listRender;
    }
	
	/**
	 * @param string $fileName
	 * @return PzCSVFile
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
		
		return $this;
	}
 
 
}
