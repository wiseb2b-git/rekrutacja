<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonPostDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\PostUserRegisterQueryParametersDto;
use Wise\User\ApiUi\Dto\Users\PostUserRegisterRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserRegisterServiceInterface;

class PostUserRegisterController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly PostUserRegisterServiceInterface $service,
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/register',
        methods: [Request::METHOD_POST]
    )]
    #[CommonPostDtoParamAttributes(
        description: 'Rejestracja użytkownika',
        tags: ['Users'],
        parametersDtoClass: PostUserRegisterQueryParametersDto::class
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/PostUserRegisterRequestDto",
            type: "object"
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie zapisano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/Common200FormResponseDto",
            type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpiły błędy",
        content: new OA\JsonContent(
            ref: "#/components/schemas/Common422FormResponseDto",
            type: "object"
        ),
    )]
    public function createPositionAction(Request $request): JsonResponse
    {
        return $this->service->process(
            $request->getContent(),
            PostUserRegisterRequestDto::class,
        );
    }
}
