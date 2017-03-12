<?php

namespace MovingImage\Client\VM6\Entity\Response;

use JMS\Serializer\Annotation\Type;

/**
 * Class VideoListCountResponse.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoListCountResponse
{
    /**
     * @Type("integer")
     */
    private $videocount;

    /**
     * @return int
     */
    public function getVideoCount()
    {
        return $this->videocount;
    }
}
