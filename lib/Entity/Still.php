<?php

namespace MovingImage\Client\VM6\Entity;

use MovingImage\Meta\Interfaces\StillInterface;

class Still implements StillInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $quality;

    /**
     * Still constructor.
     *
     * @param string $url
     * @param string $quality
     */
    public function __construct($url, $quality)
    {
        $this->url = $url;
        $this->quality = $quality;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Dimensions for stills are not available in VM 6.
     */
    public function getDimensions()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getQuality()
    {
        return $this->quality;
    }
}
