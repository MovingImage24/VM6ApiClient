<?php

namespace MovingImage\Client\VM6\ApiClient;

use MovingImage\Client\VM6\Criteria\VideoQueryCriteria;
use MovingImage\Client\VM6\Entity\Response\VideoListResponse;
use MovingImage\Client\VM6\Interfaces\ApiClientInterface;
use MovingImage\Client\VM6\Traits\LoggerAwareTrait;

abstract class AbstractApiClient extends AbstractCoreApiClient implements ApiClientInterface
{
    use LoggerAwareTrait;

    public function getVideos(VideoQueryCriteria $criteria)
    {
        $videoListResponse = $this->deserialize($this->makeRequest('GET', 'get_video_object_list.json', [
            'query' => $criteria->getCriteriaData(),
        ])->getBody(), VideoListResponse::class);

        return $videoListResponse->getVideos();
    }
}
