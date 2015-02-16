<?php

namespace AnnotatedRouterTest;

require_once 'AbstractAnnotationTestCase.php';

use AnnotatedRouter\Annotation\Route;
use AnnotatedRouter\Parser\ControllerAnnotationParser;
use AnnotatedRouter\Service\RouteConfigBuilder;
use AnnotatedRouterTest\TestController\NoBaseController;
use Zend\Code\Reflection\ClassReflection;

class SimpleRouteAnnotationTest extends AbstractAnnotationTestCase
{
    public function testAllParamsSetAndAccessible()
    {
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('completeDefinitionAction');
        $annotations = $parser->getMethodAnnotations($method);
        $this->assertCount(1, $annotations);

        /* @var $route Route */
        $route = $annotations[0];
        $this->assertEquals('complete-definition', $route->getName());
        $this->assertEquals('/complete-definition/:id/:method', $route->getRoute());
        $this->assertEquals('segment', $route->getType());
        $this->assertEquals('nobase', $route->getDefaultController());
        $this->assertEquals('complete-definition-action', $route->getDefaultAction());
        $this->assertEquals(1000, $route->getPriority());
    }

    public function testCompleteRoute()
    {
        $configBuilder = new RouteConfigBuilder();
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('completeDefinitionAction');
        $annotations = $parser->getMethodAnnotations($method);

        /* @var $route Route */
        $route = $annotations[0];
        $configBuilder->addPart($route);

        $routeArray = [
            'complete-definition' => [
                'type'          => 'segment',
                'options'       => [
                    'route'       => '/complete-definition/:id/:method',
                    'defaults'    => [
                        'controller' => 'nobase',
                        'action'     => 'complete-definition-action',
                    ],
                    'constraints' => [
                        'id'     => '\\d+',
                        'method' => '\\w+',
                    ],
                ],
                'may_terminate' => true,
            ],
        ];

        $this->assertEquals($routeArray, $configBuilder->toArray());
    }

    public function testAutodetectRouteName()
    {
        $configBuilder = new RouteConfigBuilder();
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('noRouteNameAction');
        $annotations = $parser->getMethodAnnotations($method);

        /* @var $route Route */
        $route = $annotations[0];
        $parser->autodetectMissingFields($route, $method, 'controllerkey');
        $configBuilder->addPart($route);

        $routeArray = [
            'no-route-name' => [
                'type'          => 'literal',
                'options'       => [
                    'route'       => '/route',
                    'defaults'    => [
                        'controller' => 'nobase',
                        'action'     => 'no-route'
                    ],
                    'constraints' => null
                ],
                'may_terminate' => true
            ]
        ];

        $this->assertEquals($routeArray, $configBuilder->toArray());
    }

    public function testAutodetectRoute()
    {
        $configBuilder = new RouteConfigBuilder();
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('noRouteAction');
        $annotations = $parser->getMethodAnnotations($method);

        /* @var $route Route */
        $route = $annotations[0];
        $parser->autodetectMissingFields($route, $method, 'controllerkey');
        $configBuilder->addPart($route);

        $routeArray = [
            'no-route' => [
                'type'          => 'literal',
                'options'       => [
                    'route'       => '/no-route',
                    'defaults'    => [
                        'controller' => 'nobase',
                        'action'     => 'no-route'
                    ],
                    'constraints' => null
                ],
                'may_terminate' => true
            ]
        ];

        $this->assertEquals($routeArray, $configBuilder->toArray());
    }

    public function testAutodetectType()
    {
        $configBuilder = new RouteConfigBuilder();
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('noTypeAction');
        $annotations = $parser->getMethodAnnotations($method);

        /* @var $route Route */
        $route = $annotations[0];
        $parser->autodetectMissingFields($route, $method, 'controllerkey');
        $configBuilder->addPart($route);

        $routeArray = [
            'no-type' => [
                'type'          => 'literal',
                'options'       => [
                    'route'       => '/no-type',
                    'defaults'    => [
                        'controller' => 'nobase',
                        'action'     => 'no-route'
                    ],
                    'constraints' => null
                ],
                'may_terminate' => true
            ]
        ];

        $this->assertEquals($routeArray, $configBuilder->toArray());
    }

    public function testAutodetectController()
    {
        $configBuilder = new RouteConfigBuilder();
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('noControllerAction');
        $annotations = $parser->getMethodAnnotations($method);

        /* @var $route Route */
        $route = $annotations[0];
        $parser->autodetectMissingFields($route, $method, 'controllerkey');
        $configBuilder->addPart($route);

//        die($this->coolFormat($configBuilder->toArray()));

        $routeArray = [
            'no-controller' => [
                'type'          => 'literal',
                'options'       => [
                    'route'       => '/no-controller',
                    'defaults'    => [
                        'controller' => 'controllerkey',
                        'action'     => 'no-route'
                    ],
                    'constraints' => null
                ],
                'may_terminate' => true
            ]
        ];

        $this->assertEquals($routeArray, $configBuilder->toArray());
    }

    public function testAutodetectAction()
    {
        $configBuilder = new RouteConfigBuilder();
        /* @var $parser ControllerAnnotationParser */
        $parser = $this->serviceManager->get('parser');
        $classReflection = new ClassReflection(new NoBaseController);
        $method = $classReflection->getMethod('noActionAction');
        $annotations = $parser->getMethodAnnotations($method);

        /* @var $route Route */
        $route = $annotations[0];
        $parser->autodetectMissingFields($route, $method, 'controllerkey');
        $configBuilder->addPart($route);

        $routeArray = [
            'no-action' => [
                'type'          => 'literal',
                'options'       => [
                    'route'       => '/no-action',
                    'defaults'    => [
                        'controller' => 'controllerkey',
                        'action'     => 'no-action'
                    ],
                    'constraints' => null
                ],
                'may_terminate' => true
            ]
        ];

        $this->assertEquals($routeArray, $configBuilder->toArray());
    }

}
