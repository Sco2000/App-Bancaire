<?php
namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Interfaces\RepositoryInterfaces\IUserRepository;
use App\Http\Interfaces\RepositoryInterfaces\IFindByIdRepository;

class AuthService
{
    protected $userRepository;
    protected $userIdRepository;

    public function __construct(IUserRepository $userRepository, IFindByIdRepository $userIdRepository)
    {
        $this->userRepository=$userRepository;
        $this->userIdRepository=$userIdRepository;
    }
    public function login(array $data): array
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if(!Hash::check($data['password'], $user->password)){
            throw new AuthenticationException();
        }

        $roles = $user->roles->pluck('role_name')->toArray();

        if(count($roles) > 1){
            $loginToken = Str::uuid()->toString();
            Cache::put(
                'login:' . $loginToken,
                ['user_id'=>$user->id],
                now()->addMinutes(5)
            );
            return [
                'status' => 'ROLE_SELECTION_REQUIRED',
                'roles' => $roles,
                'login_token' => $loginToken
            ];
        }

        return $this->generateAccessToken($user, $roles[0]);
    }

    public function chooseRole(array $data): array
    {
        $cacheData = Cache::get('login:' . $data['login_token']);
        if(!$cacheData) throw new AuthenticationException();

        $user = $this->userIdRepository->findById($cacheData['user_id']);

        $roles = $user->roles->pluck('role_name')->toArray();

        if (!in_array($data['role'], $roles)) {
            throw new AuthorizationException();
        }

        Cache::forget('login' . $data['login_token']);

        return $this->generateAccessToken($user, $data['role']);
    }

    private function generateAccessToken (User $user, String $role): array
    {
        $scope = strtolower($role);
        $token = $user->createToken('mfs-token', [$scope])->accessToken;

        return [
            'access_token' => $token,
            'active_role' => $role
        ];
    }

    public function logOut(User $user){
        $token = $user->token();

        if($token){
            $token->revoke();
        }
    }
}