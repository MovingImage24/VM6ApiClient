<?php

namespace MovingImage\Client\VM6\Entity\Response;

use JMS\Serializer\Annotation\Type;

class VideoListResponse
{
    /**
     * @Type("array<MovingImage\Client\VM6\Entity\Video>")
     */
    private $videolist;

    public function getVideos()
    {
        return $this->videolist;
    }
}
