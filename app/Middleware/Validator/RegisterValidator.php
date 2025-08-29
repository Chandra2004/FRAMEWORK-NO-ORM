<?php

namespace TheFramework\Middleware\Validator;

use TheFramework\Middleware\Middleware;
use Respect\Validation\Validator as v;
use TheFramework\App\Config;
use TheFramework\Helpers\Helper;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;

class RegisterValidator implements Middleware
{
    public function before()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        try {
            if ($path === '/register/auth' || $path === '/dashboard/superadmin/user-management/create/user') {
                $npm               = $_POST['npm'] ?? '';
                $fullName          = $_POST['full-name'] ?? '';
                $phone             = $_POST['phone'] ?? '';
                $email             = $_POST['email'] ?? '';
                $password          = $_POST['password'] ?? '';
                $confirmPassword   = $_POST['password-confirm'] ?? '';

                v::stringType()
                    ->regex('/^\d{2}\.\d{4}\.\d\.\d{5}$/')
                    ->setName('NPM')
                    ->assert($npm);

                v::stringType()
                    ->notEmpty()
                    ->length(3, 100)
                    ->setName('Nama Lengkap')
                    ->assert($fullName);

                v::phone()
                    ->setName('Nomor Telepon')
                    ->assert($phone);

                v::email()
                    ->setName('Email')
                    ->assert($email);

                v::stringType()
                    ->length(8, null)
                    ->setName('Password')
                    ->assert($password);

                v::equals($password)
                    ->setName('Konfirmasi Password')
                    ->assert($confirmPassword);
            }

        } catch (ValidationException $e) {
            if ($e instanceof NestedValidationException) {
                $messages = $e->getMessages([
                    'NPM' => 'Format NPM harus seperti 00.0000.0.00000',
                    'Nama Lengkap' => 'Nama lengkap wajib diisi dan minimal 3 karakter',
                    'Nomor Telepon' => 'Nomor telepon tidak valid',
                    'Email' => 'Email tidak valid',
                    'Password' => 'Password minimal 8 karakter',
                    'Konfirmasi Password' => 'Konfirmasi password harus sama dengan password',
                ]);

                $firstMessage = array_shift($messages);

                $status = isset($_SESSION['user']['role_name']);

                if($status && $_SESSION['user']['role_name'] === 'SuperAdmin') {
                    return Helper::redirect('/dashboard/superadmin/user-management', 'error', 'Validasi gagal: ' . $firstMessage);
                } else {
                }
                return Helper::redirect('/register', 'error', 'Validasi gagal: ' . $firstMessage);
            }

            Helper::redirect('/register', 'error', 'Validasi gagal: ' . $e->getMessage());
            exit;
        }
    }
}
