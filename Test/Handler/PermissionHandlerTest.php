<?php

declare(strict_types=1);

namespace Test\Handler;

use App\Handler\PermissionHandler;
use App\Provider\TokenDataProvider;
use App\Repository\TokenRepository;
use App\Request\QueryParameterExtractor;
use App\Service\PermissionChecker;
use App\Validator\TokenParameterValidator;
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
        $tokenDataProvider = new TokenDataProvider();
        $tokenRepository = new TokenRepository($tokenDataProvider);
        $queryParameterExtractor = new QueryParameterExtractor();
        $permissionChecker = new PermissionChecker();
        $validator = new TokenParameterValidator();
        
        $this->handler = new PermissionHandler(
            $validator,
            $tokenRepository,
            $queryParameterExtractor,
            $permissionChecker
        );
        $this->serverRequest = $this->createMock(ServerRequestInterface::class);
    }

    public function testTokenWithReadPermissionReturnsTrue(): void
    {
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')
            ->with('token')
            ->willReturn('token1234');

        $this->serverRequest->method('getQueryParams')
            ->willReturn([]);

        $response = $this->handler->__invoke($this->serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        $this->assertSame(['permission' => true], $content);
        $this->assertSame(200, $response->getCode());
    }

    public function testTokenReadonlyWithReadPermissionReturnsTrue(): void
    {
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')
            ->with('token')
            ->willReturn('tokenReadonly');

        $this->serverRequest->method('getQueryParams')
            ->willReturn([]);

        $response = $this->handler->__invoke($this->serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        $this->assertSame(['permission' => true], $content);
        $this->assertSame(200, $response->getCode());
    }

    public function testTokenWithWritePermissionReturnsTrue(): void
    {
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')
            ->with('token')
            ->willReturn('token1234');

        $this->serverRequest->method('getQueryParams')
            ->willReturn(['permission' => 'write']);

        $response = $this->handler->__invoke($this->serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        $this->assertSame(['permission' => true], $content);
        $this->assertSame(200, $response->getCode());
    }

    public function testTokenReadonlyWithWritePermissionReturnsFalse(): void
    {
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')
            ->with('token')
            ->willReturn('tokenReadonly');

        $this->serverRequest->method('getQueryParams')
            ->willReturn(['permission' => 'write']);

        $response = $this->handler->__invoke($this->serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        $this->assertSame(['permission' => false], $content);
        $this->assertSame(200, $response->getCode());
    }

    public function testInvalidTokenReturnsFalse(): void
    {
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')
            ->with('token')
            ->willReturn('invalidToken');

        $this->serverRequest->method('getQueryParams')
            ->willReturn([]);

        $response = $this->handler->__invoke($this->serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        $this->assertSame(['permission' => false, 'error' => 'Token not found'], $content);
        $this->assertSame(404, $response->getCode());
    }

    public function testMissingTokenParameterReturnsFalse(): void
    {
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')
            ->with('token')
            ->willReturn('');

        $this->serverRequest->method('getQueryParams')
            ->willReturn([]);

        $response = $this->handler->__invoke($this->serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        $this->assertSame(['error' => 'Token parameter is required'], $content);
        $this->assertSame(400, $response->getCode());
    }
}
