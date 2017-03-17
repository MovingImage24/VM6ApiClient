<?php

namespace MovingImage\Client\VM6\ApiClient;

use MovingImage\Client\VM6\Criteria\VideoQueryCriteria;
use MovingImage\Client\VM6\Entity\Channel;
use MovingImage\Client\VM6\Entity\ChannelList;
use MovingImage\Client\VM6\Entity\EmbedCode;
use MovingImage\Client\VM6\Entity\Response\VideoListCountResponse;
use MovingImage\Client\VM6\Entity\Response\VideoListResponse;
use MovingImage\Client\VM6\Entity\Video;
use MovingImage\Client\VM6\Interfaces\ApiClientInterface;
use MovingImage\Client\VM6\Traits\LoggerAwareTrait;

/**
 * Class AbstractApiClient.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
abstract class AbstractApiClient extends AbstractCoreApiClient implements ApiClientInterface
{
    use LoggerAwareTrait;

    /**
     * @var ChannelList
     */
    private $channelList;

    /**
     * {@inheritdoc}
     */
    public function getVideos(VideoQueryCriteria $criteria)
    {
        if ($criteria->isIncludeSubChannels()) {
            $criteria->setChannelIds($this->getChannelIdsInclusiveSubChannels($criteria->getChannelIds()));
        }
        $videoListResponse = $this->deserialize($this->makeRequest('GET', 'get_video_object_list.json', [
            'query' => $criteria->getCriteriaData(),
        ])->getBody(), VideoListResponse::class);

        return $videoListResponse->getVideos();
    }

    /**
     * Returns ChannelList containing all channels belonging to the video manager.
     *
     * @return ChannelList
     */
    private function getChannelList()
    {
        if (empty($this->channelList)) {
            $response = $this->makeRequest('GET', 'get_rubric_object_list.json', []);
            $this->channelList = $this->deserialize($response->getBody()->getContents(), ChannelList::class);
        }

        return $this->channelList;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannels($channelId = ChannelList::ROOT_CHANNEL_ID)
    {
        return $this->getChannelList()->getChannel($channelId);
    }

    /**
     * Returns an array containing all channel IDs inclusive sub channels.
     *
     * @param $channelIds
     * @return array
     */
    private function getChannelIdsInclusiveSubChannels($channelIds)
    {
        $subChannelIds = [];

        foreach ($channelIds as $channelId) {
            $subChannels = $this->getChannelList()->getChildren($channelId);
            $subChannelIds = array_merge($subChannelIds, $subChannels->map(function (Channel $channel) {
                return $channel->getId();
            })->toArray());
        }

        return empty($subChannelIds)
            ? $channelIds
            : array_merge($channelIds, $this->getChannelIdsInclusiveSubChannels($subChannelIds));
    }

    /**
     * {@inheritdoc}
     */
    public function getVideoCount(VideoQueryCriteria $criteria)
    {
        if ($criteria->isIncludeSubChannels()) {
            $criteria->setChannelIds($this->getChannelIdsInclusiveSubChannels($criteria->getChannelIds()));
        }
        $videoListCountResponse = $this->deserialize($this->makeRequest('GET', 'get_video_list_count.json', [
            'query' => $criteria->getCriteriaData(),
        ])->getBody(), VideoListCountResponse::class);

        return $videoListCountResponse->getVideoCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getVideo($videoId)
    {
        return $this->deserialize($this->makeRequest('GET', 'get_video_object.json', [
            'query' => [
                'videoid' => $videoId,
            ],
        ])->getBody(), Video::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmbedCode(Video $video, $skinId, $type = EmbedCode::TYPE_JAVASCRIPT, $tokenProtected = false)
    {
        $embedCode = $this->deserialize($this->makeRequest('GET', 'get_https_player_code_for_video.json', [
            'query' => [
                'videoid' => $video->getId(),
                'skinid' => $skinId,
                'type' => $type,
            ],
        ])->getBody(), EmbedCode::class);

        if ($tokenProtected !== false) {
            if (is_null($this->unlockTokenGenerator)) {
                throw new \Exception('Attempting to append unlock token to embed code, but no unlock code
                generator was instantiated.');
            }

            $token = $this->unlockTokenGenerator->generate($video->getId());
            $embedCode->setUnlockToken($token);
        }

        return $embedCode;
    }
}
