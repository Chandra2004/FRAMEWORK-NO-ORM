<?php

namespace TheFramework\Http\Controllers;

use Exception;
use TheFramework\App\View;
use TheFramework\Config\ImageHandler;
use TheFramework\Helpers\Helper;
use TheFramework\Models\HomeModel;

class HomeController extends Controller
{
    private $HomeModel;

    public function __construct()
    {
        $this->HomeModel = new HomeModel();
    }

    public function Welcome()
    {
        $notification = Helper::get_flash('notification');

        return View::render('interface.welcome', [
            'title' => 'THE FRAMEWORK - Modern PHP Framework with Database Migrations & REST API',
            'notification' => $notification,

            'status' => $this->HomeModel->Status()
        ]);
    }

    public function Users()
    {
        $notification = Helper::get_flash('notification');

        return View::render('interface.users', [
            'title' => 'THE FRAMEWORK - User Management',
            'notification' => $notification,

            'userData' => $this->HomeModel->GetAllUsers()
        ]);
    }

    public function InformationUser($uid)
    {
        $notification = Helper::get_flash('notification');
        $user = $this->HomeModel->InformationUser($uid);

        if (empty($user)) {
            return Helper::redirectToNotFound();
        }

        return View::render('interface.detail', [
            'title' => 'THE FRAMEWORK - ' . $user['name'] . ' - User Detail',
            'notification' => $notification,

            'user' => $user
        ]);
    }

    public function CreateUser()
    {
        if (Helper::is_post() && Helper::is_csrf()) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $profilePicture = $_FILES['profile_picture'];

            if (empty($name) || empty($email)) {
                return Helper::redirect('/users', 'warning', 'Name and Email cannot be empty.', 5);
            }

            try {
                $photoFileName = null;
                if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                    $resultPhoto = ImageHandler::handleUploadToWebP(
                        $profilePicture,
                        '/user-pictures'
                    );

                    if (ImageHandler::isError($resultPhoto)) {
                        return Helper::redirect('/users', 'error', 'error: ' . ImageHandler::getErrorMessage($resultPhoto), 5);
                    }

                    $photoFileName = $resultPhoto;
                }

                $data = [
                    'uid' => Helper::uuid(20),
                    'name' => $name,
                    'email' => $email,
                    'profile_picture' => $photoFileName
                ];

                $resultUser = $this->HomeModel->CreateUser($data);

                $messages = [
                    'name_exist' => 'Name is taken',
                    'email_exist' => 'Email is taken',
                ];

                if (array_key_exists($resultUser, $messages)) {
                    if ($photoFileName) {
                        ImageHandler::delete('/user-pictures', $photoFileName);
                    }

                    return Helper::redirect('/users', 'error', 'error: ' . $messages[$resultUser], 5);
                }

                return Helper::redirect('/users', 'success', $data['name'] . ' successfully create', 5);
            } catch (Exception $e) {
                if (!empty($photoFileName)) {
                    ImageHandler::delete('/user-pictures', $photoFileName);
                }

                return Helper::redirect('/users', 'error', 'error: ' . $e->getMessage(), 5);
            }
        }
    }

    public function UpdateUser($uid)
    {
        if (!Helper::is_post() || !Helper::is_csrf()) {
            return Helper::redirect('/users', 'error', 'Invalid request method or CSRF token.', 5);
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $profilePicture = $_FILES['profile_picture'] ?? null;
        $deleteProfilePicture = isset($_POST['delete_profile_picture']);

        // Ambil data user lama
        $user = $this->HomeModel->InformationUser($uid);
        if (!$user) {
            return Helper::redirect('/users', 'error', 'User not found.', 5);
        }

        // Validasi input dasar
        if (empty($name) || empty($email)) {
            return Helper::redirect('/users', 'warning', 'Name and Email cannot be empty.', 5);
        }

        try {
            $data = [
                'name' => $name,
                'email' => $email,
            ];

            // Hapus foto jika diminta
            if ($deleteProfilePicture) {
                if (!empty($user['profile_picture'])) {
                    ImageHandler::delete('/user-pictures', $user['profile_picture']);
                }
                $data['profile_picture'] = null;
            }
            // Upload baru jika ada
            elseif ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $uploaded = ImageHandler::handleUploadToWebP($profilePicture, '/user-pictures');
                if (ImageHandler::isError($uploaded)) {
                    return Helper::redirect('/users', 'error', 'error: ' . ImageHandler::getErrorMessage($uploaded), 5);
                }

                // Hapus foto lama jika ada
                if (!empty($user['profile_picture'])) {
                    ImageHandler::delete('/user-pictures', $user['profile_picture']);
                }

                $data['profile_picture'] = $uploaded;
            }
            // Tidak ada perubahan → pakai foto lama
            else {
                $data['profile_picture'] = $user['profile_picture'];
            }

            // Kirim ke model
            $updateUser = $this->HomeModel->UpdateUser($data, $uid);

            if ($updateUser === true) {
                return Helper::redirect("/users/information/{$uid}", 'success', 'User successfully updated.', 5);
            }
            if ($updateUser === 'name_exist') {
                return Helper::redirect('/users', 'warning', 'Name already exists.', 5);
            }
            if ($updateUser === 'email_exist') {
                return Helper::redirect('/users', 'warning', 'Email already exists.', 5);
            }
            if ($updateUser === 'not_found') {
                return Helper::redirect('/users', 'error', 'User not found.', 5);
            }

            return Helper::redirect('/users', 'error', 'Failed to update user.', 5);
        } catch (Exception $e) {
            return Helper::redirect('/users', 'error', 'error: ' . $e->getMessage(), 5);
        }
    }


    public function DeleteUser($uid)
    {
        if (Helper::is_post() && Helper::is_csrf()) {
            $user = $this->HomeModel->InformationUser($uid);
            ImageHandler::delete('/user-pictures', $user['profile_picture']);


            $this->HomeModel->DeleteUser($uid);
            return Helper::redirect('/users', 'success', 'user berhasil terhapus', 5);
        }
    }
}
