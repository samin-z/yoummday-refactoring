<?php

declare(strict_types=1);

namespace Test\Handler;

use App\Handler\PermissionHandler;
use PHPUnit\Framework\TestCase;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

class PermissionHandlerTest extends TestCase
{
    private PermissionHandler $handler;
    private ServerRequestInterface $serverRequest;

    protected function setUp(): void
    {
        $this->handler = new PermissionHandler();
        $this->serverRequest = $this->createMock(ServerRequestInterface::class);
    }
    
    public function testTokenWithReadPermissionReturnsTrue(): void
    {
        // TODO: Implement test for token1234 (read permission)
    }

    public function testTokenReadonlyWithReadPermissionReturnsTrue(): void
    {
        // TODO: Implement test for tokenReadonly (read permission)
    }


    public function testInvalidTokenReturnsFalse(): void
    {
        // TODO: Implement test for invalid (non-exsistent)token
    }

   
    public function testMissingTokenParameterReturnsFalse(): void
    {
        // TODO: Implement test for missing token
    }
}
