#!/usr/bin/env php
<?php

use ChinaDivisions\Division;
use GuzzleHttp\Exception\BadResponseException;
use ChinaDivisions\Exceptions\ResponseException;

require __DIR__ . '/../vendor/autoload.php';

define('MAX_LEVEL', getenv('MAX_LEVEL') ?: 4); // 1.国家 -> 2.省 -> 3.市 -> 4.区县 -> 5.街道乡镇
define('CHILDREN_KEY', getenv('CHILDREN_KEY') ?: 'children'); // 子节点键名
const DIVISION_FILTER = 'division_filter';

function division_filter(array $data): ?array
{
    if ($data['isdeleted'] != 'false') { // 剔除已废弃的区划
        return null;
    }

    return $data;
    return [
        'id' => intval($data['divisionId']),
        'level' => intval($data['divisionLevel']),
        'name' => $data['divisionName'],
        // 可追加更多数据或直接返回 $data
    ];
}

$deep = function (Division $division) use (&$deep) {
    try {
        $children = $division->children();
    } catch (ResponseException $ex) {
        // 若错误码为 201，错误为 `DivisionId is illegal.`，表示无子区划。
        $children = $ex->getCode() === 201 ? [] : $deep($division);
    }

    return $children;
};

$build = function (Division $division) use (&$build, $deep) {
    $data = $division->self();

    $depth = intval($data['divisionLevel']);

    echo str_repeat('-', $depth) . ' ' . $data['divisionName'] . PHP_EOL;

    if (function_exists(DIVISION_FILTER)) {
        $data = call_user_func(DIVISION_FILTER, $data);
    }

    if ($data && $depth < MAX_LEVEL) {
        foreach ($deep($division) as $child) {
            if ($descendants = $build($child)) {
                $data[CHILDREN_KEY][] = $descendants;
            }
        }
    }

    return $data;
};

$division = new Division(1);

try {
    $collection = $build($division);
} catch (BadResponseException $ex) {
    echo '***' . $ex->getResponse()->getBody()->getContents() . '***';
    exit;
}

file_put_contents(
    sprintf('%s/output/level%s.json', __DIR__, MAX_LEVEL),
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);
