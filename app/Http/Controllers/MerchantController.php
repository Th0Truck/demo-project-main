<?php

namespace App\Http\Controllers;

use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\InputBag;

class MerchantController extends BaseController
{

    public function __construct(
        private readonly MerchantService $merchantService,
    ){}

    /**
     * Adds a new merchant with the provided name.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function addMerchant(Request $request): JsonResponse
    {
        /** @var InputBag $req */
        $req = $request->json();

        $res = $this->merchantService->addMerchant(
            $req->getString('name'),
        );

        return response()->json($res->toResponse());
    }
}
