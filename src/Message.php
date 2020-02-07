<?php

class Message
{
    /**
     * @var Chat
     */
    public $Chat;

    /**
     * @var DateTime
     */
    public $time;

    /**
     * @var string
     */
    public $author;

    /**
     * @var string
     */
    public $content;

    /**
     * @var Attachment
     */
    public $attachment;

    /**
     * @param string $message
     * @return Message
     */
    public static function fromString(string $message): Message
    {
        preg_match("/(?P<time>\d+\.\d+\.\d+, \d+:\d+) - (?P<author>[\w ]+):(?P<content>.+)/s", $message, $matches);
        if ( ! isset($matches["content"])) {
            return new Message();
        }

        $Message = new Message();
        $Message->time = DateTime::createFromFormat("d.m.y, H:i", $matches["time"]);
        $Message->author = $matches["author"];
        $Message->content = $matches["content"];
        $Message->attachment = Attachment::fromMessage($Message);
        return $Message;
    }

    public function setChat(Chat $Chat)
    {
        $this->Chat = $Chat;
    }

    /**
     * @return string
     */
    public function getAttachmentLink(): string
    {
        return $this->Chat->getImagesFolder() . "/" . $this->attachment->file;
    }

    /**
     * @return bool
     */
    public function hasImage(): bool
    {
        return $this->attachment !== null && $this->attachment->isImage();
    }

    /**
     * @return bool
     */
    public function hasVideo(): bool
    {
        return $this->attachment !== null && $this->attachment->isVideo();
    }

    /**
     * @return bool
     */
    public function isUserMessage(): bool
    {
        return $this->author === USER;
    }
}