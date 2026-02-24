<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function __construct(protected ServiceRepositoryInterface $serviceRepo) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'categories' => $this->serviceRepo->getAllActiveWithCategories(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $service = $this->serviceRepo->findBySlug($slug);

        if (! $service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json(['service' => $service]);
    }
}