<?php

namespace MovingImage\Client\VM6\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\PostDeserialize;
use MovingImage\Client\VM6\Entity\Channel;

/**
 * Class ChannelList
 * @package MovingImage\Client\VM6\Entity
 *
 * @author Robert Szeker <robert.szeker@movingimage.com>
 */
class ChannelList
{
    const ROOT_CHANNEL_ID = 0;

    /**
     * @Type("ArrayCollection<MovingImage\Client\VM6\Entity\Channel>")
     * @SerializedName("rubriclist")
     * @var ArrayCollection
     */
    private $channelList;

    /**
     * @PostDeserialize
     * @param int $parentId
     * @return ArrayCollection
     */
    private function buildChannelTree($parentId = self::ROOT_CHANNEL_ID)
    {
        $children = $this->getChildren($parentId);
        $children->map(function (Channel $channel) use ($parentId) {
            $channel->setChildren($this->buildChannelTree($channel->getId()));
            if ($parentId != self::ROOT_CHANNEL_ID) {
                $channel->setParent($this->getChannel($parentId));
            }
        });

        return $children;
    }

    /**
     * Returns list of channels, which are the children of $parentId
     *
     * @param $parentId
     * @return ArrayCollection
     */
    public function getChildren($parentId)
    {
        return $this->channelList->filter(function (Channel $channel) use ($parentId) {
            return $channel->getParentId() == $parentId;
        });
    }

    /**
     * @param int $channelId
     * @return Channel
     */
    public function getChannel($channelId = self::ROOT_CHANNEL_ID)
    {
        if ($channelId == self::ROOT_CHANNEL_ID) {
            return $this->getRootChannel();
        }

        return $this->channelList->filter(function (Channel $channel) use ($channelId) {
            return $channel->getId() == $channelId;
        })->first();
    }

    /**
     * @return Channel
     */
    private function getRootChannel()
    {
        $channel = new Channel();
        $channel->setId(self::ROOT_CHANNEL_ID);
        $channel->setChildren($this->getChildren(self::ROOT_CHANNEL_ID));

        return $channel;
    }
}