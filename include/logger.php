<?php
function generatelogs_new($type, $data = [], $localpath = '')
{
    $logDir = __DIR__ . '/logs/';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logFile = $logDir . date('Y-m-d') . '.log';
    $log = [
        'time'   => date('Y-m-d H:i:s'),
        'status' => 'INFO',
        'type'   => $type,
        'data'   => $data,
    ];
    file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT) . PHP_EOL . PHP_EOL, FILE_APPEND);
}
