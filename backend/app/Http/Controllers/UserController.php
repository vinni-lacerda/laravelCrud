<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $users = User::orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return $this->successResponse(
            UserResource::collection($users),
            'Usuários carregados com sucesso.',
            200
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return $this->successResponse(
            UserResource::make($user),
            'Usuário criado com sucesso.',
            201
        );
    }

    public function show(User $user): JsonResponse
    {
        return $this->successResponse(
            UserResource::make($user),
            'Usuário encontrado com sucesso.',
            200
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return $this->successResponse(
            UserResource::make($user),
            'Usuário atualizado com sucesso.',
            200
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->successResponse(
            null,
            'Usuário removido com sucesso.',
            204
        );
    }
}
