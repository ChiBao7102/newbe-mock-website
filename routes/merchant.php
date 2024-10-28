<?php
$router->group([
    'as' => 'merchants',
    'prefix' => 'merchants',
], function () use ($router) {
    $router->post('/createTransaction', [
        'as' => 'createTransaction',
        'uses' => 'MerchantController@createTransaction',
    ]);

    $router->post('/createRemittance', [
        'as' => 'createRemittance',
        'uses' => 'MerchantController@createRemittance',
    ]);

    $router->post('/callback', [
        'as' => 'callback',
        'uses' => 'MerchantController@callback',
    ]);

    $router->get('/callback-success', [
        'as' => 'callback',
        'uses' => 'MerchantController@callbackSuccess',
    ]);

    $router->get('/callback-fail', [
        'as' => 'callback',
        'uses' => 'MerchantController@callbackFail',
    ]);

    $router->post('/callback-payout', [
        'as' => 'callback',
        'uses' => 'MerchantController@callbackPayout',
    ]);

    $router->get('/getTransaction', [
        'as' => 'callback',
        'uses' => 'MerchantController@retrieve',
    ]);

    $router->get('/gettype', [
        'as' => 'gettype',
        'uses' => 'MerchantController@gettype',
    ]);
});
