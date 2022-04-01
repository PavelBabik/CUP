<?php

namespace App\Classes;

class TgBot
{
    // (TgBot::getInstance())->tgMessage('test'));
    private static $instances = [];

    private $token;
    private $chat;

    protected function __construct()
    {
        $this->token = getenv('TG_BOT_TOKEN');
        $this->chat = getenv('TG_BOT_CHAT');
    }

    protected function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            // Обратите внимание, что здесь мы используем ключевое слово
            // "static"  вместо фактического имени класса. В этом контексте
            // ключевое слово "static" означает «имя текущего класса». Эта
            // особенность важна, потому что, когда метод вызывается в
            // подклассе, мы хотим, чтобы экземпляр этого подкласса был создан
            // здесь.

            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }

    public function tgMessage($text)
    {
        $sendToTelegram = fopen("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$this->chat}&parse_mode=html&text={$text}","r");

        if (!$sendToTelegram) {
//            echo "Error";
        }
    }

    public function tgPhoto($text)
    {
        $sendToTelegram = fopen("https://api.telegram.org/bot{$this->token}/sendPhoto?photo=https://api.influenceup.me/files/getIMG/447ea64b223a11a945dbea2a7c4a8efe&chat_id={$this->chat}&parse_mode=html&caption={$text}","r");

        if (!$sendToTelegram) {
//            echo "Error";
        }
    }
}