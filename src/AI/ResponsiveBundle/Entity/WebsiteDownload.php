<?php

namespace AI\ResponsiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebsiteDownload
 *
 * @ORM\Table(name="WEBSITE_DOWNLOAD")
 * @ORM\Entity
 */
class WebsiteDownload
{
    /**
     * @var string
     *
     * @ORM\Column(name="CATEGORY", type="string", length=128, nullable=true)
     */
    private $category;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CREATED_DATE", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var string
     *
     * @ORM\Column(name="MAIL_ADDRESS", type="string", length=128, nullable=true)
     */
    private $mailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="REPORT", type="string", length=256, nullable=true)
     */
    private $report;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="WEBSITE_DOWNLOAD_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;



    /**
     * Set category
     *
     * @param string $category
     * @return WebsiteDownload
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return WebsiteDownload
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set mailAddress
     *
     * @param string $mailAddress
     * @return WebsiteDownload
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;

        return $this;
    }

    /**
     * Get mailAddress
     *
     * @return string 
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * Set report
     *
     * @param string $report
     * @return WebsiteDownload
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return string 
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
