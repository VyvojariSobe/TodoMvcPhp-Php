<?php
return [
  'database' => __DIR__ . '/../database.sqlite',
  'routes'   => [
    'clear-completed'                     => 'Todos:clearCompleted',
    'new'                                 => 'Todos:new',
    '(\d+)/remove'                        => 'Todos:remove',
    '(\d+)/change-value'                  => 'Todos:changeValue',
    '(\d+)/change-status/(check|uncheck)' => 'Todos:changeStatus',
    '(\w+|$)'                             => 'Todos:default',
  ]
];
