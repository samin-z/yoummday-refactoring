<?php

declare(strict_types=1);

namespace App\Handler;

use App\Repository\TokenRepositoryInterface;
use App\Request\QueryParameterExtractor;
use App\Service\PermissionChecker;
use App\Validator\TokenParameterValidator;
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
    private const HTTP_OK = 200;
    private const HTTP_BAD_REQUEST = 400;
    private const HTTP_NOT_FOUND = 404;

    public function __construct(
        private readonly TokenParameterValidator $validator,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly QueryParameterExtractor $queryParameterExtractor,
        private readonly PermissionChecker $permissionChecker
    ) {
    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $tokenId = $parameters->get('token');

        $validationResult = $this->validator->validate($tokenId);
        if (!$validationResult->isValid()) {
            return new JSONResponse(
                ['error' => $validationResult->getErrorMessage()],
                self::HTTP_BAD_REQUEST
            );
        }

        $queryParams = $serverRequest->getQueryParams();
        $permission = $this->queryParameterExtractor->extractPermission($queryParams);

        $token = $this->tokenRepository->findByTokenId($tokenId);
        if ($token === null) {
            return new JSONResponse(
                ['permission' => false, 'error' => 'Token not found'],
                self::HTTP_NOT_FOUND
            );
        }

        $hasPermission = $this->permissionChecker->hasPermission($token, $permission);

        return new JSONResponse(
            ['permission' => $hasPermission],
            self::HTTP_OK
        );
    }
}
