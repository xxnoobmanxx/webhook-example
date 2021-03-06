<?php

namespace Tests\Corviz\Framework\Routing;

use Corviz\Http\Request;
use Corviz\Routing\Map;
use Corviz\Routing\Route;

class MapTest extends \PHPUnit_Framework_TestCase
{
    /*
     * Method: getCurrentRoute
     *------------------------------
     */

    public function testGetCurrentRouteAttendsOnlySpecifiedMethod()
    {
        $routeStr = '/posts/1/';

        //setup
        $this->clearMap();
        Route::get('posts/{id}', ['controller' => 'Post', 'alias' => 'route1']);

        /*
         * Testing with correct method
         */
        $this->setCurrentRequest(Request::METHOD_GET, $routeStr);

        $data = Map::getCurrentRoute();
        $this->assertEquals('route1', $data['alias'], 'route1 should attend GET request');
        unset($data);

        /*
         * Testing with wrong method
         */
        $this->setCurrentRequest(Request::METHOD_POST, $routeStr);

        $data = Map::getCurrentRoute();
        $this->assertEquals(true, is_null($data), 'route1 should not attend POST request');
    }

    public function testGetCurrentRouteShouldSelectCorrectInformationForAmbiguousPaths()
    {
        //setup
        $this->clearMap();

        Route::all('posts/edit/{id}', ['controller' => 'Test', 'alias' => 'route1']);
        Route::all('{somePage}', ['controller' => 'Test', 'alias' => 'route2']);
        Route::all('posts/{id}', ['controller' => 'Test', 'alias' => 'route3']);

        //Test 1
        $this->setCurrentRequest(Request::METHOD_GET, '/posts/1/');

        $data = Map::getCurrentRoute();
        $this->assertEquals('route3', $data['alias'], 'incorrect route selected in test case 1');

        //Test 2
        $this->setCurrentRequest(Request::METHOD_GET, '/posts/edit/1/');

        $data = Map::getCurrentRoute();
        $this->assertEquals('route1', $data['alias'], 'incorrect route selected in test case 2');

        //Test 3
        $this->setCurrentRequest(Request::METHOD_GET, '/home/');

        $data = Map::getCurrentRoute();
        $this->assertEquals('route2', $data['alias'], 'incorrect route selected in test case 3');
    }

    /**
     * Clear the routes collection.
     */
    private function clearMap()
    {
        //Clear all routes before testing
        $property = new \ReflectionProperty(Map::class, 'routes');
        $property->setAccessible(true);
        $property->setValue([]);
    }

    /**
     * @param string $method
     * @param string $routeString
     *
     * @return void
     */
    private function setCurrentRequest(string $method, string $routeString)
    {
        $request = new Request();
        $request->setClientIp('127.0.0.1');
        $request->setMethod($method);
        $request->setQueryParams([]);
        $request->setRequestBody('');
        $request->setRouteStr($routeString);
        $request->setAjax(false);
        $request->setSecure(false);

        $property = new \ReflectionProperty(Request::class, 'currentRequest');
        $property->setAccessible(true);
        $property->setValue($request);
    }
}
