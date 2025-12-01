<?php

declare(strict_types=1);

namespace App\Handler;

use App\Provider\TokenDataProvider;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Response\ResponseInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Attribute\Route;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Handler\HandlerInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\HttpMethod;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

#[Route(httpMethod: HttpMethod::GET, uri: '/has_permission/{token}')]
final class PermissionHandler implements HandlerInterface
{
    private const DEFAULT_PERMISSION = 'read';
    private const HTTP_OK = 200;
    private const HTTP_BAD_REQUEST = 400;
    private const HTTP_NOT_FOUND = 404;

    public function __construct(
        private readonly TokenDataProvider $tokenDataProvider
    ) {
    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $tokenId = $parameters->get('token');

        if (empty($tokenId)) {
            return new JSONResponse(
                ['error' => 'Token parameter is required'],
                self::HTTP_BAD_REQUEST
            );
        }

        $permission = $this->getPermissionFromQuery($serverRequest);

        $token = $this->findTokenById($tokenId);

        if ($token === null) {
            return new JSONResponse(
                ['permission' => false, 'error' => 'Token not found'],
                self::HTTP_NOT_FOUND
            );
        }

        $hasPermission = $this->checkPermission($token, $permission);

        return new JSONResponse(
            ['permission' => $hasPermission],
            self::HTTP_OK
        );
    }

    private function getPermissionFromQuery(ServerRequestInterface $serverRequest): string
    {
        $queryParams = $serverRequest->getQueryParams();
        return $queryParams['permission'] ?? self::DEFAULT_PERMISSION;
    }

    private function findTokenById(string $tokenId): ?array
    {
        $tokens = $this->tokenDataProvider->getTokens();

        foreach ($tokens as $token) {
            if ($token['token'] === $tokenId) {
                return $token;
            }
        }

        return null;
    }

    private function checkPermission(array $token, string $permission): bool
    {
        return in_array($permission, $token['permissions'] ?? [], true);
    }
}
