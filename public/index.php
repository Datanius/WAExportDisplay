<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__."/../vendor/autoload.php";

try {
    $Chat = new Chat($_GET["chat"]);
} catch (Exception $ex) {
    $error = $ex->getMessage();
    $Chat = null;
}

?>

<link href="stylesheets/main.css" rel="stylesheet"/>

<script>
    window.onload = function() {
        window.scrollTo(0, document.querySelector(".chat").scrollHeight);
    }
</script>

<body>
    <div class="menu">
        <h2>Chats</h2>
        <div>
            <?php foreach(Chat::allChats() as $chat) { ?>
                <a class="<?php echo $Chat && $Chat->name === $chat ? "active" : ""; ?>"
                    href="?chat=<?php echo $chat; ?>">
                    <?php echo $chat; ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="chat">
        <?php if(isset($error)) { ?>
            <div class="error-box">
                <div class="error">
                    <?php echo $error; ?>
                </div>
            </div>
        <?php } else { ?>
            <?php foreach ($Chat->getMessages() as $Message) { ?>
                <div class="message" style="float: <?php echo $Message->isUserMessage() ? "right" : "left"; ?>">
                    <div class="content">
                        <?php if($Message->hasImage()) { ?>
                            <a target="_blank" href="<?php echo $Message->getAttachmentLink(); ?>">
                                <img src="<?php echo $Message->getAttachmentLink(); ?>" />
                            </a>
                        <?php } elseif($Message->hasVideo()) { ?>
                            <video controls="controls" src="<?php echo $Message->getAttachmentLink(); ?>"></video>
                        <?php } else { ?>
                            <?php echo nl2br(htmlentities($Message->content)); ?>
                        <?php } ?>
                    </div>
                    <div class="info">
                        <div><?php echo $Message->time->format("d.m.Y H:i"); ?></div>
                        <div class="author"><?php echo $Message->author; ?></div>
                    </div>
                </div>
                <div style="clear: both"></div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
