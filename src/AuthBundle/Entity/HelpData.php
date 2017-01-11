<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HelpData
 *
 * @ORM\Table(name="help_data")
 * @ORM\Entity
 */
class HelpData
{
    /**
     * @var integer
     *
     * @ORM\Column(name="days_verdict", type="integer", nullable=false)
     */
    private $daysVerdict;

    /**
     * @var integer
     *
     * @ORM\Column(name="days_guarantee", type="integer", nullable=false)
     */
    private $daysGuarantee;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set daysVerdict
     *
     * @param integer $daysVerdict
     *
     * @return HelpData
     */
    public function setDaysVerdict($daysVerdict)
    {
        $this->daysVerdict = $daysVerdict;

        return $this;
    }

    /**
     * Get daysVerdict
     *
     * @return integer
     */
    public function getDaysVerdict()
    {
        return $this->daysVerdict;
    }

    /**
     * Set daysGuarantee
     *
     * @param integer $daysGuarantee
     *
     * @return HelpData
     */
    public function setDaysGuarantee($daysGuarantee)
    {
        $this->daysGuarantee = $daysGuarantee;

        return $this;
    }

    /**
     * Get daysGuarantee
     *
     * @return integer
     */
    public function getDaysGuarantee()
    {
        return $this->daysGuarantee;
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
