<?php

namespace Lib\Database;

class ResponseData
{
    public function __construct()
    {
    }

    /**
     * @param array $data
     * @param int $recordCount
     * @return array
     */
    public function success(array $data, int $recordCount)
    {
        $response = [
            'success' => true,
            'record_count' => $recordCount,
            'data' => $data,
        ];

        return $response;
    }

    /**
     * @param string $message
     * @return array
     */
    public function error(string $message)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return $response;
    }
}