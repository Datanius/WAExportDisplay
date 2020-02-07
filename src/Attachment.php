<?php

class Attachment
{
    /**
     * @var string
     */
    public $file;

    const VIDEO_EXTENSIONS = ["mp4"];
    const IMAGE_EXTENSIONS = ["jpg", "png"];

    /**
     * @param Message $Message
     * @return Attachment|null
     */
    public static function fromMessage(Message $Message): ?Attachment
    {
        if( ! strstr($Message->content, ATTACHMENT_IDENTIFIER)) {
            return null;
        }

        preg_match("/(?P<filename>.+\.\w+)/", $Message->content, $matches);

        $Attachment = new Attachment();
        $Attachment->file = preg_replace('/[^\PC\s]/u', '', trim($matches["filename"]));
        return $Attachment;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return pathinfo($this->file)["extension"];
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return in_array($this->getExtension(), self::IMAGE_EXTENSIONS);
    }

    /**
     * @return bool
     */
    public function isVideo(): bool
    {
        return in_array($this->getExtension(), self::VIDEO_EXTENSIONS);
    }
}