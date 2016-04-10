<?php

/**
 * Class Stopwatch is provided to add, get status and remove stopwatch info from mysqli database
 * 
 * @author Anton Bagaiev <tony@dontgiveafish.com>
 * @copyright Copyright (c) 2016
 */

class Stopwatch
{
    /** @var mysqli */
    private $mysqli;
    /** @var int */
    private $stopwatch_id;

    /**
     * Stopwatch constructor
     * @param mysqli $mysqli
     * @param $stopwatch_id
     */
    public function __construct(\mysqli $mysqli, $stopwatch_id)
    {
        $this->mysqli = $mysqli;
        $this->stopwatch_id = intval($stopwatch_id);
    }

    /**
     * Save or update row with stopwatch id an Unix time
     * @return bool|mysqli_result
     */
    public function start()
    {
        $timestamp = time();

        $query = "
            INSERT INTO  `stopwatch` (
            `chat_id` ,
            `timestamp`
            )
            VALUES (
            '$this->stopwatch_id', '$timestamp'
            )
            ON DUPLICATE KEY UPDATE    
            timestamp = '$timestamp'        
        ";

        return $this->mysqli->query($query);
    }

    /**
     * Delete row with stopwatch id
     * @return bool|mysqli_result
     */
    public function stop()
    {
        $query = "
            DELETE FROM `stopwatch`
            WHERE `chat_id` = $this->stopwatch_id  
        ";
        return $this->mysqli->query($query);
    }

    /**
     * Find row with stopwatch id and return difference in seconds from saved Unix time and current time
     * @return string
     */
    public function status()
    {
        $query = "
            SELECT `timestamp`
            FROM  `stopwatch`
            WHERE `chat_id` = $this->stopwatch_id        
        ";

        $timestamp = $this->mysqli->query($query)->fetch_row();

        if (!empty($timestamp)) {
            return gmdate("H:i:s", time() - reset($timestamp));
        }
    }

}