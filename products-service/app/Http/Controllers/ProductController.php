<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use src\Delivery\Http\Product\Handlers\CreateProductHandler;
use src\Delivery\Http\Product\Handlers\GetProductHandler;
use src\Delivery\Http\Product\Requests\CreateProductRequest;
use src\Delivery\Http\Product\Requests\GetProductRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly CreateProductHandler $createProductHandler,
        private readonly GetProductHandler $getProductHandler,
    ){
    }


    public function create(CreateProductRequest $request): JsonResponse
    {
        $response = $this->createProductHandler->handle(
            $request->name,
            $request->price,
        );

        return response()->json($response,Response::HTTP_CREATED);
    }


    public function show(GetProductRequest $request): JsonResponse
    {
        $response = $this->getProductHandler->handle($request->id);
        return response()->json($response,Response::HTTP_OK);
    }
}
