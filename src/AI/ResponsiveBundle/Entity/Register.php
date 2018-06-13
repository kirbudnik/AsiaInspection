<?php

namespace AI\ResponsiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Register
 *
 * @ORM\Table(name="register")
 * @ORM\Entity(repositoryClass="AI\ResponsiveBundle\Entity\RegisterRepository")
 */
class Register {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="firstName", type="string", length=255)
	 *
	 *
	 */
	private $firstName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lastName", type="string", length=255)
	 */
	private $lastName;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="Country", type="string", length=255)
	 */
	private $country;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="Company", type="string", length=255)
	 */
	private $company;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="Industry", type="string", length=255)
	 */
	private $industry;
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="telephone", type="integer")
	 */
	private $telephone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="username", type="string", length=255)
	 */
	private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=255)
	 */
	private $password;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="affid", type="int", length=255)
	 */
	private $affid;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="referrer", type="string", length=255)
	 */
	private $referrer;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="isMobile", type="boolean", length=255)
	 */
	private $isMobile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="deviceInfo", type="string", length=255)
	 */
	private $deviceInfo;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="chb", type="boolean", length=255)
	 */
	private $chb;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set firstName
	 *
	 * @param string $firstName
	 * @return Register
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Get firstName
	 *
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * Set lastName
	 *
	 * @param string $lastName
	 * @return Register
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * Get lastName
	 *
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}
	/**
	 * Set country
	 *
	 * @param string $company
	 * @return Register
	 */
	public function setCountry($country) {
		$this->country = $country;

		return $this;
	}

	/**
	 * Get country
	 *
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Set company
	 *
	 * @param string $company
	 * @return Register
	 */
	public function setCompany($company) {
		$this->company = $company;

		return $this;
	}

	/**
	 * Get company
	 *
	 * @return string
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * Set industry
	 *
	 * @param string $industry
	 * @return Register
	 */
	public function setIndustry($industry) {
		$this->industry = $industry;

		return $this;
	}

	/**
	 * Get industry
	 *
	 * @return string
	 */
	public function getIndustry() {
		return $this->industry;
	}

	/**
	 * Set telephone
	 *
	 * @param integer $telephone
	 * @return Register
	 */
	public function setTelephone($telephone) {
		$this->telephone = $telephone;

		return $this;
	}

	/**
	 * Get telephone
	 *
	 * @return integer
	 */
	public function getTelephone() {
		return $this->telephone;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return Register
	 */
	public function setEmail($email) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 * @return Register
	 */
	public function setUsername($username) {
		$this->username = $username;

		return $this;
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return Register
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set Affiliate ID
	 *
	 * @param int $affid
	 * @return Register
	 */
	public function setAffid($affid) {
		$this->affid = $affid;

		return $this;
	}

	/**
	 * Get Affiliate ID
	 *
	 * @return int
	 */
	public function getAffid() {
		return $this->affid;
	}

	/**
	 * Set Referrer
	 *
	 * @param string $referrer
	 * @return Register
	 */
	public function setReferrer($referrer) {
		$this->referrer = $referrer;

		return $this;
	}

	/**
	 * Get Referrer
	 *
	 * @return string
	 */
	public function getReferrer() {
		return $this->referrer;
	}

	/**
	 * Set isMobile
	 *
	 * @param boolean $isMobile
	 * @return Register
	 */
	public function setIsMobile($isMobile) {
		$this->isMobile = $isMobile;

		return $this;
	}

	/**
	 * Get isMobile
	 *
	 * @return boolean
	 */
	public function getIsMobile() {
		return $this->isMobile;
	}

	/**
	 * Set deviceInfo
	 *
	 * @param string $deviceInfo
	 * @return Register
	 */
	public function setDeviceInfo($deviceInfo) {
		$this->deviceInfo = $deviceInfo;

		return $this;
	}

	/**
	 * Get deviceInfo
	 *
	 * @return string
	 */
	public function getDeviceInfo() {
		return $this->deviceInfo;
	}

	/**
	 * Set chb
	 *
	 * @param boolean $chb
	 * @return Register
	 */
	public function setChb($chb) {
		$this->chb = $chb;

		return $this;
	}

	/**
	 * Get chb
	 *
	 * @return boolean
	 */
	public function getChb() {
		return $this->chb;
	}

}
