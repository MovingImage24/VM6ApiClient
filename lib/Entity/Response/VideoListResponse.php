<?php

namespace MovingImage\Client\VM6\Entity\Response;

use JMS\Serializer\Annotation\Type;
use MovingImage\Client\VM6\Entity\Video;

/**
 * Class VideoListResponse.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoListResponse
{
    /**
     * @Type("array<MovingImage\Client\VM6\Entity\Video>")
     */
    private $videolist;

    /**
     * @return Video[]
     */
    public function getVideos()
    {
        return $this->videolist;
    }
}
