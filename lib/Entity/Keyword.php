<?php

namespace MovingImage\Client\VM6\Entity;

use MovingImage\Meta\Interfaces\KeywordInterface;

/**
 * Class Keyword.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class Keyword implements KeywordInterface
{
    /**
     * @var string
     */
    private $keyword;

    /**
     * Keyword constructor.
     *
     * @param string $keyword
     */
    public function __construct($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * Keywords in VM 6 don't have an ID.
     */
    public function getId()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
}
