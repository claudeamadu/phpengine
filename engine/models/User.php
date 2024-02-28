<?php

class User
{
    public static function authenticate()
    {
        $email = requestData('email');
        $password = requestData('password');
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $user = Database::fetch($sql);
        if ($user) {
            if (password_verify($password, $user->password)) {
                Session::set('user_id', $user->id);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function create($data)
    {
        $user = Database::insert("users", $data);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public static function get()
    {
        if (Session::isset('user_id')) {
            $user_id = Session::get('user_id');
            $sql = "SELECT * FROM users WHERE id = '$user_id'";
            $user = Database::fetch($sql);
            return $user;
        } else {
            return null;
        }
    }

    public static function update($userID, $data = [])
    {
        return Database::update('users', $data, ['id' => $userID]);
    }

    public static function delete($userID, $data = [])
    {
        return Database::delete('users',['id' => $userID]);
    }
}