<?php

namespace NovaCMS\Controllers;

use NovaCMS\Core\BaseController;
use NovaCMS\Core\CSRF;
use NovaCMS\Services\AuthService;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(): void
    {
        try {
            $this->render('auth/login');
        } catch (\Exception $e) {
            http_response_code(500);
            echo 'An error occurred while loading the login page';
        }
    }

    public function login(): void
    {
        try {
            if (!CSRF::validateRequest()) {
                $this->render('auth/login', ['error' => 'Invalid security token. Please try again.']);
                return;
            }
            
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            $user = $this->authService->login($email, $password);

            if ($user) {
                $this->redirect('/admin/dashboard');
            } else {
                $this->render('auth/login', ['error' => 'Invalid credentials']);
            }
        } catch (\Exception $e) {
            $this->render('auth/login', ['error' => 'An error occurred during login. Please try again.']);
        }
    }

    public function logout(): void
    {
        try {
            $this->authService->logout();
            $this->redirect('/');
        } catch (\Exception $e) {
            $this->redirect('/?error=logout_failed');
        }
    }

    public function showRegister(): void
    {
        try {
            $this->render('auth/register');
        } catch (\Exception $e) {
            http_response_code(500);
            echo 'An error occurred while loading the registration page';
        }
    }

    public function register(): void
    {
        if (!CSRF::validateRequest()) {
            $this->render('auth/register', ['error' => 'Invalid security token. Please try again.']);
            return;
        }
        
        $data = $this->getPostData();
        
        try {
            $this->authService->register($data);
            $this->redirect('/login');
        } catch (\Exception $e) {
            $this->render('auth/register', ['error' => $e->getMessage()]);
        }
    }

    private function getPostData(): array
    {
        return [
            'username' => isset($_POST['username']) ? $_POST['username'] : '',
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'first_name' => isset($_POST['first_name']) ? $_POST['first_name'] : null,
            'last_name' => isset($_POST['last_name']) ? $_POST['last_name'] : null,
        ];
    }
}
