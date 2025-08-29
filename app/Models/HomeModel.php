<?php

namespace TheFramework\Models;

use TheFramework\App\CacheManager;
use TheFramework\App\Database;
use TheFramework\App\Config;
use TheFramework\App\Logging;
use Exception;
use Faker\Factory;
use TheFramework\Helpers\Helper;

class HomeModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function Status() {
        return $this->db ? 'success' : 'failed';
    }

    public function GetAllUsers() {
        $this->db->query("
            SELECT * FROM users ORDER BY updated_at DESC
        ");
        return $this->db->resultSet();
    }

    public function InformationUser($uid) {
        $this->db->query("
            SELECT * FROM users WHERE uid = :uid
        ");
        $this->db->bind(':uid', $uid);
        return $this->db->single();
    }

    public function CreateUser($data) {
        $faker = Factory::create();

        $this->db->query("
            SELECT COUNT(*) as count FROM users WHERE name = :name
        ");
        $this->db->bind(':name', $data['name']);
        $result = $this->db->single();
        if ($result && $result['count'] > 0) {
            return 'name_exist';
        }
        
        $this->db->query("
            SELECT COUNT(*) as count FROM users WHERE email = :email
        ");
        $this->db->bind(':email', $data['email']);
        $result = $this->db->single();
        if ($result && $result['count'] > 0) {
            return 'email_exist';
        }

        $this->db->query("
            INSERT INTO users (
                uid, name, email, password, profile_picture
            ) VALUES (
                :uid, :name, :email, :password, :profile_picture
            )
        ");
        $this->db->bind(':uid', Helper::uuid(20));
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', Helper::hash_password($faker->name()));
        $this->db->bind(':profile_picture', $data['profile_picture']);
        $this->db->execute();
    }

    public function UpdateUser($data, $uid) {
        // Cek apakah user dengan UID tersebut ada
        $this->db->query("SELECT uid FROM users WHERE uid = :uid");
        $this->db->bind(':uid', $uid);
        $user = $this->db->single();

        if (!$user) {
            return 'not_found';
        }

        // Cek duplikasi nama (kecuali milik user yang sedang diupdate)
        $this->db->query("
            SELECT COUNT(*) as count FROM users 
            WHERE name = :name AND uid != :uid
        ");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':uid', $uid);
        $result = $this->db->single();
        if ($result && $result['count'] > 0) {
            return 'name_exist';
        }

        // Cek duplikasi email (kecuali milik user yang sedang diupdate)
        $this->db->query("
            SELECT COUNT(*) as count FROM users 
            WHERE email = :email AND uid != :uid
        ");
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':uid', $uid);
        $result = $this->db->single();
        if ($result && $result['count'] > 0) {
            return 'email_exist';
        }

        // SQL UPDATE manual dan eksplisit
        $this->db->query("
            UPDATE users SET
                name = :name,
                email = :email,
                profile_picture = :profile_picture,
                updated_at = NOW()
            WHERE uid = :uid
        ");

        // Binding parameter
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':profile_picture', $data['profile_picture']);
        $this->db->bind(':uid', $uid);

        return $this->db->execute();
    }




    public function DeleteUser($uid) {
        $this->db->query("
            DELETE FROM users WHERE uid = :uid
        ");
        $this->db->bind(':uid', $uid);
        $this->db->execute();
    }
}