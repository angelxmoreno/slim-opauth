<?php

use Kahlan\Plugin\Double;
use SlimOpauth\SlimOpauthMiddleware;

describe(SlimOpauthMiddleware::class, function () {


    given('uri', function () {
        $obj = Double::instance([
            'extends' => \Slim\Http\Uri::class,
            'magicMethods' => true
        ]);

        return $obj;
    });

    given('request', function () {
        $obj = Double::instance([
            'extends' => \Slim\Http\Request::class,
            'magicMethods' => true
        ]);

        allow($obj)->toReceive('getUri')->andReturn($this->uri);

        return $obj;
    });

    given('response', function () {
        $obj = Double::instance([
            'extends' => \Slim\Http\Response::class,
            'magicMethods' => true
        ]);

        return $obj;
    });

    given('next', function () {
        return function () {
        };
    });

    beforeEach(function () {
        allow(SlimOpauthMiddleware::class)
            ->toReceive('main')
            ->andReturn(true);
    });

    describe('->__construct()', function () {
        it('sets the config array', function () {
            $config = ['key' => 'val'];

            expect(SlimOpauthMiddleware::class)
                ->toReceive('setConfig')
                ->with($config);

            $mw = new SlimOpauthMiddleware($config);

            expect($mw->getConfig())
                ->toBe($config);
        });
    });

    describe('->__invoke()', function () {
        beforeEach(function () {
            $this->mw = new SlimOpauthMiddleware();
        });

        describe('checking if path should trigger', function () {

            it('checks if a path should trigger', function () {
                expect(SlimOpauthMiddleware::class)
                    ->toReceive('checkPathQualifies');

                $this->mw($this->request, $this->response, $this->next);
            });

            context('when the path is not a string', function () {
                beforeAll(function () {
                    allow(\Slim\Http\Uri::class)
                        ->toReceive('getPath')
                        ->andReturn([]);
                });
                it('does not trigger', function () {
                    expect(SlimOpauthMiddleware::class)
                        ->toReceive('checkPathQualifies');

                    expect(SlimOpauthMiddleware::class)
                        ->not
                        ->toReceive('main');

                    $this->mw($this->request, $this->response, $this->next);
                });
            });

            context('when the path does not match the auth route', function () {
                beforeAll(function () {
                    allow(\Slim\Http\Uri::class)
                        ->toReceive('getPath')
                        ->andReturn('some-string');
                });
                it('does not trigger', function () {
                    expect(SlimOpauthMiddleware::class)
                        ->toReceive('checkPathQualifies');

                    expect(SlimOpauthMiddleware::class)
                        ->toReceive('getAuthRouteRegex');

                    expect(SlimOpauthMiddleware::class)
                        ->not
                        ->toReceive('main');

                    $this->mw($this->request, $this->response, $this->next);
                });
            });

            context('when the path does match the auth route', function () {
                context('and the path has the provider', function () {
                    beforeAll(function () {
                        allow(\Slim\Http\Uri::class)
                            ->toReceive('getPath')
                            ->andReturn('/auth/provider');
                    });
                    it('does trigger', function () {
                        expect(SlimOpauthMiddleware::class)
                            ->toReceive('checkPathQualifies');

                        expect(SlimOpauthMiddleware::class)
                            ->toReceive('getAuthRouteRegex');

                        expect(SlimOpauthMiddleware::class)
                            ->toReceive('main');

                        $this->mw($this->request, $this->response, $this->next);
                    });
                });

                context('and the path has the provider with callback', function () {
                    beforeAll(function () {
                        allow(\Slim\Http\Uri::class)
                            ->toReceive('getPath')
                            ->andReturn('/auth/provider/callback');
                    });
                    it('does trigger', function () {
                        expect(SlimOpauthMiddleware::class)
                            ->toReceive('checkPathQualifies');

                        expect(SlimOpauthMiddleware::class)
                            ->toReceive('getAuthRouteRegex');

                        expect(SlimOpauthMiddleware::class)
                            ->toReceive('main');

                        $this->mw($this->request, $this->response, $this->next);
                    });
                });
            });


        });

    });
});