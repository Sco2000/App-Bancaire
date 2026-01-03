<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChooseRoleRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService=$authService;
    }
    public function login(LoginRequest $request){
        $data = $request->validated();
        $result = $this->authService->login($data);
        if (($result['status'] ?? null) === 'ROLE_SELECTION_REQUIRED') {
            return $this->successResponse($result, 'Veuillez choisir un profil.');
        }
        return $this->successResponse($result, 'Connexion réussi aec succès!');
    }

    public function chooseRole(ChooseRoleRequest $request){
        $data = $request->validated();
        $response = $this->authService->chooseRole($data);
        return $this->successResponse($response, 'Connexion réussi aec succès!');

    }

    public function logOut(Request $request){
        $this->authService->logOut($request->user());
        return $this->successResponse(null, 'Déconnexion réussie');
    }
}
