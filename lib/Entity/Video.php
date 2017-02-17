<?php

namespace MovingImage\Client\VM6\Entity;

use MovingImage\Meta\Interfaces\VideoInterface;
use JMS\Serializer\Annotation\Type;

class Video implements VideoInterface
{
    /**
     * @const string
     */
    const STATUS_APPROVED = 3;

    /**
     * @Type("integer")
     *
     * @var int
     */
    private $id;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $name;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $description;

    /**
     * @Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @var \DateTime
     */
    private $display_date;

    /**
     * @Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @var \DateTime
     */
    private $version_date;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $thumbnail_url;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $thumbnail_url_lq;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $thumbnail_url_mq;

    /**
     * @Type("integer")
     *
     * @var int
     */
    private $status;

    /**
     * @Type("integer")
     *
     * @var int
     */
    private $length_in_seconds;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $keywords;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_1;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_2;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_3;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_4;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_5;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_6;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_7;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_8;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_9;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $custom_data_field_10;

    /**
     * @var Still[]
     */
    private $stills;

    /**
     * @var Keyword[]
     */
    private $keywordObjs;

    /**
     * @var CustomMetaData[]
     */
    private $customMetaDatas;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedDate()
    {
        return $this->display_date;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadDate()
    {
        return $this->display_date;
    }

    /**
     * {@inheritdoc}
     */
    public function getModifiedDate()
    {
        return $this->version_date;
    }

    /**
     * Generate a list of Still entities based on
     * other properties that come from VM 6.
     *
     * @return Still[]
     */
    public function getStills()
    {
        if (!isset($this->stills)) {
            $this->stills = [
                new Still($this->thumbnail_url, 'default'),
                new Still($this->thumbnail_url_lq, 'lq'),
                new Still($this->thumbnail_url_mq, 'mq'),
            ];
        }

        return $this->stills;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return $this->getStatus() === self::STATUS_APPROVED;
    }

    /**
     * {@inheritdoc}
     */
    public function getThumbnail()
    {
        return $this->thumbnail_url;
    }

    /**
     * {@inheritdoc}
     */
    public function getLength()
    {
        return $this->length_in_seconds;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeywords()
    {
        if (!isset($this->keywordObjs)) {
            $this->keywordObjs = [];
            $keywords = explode(',', $this->keywords);

            foreach ($keywords as $keyword) {
                $this->keywordObjs[] = new Keyword($keyword);
            }
        }

        return $this->keywordObjs;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomMetadata()
    {
        if (!isset($this->customMetaDatas)) {
            $this->customMetaDatas = [
                new CustomMetaData('custom_data_field_1', $this->custom_data_field_1),
                new CustomMetaData('custom_data_field_2', $this->custom_data_field_2),
                new CustomMetaData('custom_data_field_3', $this->custom_data_field_3),
                new CustomMetaData('custom_data_field_4', $this->custom_data_field_4),
                new CustomMetaData('custom_data_field_5', $this->custom_data_field_5),
                new CustomMetaData('custom_data_field_6', $this->custom_data_field_6),
                new CustomMetaData('custom_data_field_7', $this->custom_data_field_7),
                new CustomMetaData('custom_data_field_8', $this->custom_data_field_8),
                new CustomMetaData('custom_data_field_9', $this->custom_data_field_9),
                new CustomMetaData('custom_data_field_10', $this->custom_data_field_10),
            ];
        }

        return $this->customMetaDatas;
    }
}
