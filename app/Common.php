<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

define('AUTH_TYPE_SIMPLE', 0);
define('AUTH_TYPE_LINK', 1);

function check_required_fields(array $required_fields, array $array_to_search): array
{
    $empty = [];
    foreach ($required_fields as $required_field) {
        if (!isset($array_to_search[$required_field])) {
            $empty[] = $required_field;
        }
        if (isset($array_to_search[$required_field]) && $array_to_search[$required_field] == '') {
            $empty[] = $required_field;
        }
    }

    return [
        'status' => sizeof($empty) == 0,
        'fields' => $empty
    ];
}

function delete_timestamps(&$payload): void
{
    try {
        unset($payload['created_at']);
        unset($payload['updated_at']);
        unset($payload['deleted_at']);
    } catch (Exception $e) {

    }
}

function generateRandomString($length = 10, $only = 'all')
{
    switch ($only) {
        case 'numbers':
            $characters = '0123456789';
            break;
        case 'chars':
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        default:
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
    }
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function prp($a, $b = '#fff', $c = '#000')
{
    echo "<pre style='background-color: {$b}; color: {$c};'>";
    print_r($a);
    echo "</pre>";
}

function vdp($a, $b = '#fff', $c = '#000')
{
    echo "<pre style='background-color: {$b}; color: {$c};'>";
    var_dump($a);
    echo "</pre>";
}

function hr()
{
    echo "<hr>";
}

function br($c = 1)
{
    for ($i = 0; $i < $c; $i++) {
        echo "<br>";
    }
}