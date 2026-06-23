<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreatePostRequest;
use App\Http\Requests\Api\PostsFilterRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function store(CreatePostRequest $request): JsonResponse
    {
        $post = $this->postService->create($request->toDto((int) Auth::id()));

        return response()->json(new PostResource($post->load('user')), 201);
    }

    public function index(PostsFilterRequest $request): JsonResponse
    {
        $dto    = $request->toDto();
        $result = $this->postService->getAll($dto);

        return response()->json([
            'data'   => PostResource::collection($result['posts']),
            'total'  => $result['total'],
            'limit'  => $result['limit'],
            'offset' => $result['offset'],
        ]);
    }

    public function my(PostsFilterRequest $request): JsonResponse
    {
        $dto    = $request->toDto();
        $result = $this->postService->getMy((int) Auth::id(), $dto);

        return response()->json([
            'data'   => PostResource::collection($result['posts']),
            'total'  => $result['total'],
            'limit'  => $result['limit'],
            'offset' => $result['offset'],
        ]);
    }
}
