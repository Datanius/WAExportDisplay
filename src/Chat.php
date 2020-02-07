<?php

class Chat
{
    /**
     * @var string
     */
    public $name;

    const CHATS_DIR = __DIR__."/../resources/chats";

    /**
     * Chat constructor.
     * @param string $name
     * @throws Exception
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        if( ! file_exists($this->getChatFolder())) {
            throw new Exception("Chat {$this->name} does not exist.");
        }
    }

    /**
     * @return array
     */
    public static function allChats(): array
    {
        return array_diff(scandir(self::CHATS_DIR), [".", ".."]);
    }

    /**
     * @return string
     */
    public function getChatFolder(): string
    {
        return self::CHATS_DIR . "/" . $this->name;
    }

    /**
     * @return string
     */
    public function getChatFile(): string
    {
        $folder = $this->getChatFolder();
        foreach (scandir($folder) as $file) {
            if (pathinfo($file)["extension"] === "txt") {
                return $folder . "/" . $file;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getImagesFolder(): string
    {
        return "./images/chats/{$this->name}";
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        $contents = file_get_contents($this->getChatFile());
        $lines = explode("\n", $contents);
        $messages = [];

        for($i = 0; $i < count($lines); $i++) {
            if( ! isset($lines[$i])) {
                break;
            }
            $line = $lines[$i];
            $skip = 0;
            while(isset($lines[$i + $skip + 1]) && ! preg_match("/^\d+.\d+.\d+, \d+:\d+/", $lines[$i + $skip + 1])) {
                $line .= "\n" . $lines[$i + $skip + 1];
                $skip++;
            }
            $i += $skip;
            $Message = Message::fromString($line);
            if(empty($Message->content)) {
                continue;
            }
            $Message->setChat($this);
            $messages[] = $Message;
        }
        return $messages;
    }
}