<?php

namespace TheFramework\Middleware\Validator;

use TheFramework\Helpers\Helper;
use TheFramework\Middleware\Middleware;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;

class LoginValidator implements Middleware
{
    public function before()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        try {
            if ($path === '/login/auth') {
                $identyfier = $_POST['identyfier'] ?? '';
                $password = $_POST['password'] ?? '';

                v::oneOf(
                    v::email(),
                    v::regex('/^\d{2}\.\d{4}\.\d\.\d{5}$/')
                )->setName('NPM/Email')->assert($identyfier);

                // Validasi password
                v::stringType()
                    ->length(8, null)
                    ->setName('Password')
                    ->assert($password);
            }
        } catch (ValidationException $e) {
            if ($e instanceof NestedValidationException) {
                $messages = $e->getMessages([
                    'NPM/Email' => 'Masukkan NPM atau Email yang valid.',
                    'Password' => 'Password minimal 8 karakter.',
                ]);

                $firstMessage = array_shift($messages);
                Helper::redirect('/login', 'error', 'Validasi gagal: ' . $firstMessage);
                exit;
            }

            Helper::redirect('/login', 'error', 'Validasi gagal: ' . $e->getMessage());
            exit;
        }
    }
}
