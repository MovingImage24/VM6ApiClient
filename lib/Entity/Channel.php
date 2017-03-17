<?php

namespace MovingImage\Client\VM6\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Exclude;

/**
 * Class Channel
 * @package MovingImage\Client\VM6\Entity
 *
 * @author Robert Szeker <robert.szeker@movingimage.com>
 */
class Channel
{
    /**
     * @Type("int")
     */
    private $id = null;

    /**
     * @Type("string")
     */
    private $name = null;

    /**
     * @Type("int")
     * @SerializedName("parent")
     */
    private $parentId = null;

    /**
     * @Exclude
     * @var Channel
     */
    private $parent = null;

    /**
     * @var ArrayCollection<Channel>
     */
    private $children;

    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param null $parent
     * @return Channel
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     * @return Channel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setParentId($id)
    {
        $this->parentId = $id;

        return $this;
    }
}