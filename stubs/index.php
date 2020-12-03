<?php
/**
 * Created by PhpStorm.
 * User: Madman
 * Date: 2020/11/25
 * Time: 16:14
 */

$config_path = $argv[1];
$pos = strpos($config_path, '--config=');
$config_path = substr($config_path, $pos + 9);
$cmd = strtolower($argv[2] ?? 'start');

$_SERVER['argv'][0] = $argv[0] = __DIR__ . '/artisan';
$_SERVER['argv'][1] = $argv[1] = 'tars:server';
$_SERVER['argv'][2] = $argv[2] = '--cmd=' . $cmd;
$_SERVER['argv'][3] = $argv[3] = '--config=' . $config_path;
$_SERVER['argc'] = $argc = count($_SERVER['argv']);

include_once __DIR__ . '/artisan';