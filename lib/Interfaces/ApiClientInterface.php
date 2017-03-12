<?php

namespace MovingImage\Client\VM6\Interfaces;

use MovingImage\Client\VM6\Criteria\VideoQueryCriteria;
use MovingImage\Client\VM6\Entity\EmbedCode;
use MovingImage\Client\VM6\Entity\Video;

/**
 * Interface ApiClientInterface.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
interface ApiClientInterface
{
    /**
     * @const Version indicator to determine compatibility.
     */
    const VERSION = '0.1.0';

    /**
     * @param VideoQueryCriteria $criteria
     *
     * @return Video[]
     */
    public function getVideos(VideoQueryCriteria $criteria);

    /**
     * @param VideoQueryCriteria $criteria
     *
     * @return int
     */
    public function getVideoCount(VideoQueryCriteria $criteria);

    /**
     * @param int $videoId
     *
     * @return Video
     */
    public function getVideo($videoId);

    /**
     * @param Video $video
     * @param int   $skinId
     * @param int   $type
     *
     * @return EmbedCode
     */
    public function getEmbedCode(Video $video, $skinId, $type = EmbedCode::TYPE_JAVASCRIPT);
}
