<?php

require_once 'vendor/autoload.php';
require_once 'stopwatch.php';

try {

    // include config
    $config = require 'config.php';

    // connect to database
    $mysqli = new mysqli($config['database_host'], $config['database_user'], $config['database_password'], $config['database_name']);

    if (!empty($mysqli->connect_errno)) {
        throw new \Exception($mysqli->connect_error, $mysqli->connect_errno);
    }

    // create a bot
    $bot = new \TelegramBot\Api\Client($config['bot_token'], $config['bot_tracker']);

    // create keyboards collection
    $keyboards = [
        'go' => new \TelegramBot\Api\Types\ReplyKeyboardMarkup([['/go', '/status']], null, true),
        'stop' => new \TelegramBot\Api\Types\ReplyKeyboardMarkup([['/stop', '/status']], null, true)
    ];

    $bot->command('start', function ($message) use ($bot, $keyboards) {

        $answer = 'Howdy! Welcome to the stopwatch. Use bot commands or keyboard to control your time.';

        $bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboards['go']);
    });

    $bot->command('go', function ($message) use ($bot, $keyboards, $mysqli) {

        $stopwatch = new Stopwatch($mysqli, $message->getChat()->getId());
        $stopwatch->start();

        $bot->sendMessage($message->getChat()->getId(), 'Stopwatch started. Go!', false, null, null, $keyboards['stop']);
    });

    $bot->command('status', function ($message) use ($bot, $keyboards, $mysqli) {

        $stopwatch = new Stopwatch($mysqli, $message->getChat()->getId());
        $answer = $stopwatch->status();

        if (empty($answer)) {
            $answer = 'Timer is not started.';
            $keyboard = $keyboards['go'];
        } else {
            $keyboard = $keyboards['stop'];
        }

        $bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
    });

    $bot->command('stop', function ($message) use ($bot, $keyboards, $mysqli) {

        $stopwatch = new Stopwatch($mysqli, $message->getChat()->getId());
        $answer = $stopwatch->status();

        if (!empty($answer)) {
            $answer = 'Your time is ' . $answer . PHP_EOL;
        }

        $stopwatch->stop();

        $bot->sendMessage($message->getChat()->getId(), $answer . 'Stopwatch stopped. Enjoy your time!', false, null, null, $keyboards['go']);
    });

    // run, bot, run!
    $bot->run();

} catch (\Exception $e) {
    // here you can add error logging
}
