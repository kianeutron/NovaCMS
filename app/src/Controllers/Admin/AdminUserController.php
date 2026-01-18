<?php

namespace NovaCMS\Controllers\Admin;

use NovaCMS\Core\BaseController;
use NovaCMS\Core\CSRF;
use NovaCMS\Core\Flash;
use NovaCMS\Services\UserService;

class AdminUserController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->checkAuth();
        $this->checkAdminRole();
    }

    public function index(): void
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $users = $this->userService->getAllUsers($page, 50);
            $totalUsers = $this->userService->countUsers();
            
            $this->render('admin/users/index', [
                'users' => $users,
                'currentPage' => $page,
                'totalUsers' => $totalUsers
            ]);
        } catch (\Exception $e) {
            Flash::error('An error occurred while loading users.');
            $this->render('admin/users/index', ['users' => [], 'currentPage' => 1, 'totalUsers' => 0]);
        }
    }

    public function edit(int $id): void
    {
        try {
            $user = $this->userService->getUserById($id);
            
            if (!$user) {
                Flash::error('User not found.');
                $this->redirect('/admin/users');
                return;
            }
            
            $this->render('admin/users/edit', [
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Flash::error('An error occurred while loading user.');
            $this->redirect('/admin/users');
        }
    }

    public function updateRole(int $id): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token.');
            $this->redirect('/admin/users');
            return;
        }
        
        $role = isset($_POST['role']) ? $_POST['role'] : '';
        
        try {
            $this->userService->updateRole($id, $role);
            Flash::success('User role updated successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }
        
        $this->redirect('/admin/users/' . $id . '/edit');
    }

    public function updateStatus(int $id): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token.');
            $this->redirect('/admin/users');
            return;
        }
        
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        
        try {
            $this->userService->updateStatus($id, $status);
            Flash::success('User status updated successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }
        
        $this->redirect('/admin/users/' . $id . '/edit');
    }

    public function resetPassword(int $id): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token.');
            $this->redirect('/admin/users');
            return;
        }
        
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        
        if ($password !== $confirmPassword) {
            Flash::error('Passwords do not match.');
            $this->redirect('/admin/users/' . $id . '/edit');
            return;
        }
        
        try {
            $this->userService->resetPassword($id, $password);
            Flash::success('Password reset successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }
        
        $this->redirect('/admin/users/' . $id . '/edit');
    }

    public function delete(int $id): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token.');
            $this->redirect('/admin/users');
            return;
        }
        
        // Prevent self-deletion
        if ($id == $_SESSION['user_id']) {
            Flash::error('You cannot delete your own account.');
            $this->redirect('/admin/users');
            return;
        }
        
        try {
            $this->userService->deleteUser($id);
            Flash::success('User deleted successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }
        
        $this->redirect('/admin/users');
    }

    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    private function checkAdminRole(): void
    {
        $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        
        if ($userRole !== 'admin') {
            http_response_code(403);
            echo '403 Forbidden - Admin access required.';
            exit;
        }
    }
}

