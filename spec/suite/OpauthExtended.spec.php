<?php

use SlimOpauth\OpauthExtended;
use SlimOpauth\OpauthExtendedException;

describe(OpauthExtended::class, function () {
    given('config', function () {
        return [
            'security_salt' => 'some-salt',
            'callback_transport' => 'get',
            'Strategy' => [
                'SomeStrategy' => []
            ]
        ];
    });

    given('opauth', function () {
        $opauth = new OpauthExtended($this->config, false);
        allow($opauth)->toReceive('assertKeys')->andReturn(true);
        allow($opauth)->toReceive('validateBody')->andReturn(true);

        return $opauth;
    });

    beforeAll(function () {
        $_SERVER['HTTP_HOST'] = 'some-host';
        $_SERVER['REQUEST_URI'] = 'some-uri';
    });
    describe('->callbackExtended()', function () {
        describe('fetching the response', function () {
            beforeAll(function () {
                $this->get_msg = [
                    'msg' => 'from GET'
                ];

                $this->post_msg = [
                    'msg' => 'from POST'
                ];

                $this->get_string = base64_encode(serialize($this->get_msg));
                $this->post_string = base64_encode(serialize($this->post_msg));


            });

            context('when the `callback_transport` is set to `post`', function () {
                beforeAll(function () {
                    $_POST['opauth'] = $this->post_string;
                    $this->opauth->env['callback_transport'] = 'post';
                });
                it('gets the opauth string from the POST body', function () {
                    expect(OpauthExtended::class)
                        ->toReceive('unpackResponse')
                        ->with($this->post_string);

                    $this->opauth->callbackExtended();
                });
            });

            context('when the `callback_transport` is set to `get`', function () {
                beforeAll(function () {
                    $_GET['opauth'] = $this->get_string;
                    $this->opauth->env['callback_transport'] = 'get';
                });
                it('gets the opauth string from the GET body', function () {
                    expect(OpauthExtended::class)
                        ->toReceive('unpackResponse')
                        ->with($this->get_string);

                    $this->opauth->callbackExtended();
                });
            });

            context('when the `callback_transport` is set not set to `get` or `post`', function () {
                beforeAll(function () {
                    $this->opauth->env['callback_transport'] = 'session';
                });
                it('throws an exception', function () {
                    expect(OpauthExtended::class)
                        ->not
                        ->toReceive('unpackResponse')
                        ->with($this->get_string);

                    $closure = function () {
                        $this->opauth->callbackExtended();
                    };

                    $exception_msg = "Unsupported callback_transport: '{$this->opauth->env['callback_transport']}'";
                    expect($closure)
                        ->toThrow(new OpauthExtendedException($exception_msg));
                });
            });
        });
    });
});
