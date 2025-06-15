<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Register a new user, log them in, and return a token.
     *
     * @param  string  $email
     * @param  string  $name
     * @param  string  $password
     * @param  bool    $isSupplier
     * @return array
     */
    public function registerAndLogin(string $email, string $name, string $password,bool $isSupplier = false): array
    {
        $user = $this->userRepository->create([
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'role' => $isSupplier ? ROLE_SUPPLIER : ROLE_USER,
        ]);

        $token = $user->createToken(config('app.name'))->plainTextToken;

        return [
            'token' => $token,
        ];
    }
    /**
     * Logs in a user and return a token.
     *
     * @param  string  $email
     * @param  string  $password
     * @return array
     */
    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);
        // Check if the user exists and if the password is correct
        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        $this->resetLoginAttempts($email);

        $token = $user->createToken(config('app.name'))->plainTextToken;

        return [
            'token' => $token,
        ];
    }

    public function logOut(User $user){
        $user->tokens->each(function ($token) {
            $token->delete();
        });
       return true;
    }

    private function resetLoginAttempts($email){

        $attemptsCacheKey = 'login_attempts_' . $email;
        $lockoutCacheKey = 'login_lockout_' . $email;

        cache()->forget($lockoutCacheKey);
        cache()->forget($attemptsCacheKey);
    }
}
