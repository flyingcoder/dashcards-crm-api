<?php


namespace App;

use App\Traits\HasFileTrait;
use Bnb\Laravel\Attachments\Attachment as BaseAttachment;

/**
 * Class Attachment
 * @package App
 */
class Attachment extends BaseAttachment
{
    use HasFileTrait;
    /**
     * @var array
     */
    protected $appends = ['file_url', 'category'];

    /**
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return $this->url;
    }

    /**
     * @return bool|int|string
     */
    public function getCategoryAttribute()
    {
        if (!$this->filetype)
            return false;
        return $this->getFileCategoryByMimeType($this->filetype);
    }
}