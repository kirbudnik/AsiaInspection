<?php

namespace AI\ResponsiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WhitepaperDownload
 */
class WhitepaperDownload
{
    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $company;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $mailAddress;

    /**
     * @var string
     */
    private $report;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set category
     *
     * @param string $category
     * @return WhitepaperDownload
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
     * Set company
     *
     * @param string $company
     * @return WhitepaperDownload
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return WhitepaperDownload
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
     * Set firstName
     *
     * @param string $firstName
     * @return WhitepaperDownload
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return WhitepaperDownload
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set mailAddress
     *
     * @param string $mailAddress
     * @return WhitepaperDownload
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
     * @return WhitepaperDownload
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
