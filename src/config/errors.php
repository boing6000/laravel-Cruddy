<?php

return [

    'not_found' => [
        'title'  => 'The requested resource could not be found but may be available again in the future. Subsequent requests by the client are permissible.',
        'detail' => 'The resource you were looking for was not found.',
    ],

    'not-acceptable' => [
        'title'  => 'Not Acceptable',
        'detail' => 'Accept was not %s.',
    ],

    'unsupported-media-type' => [
        'title'  => 'Unsupported Media Type',
        'detail' => 'Content-Type was not'.config('jsonapi.content-type'),
    ],

    'invalid_sort' => [
        'title'  => 'Invalid Query Parameter.',
        'detail' => 'The resource `%s` does not have an `%s` sorting option.',
        'source' => ['type' => 'parameter', 'value' => '%s.%s'],
    ],

    'invalid_filter' => [
        'title'  => 'Invalid Query Parameter.',
        'detail' => 'The resource `%s` does not have an `%s` filter option.',
        'source' => ['type' => 'parameter', 'value' => '%s.%s'],
    ],

    'invalid_include' => [
        'title'  => 'Invalid Query Parameter',
        'detail' => 'The resource does not have an `%s` relationship path.',
        'source' => ['type' => 'parameter', 'value' => '%s'],
    ],

    'invalid_get' => [
        'title'  => 'Invalid Query Parameter',
        'detail' => '%s is not an allowed query parameter.',
        'source' => ['type' => 'parameter', 'value' => '%s'],
    ],

    'invalid_attribute' => [
        'title'  => 'Invalid Attribute',
        'detail' => '%s',
        'source' => ['type' => 'pointer', 'value' => '%s'],
    ],

];
