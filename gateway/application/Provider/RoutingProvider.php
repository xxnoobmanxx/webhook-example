<?php

namespace App\Provider;

use App\Http\Controller;
use Corviz\DI\Provider;
use Corviz\Routing\Route;

class RoutingProvider extends Provider
{
    /**
     * Include application routes here.
     * Example:.
     *
     * //Single route.
     * Route::get('/', [
     *     'controller' => Controller\Home::class,
     *     'action'     => 'index',
     *     'alias'      => 'home.index', //Optional
     *     'middleware' => ['middleware1', 'middleware2'] //Optional
     * ]);
     *
     * //Groups.
     * Route::group('contact', function(){
     *     Route::get('form', [/* ... *\/]);
     *     Route::post('form-submit', [/* ... *\/])
     * });
     */
    public function register()
    {
        Route::get('/', [
            'controller' => Controller\PaymentController::class,
            'action'     => 'index',
            'alias'      => 'home.index',
        ]);

        Route::post('update-status', [
            'controller' => Controller\PaymentController::class,
            'action'     => 'updateStatus',
        ]);

        Route::post('receive', [
            'controller' => Controller\PaymentController::class,
            'action'     => 'receive',
        ]);
    }
}
