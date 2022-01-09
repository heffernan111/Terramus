<?php


\Routes::get('/', 'homeController@index');
\Routes::get('/test', 'testController@index');
\Routes::get('/home/copy/{name}', 'testController@index');
\Routes::get('/home/house/{name}/{dog}', 'testController@index');
