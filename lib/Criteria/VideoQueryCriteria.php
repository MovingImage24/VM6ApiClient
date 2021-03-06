<?php

namespace MovingImage\Client\VM6\Criteria;

/**
 * Class VideoQueryCriteria.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoQueryCriteria
{
    /**
     * @const int Always retrieve the full objects
     */
    const LIST_TYPE_FULL = 2;

    const SORT_ORDER_DESC = 0;
    const SORT_ORDER_ASC = 1;

    const PUBLICATION_STATES = [
        'published' => 1,
        'not_published' => 2,
        'all' => 3,
    ];

    /**
     * @var array
     */
    private $channels = [];

    /**
     * @var int|null
     */
    private $limit = null;

    /**
     * @var int|null
     */
    private $page = null;

    /**
     * @var int|null
     */
    private $offset = null;

    /**
     * @var string|null
     */
    private $sortColumn = null;

    /**
     * @var int|null
     */
    private $sortByColumnOrder = null;

    /**
     * @var string|null
     */
    private $searchTerm = null;

    /**
     * @var bool
     */
    private $includeSubChannels = true;

    /**
     * @var int|null
     */
    private $publicationState = null;

    /**
     * Possible values: 'published', 'not_published', 'all'.
     *
     * @param $publicationState
     *
     * @throws \Exception
     */
    public function setPublicationState($publicationState)
    {
        if (array_key_exists($publicationState, self::PUBLICATION_STATES)) {
            $this->publicationState = self::PUBLICATION_STATES[$publicationState];
        } else {
            throw new \Exception('publication state is unknown');
        }
    }

    /**
     * @return string|null
     */
    public function getPublicationState()
    {
        $states = array_flip(self::PUBLICATION_STATES);

        return is_null($this->publicationState)
            ? null
            : $states[$this->publicationState];
    }

    /**
     * @param bool $includeSubChannels
     *
     * @return $this
     */
    public function setIncludeSubChannels($includeSubChannels)
    {
        $this->includeSubChannels = $includeSubChannels;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIncludeSubChannels()
    {
        return $this->includeSubChannels;
    }

    /**
     * @param $searchTerm
     *
     * @return $this
     */
    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * Filter video list by a single channel ID - this overrides any
     * previously set criterias to filter by multiple channel IDs if called.
     *
     * @param int $channelId
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setChannelId($channelId)
    {
        if (!is_numeric($channelId)) {
            throw new \Exception('Channel ID should be numeric.');
        }

        $this->channels = [$channelId];

        return $this;
    }

    /**
     * Filter video list by one or multiple channel IDs.
     *
     * @param int[] $channelIds
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setChannelIds(array $channelIds)
    {
        foreach ($channelIds as $channelId) {
            if (!is_numeric($channelId)) {
                throw new \Exception('Channel ID should be numeric.');
            }
        }

        $this->channels = $channelIds;

        return $this;
    }

    /**
     * @return array
     */
    public function getChannelIds()
    {
        return $this->channels;
    }

    /**
     * Limit result set by a certain amount of videos.
     *
     * @param int $limit
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        if (!is_numeric($limit)) {
            throw new \Exception('Video limit per page/offset should be numeric.');
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * Set offset of video list - this requires 'limit' to be set.
     *
     * @param int $offset
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        if (!is_numeric($offset)) {
            throw new \Exception('Video offset should be numeric.');
        }

        $this->offset = $offset;

        return $this;
    }

    /**
     * Set page of results - functions the same as offset but with
     * page number instead of having to keep count yourself.
     *
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the column on which to sort.
     * Possible values are:.
     *
     * - id
     * - name
     * - display_date
     * - custom_id_1
     * - custom_id_2
     * - custom_id_3
     * - versiondate
     *
     * @param string $column
     *
     * @return $this
     */
    public function setSortColumn($column)
    {
        $this->sortColumn = $column;

        return $this;
    }

    /**
     * Set how to sort.
     * Allowed values: 'desc', 'asc'.
     *
     * @param string $order
     *
     * @return $this
     */
    public function setSortByColumnOrder($order)
    {
        switch ($order) {
            case 'desc':
                $this->sortByColumnOrder = self::SORT_ORDER_DESC;
                break;
            case 'asc':
                $this->sortByColumnOrder = self:: SORT_ORDER_ASC;
                break;
        }

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    public function getCriteriaData()
    {
        $criteriaData = [
            'object_list_type' => self::LIST_TYPE_FULL,
        ];

        if (!empty($this->publicationState)) {
            $criteriaData['show_type'] = $this->publicationState;
        }

        if (!empty($this->channels)) {
            $criteriaData['rubric_list'] = implode(',', $this->channels);
        }

        if (!is_null($this->limit)) {
            $criteriaData['videosperpage'] = $this->limit;
        }

        if (!is_null($this->page)) {
            $criteriaData['page'] = $this->page;
        }

        if (!is_null($this->sortColumn)) {
            if ($this->sortColumn == 'views') {
                $criteriaData['sort'] = 2; // Sort by 'most popular'
            } else {
                $criteriaData['sort_by_column'] = $this->sortColumn;

                if (!is_null($this->sortByColumnOrder)) {
                    // Put this here because providing this field doesn't
                    // do anything if you don't provide 'sort_by_column'
                    if (!in_array($this->sortByColumnOrder, array(self::SORT_ORDER_ASC, self::SORT_ORDER_DESC))) {
                        throw new \Exception('Invalid sort order provided.');
                    }

                    $criteriaData['sort_by_column_order'] = $this->sortByColumnOrder;
                }
            }
        }

        if (!is_null($this->searchTerm)) {
            $criteriaData['searchstring'] = $this->searchTerm;
        }

        if (!is_null($this->page)) {
            $criteriaData['page'] = $this->page;
        } elseif (!is_null($this->offset)) {
            if (is_null($this->limit)) {
                throw new \Exception('Criteria \'offset\' can only be used in conjunction with \'limit\'.');
            }

            $criteriaData['page'] = ceil($this->offset / $this->limit) + 1;
        }

        return $criteriaData;
    }
}
