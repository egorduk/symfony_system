<?php

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubjectOrder
 *
 * @ORM\Table(name="subject_order", uniqueConstraints={@ORM\UniqueConstraint(name="child_name", columns={"child_name"})})
 * @ORM\Entity
 */
class SubjectOrder
{
    /**
     * @var string
     *
     * @ORM\Column(name="child_name", type="string", length=50, nullable=false)
     */
    private $childName;

    /**
     * @var string
     *
     * @ORM\Column(name="parent_name", type="string", length=50, nullable=false)
     */
    private $parentName;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set childName
     *
     * @param string $childName
     *
     * @return SubjectOrder
     */
    public function setChildName($childName)
    {
        $this->childName = $childName;

        return $this;
    }

    /**
     * Get childName
     *
     * @return string
     */
    public function getChildName()
    {
        return $this->childName;
    }

    /**
     * Set parentName
     *
     * @param string $parentName
     *
     * @return SubjectOrder
     */
    public function setParentName($parentName)
    {
        $this->parentName = $parentName;

        return $this;
    }

    /**
     * Get parentName
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parentName;
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
