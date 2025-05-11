<?php


class Authentication{
    private $pdo;
    private $hash = 'bookshopez';
    
    public function getUserByEmail($email){
        $stmt = $this->pdo->prepare("SELECT * FROM public.users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function validateForgotPassword($email)
    {
        $validationErrors = [];

        if (empty($email)) {
            $validationErrors['email'] = 'Введите ваш email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validationErrors['email'] = 'Некорректный формат email адреса';
        } elseif (!$this->emailExists($email)) {
            $validationErrors['email'] = 'Пользователь с таким email не найден';
        }

        return $validationErrors;
    }

    public function emailExists($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM public.users WHERE email = ?");
            $stmt->execute([$email]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function savePasswordResetToken($userId, $token)
    {
        $stmt = $this->pdo->prepare("UPDATE public.users SET reset_token = ? WHERE id = ?");
        return $stmt->execute([$token, $userId]);
    }

    public function __construct($pdo){
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function findUser($login) {
        $sql = 'SELECT id, login FROM public.users WHERE login = :login LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        if($row_count !== 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function comparePasswords($login, $oldPassword) {
        $sql = 'SELECT id, password FROM public.users WHERE login = :login LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        if($row_count !== 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $oldPassword = md5($oldPassword . $this->hash);
            if ($user['password'] !== $oldPassword) {
                return false;
            }
            return true;
        }
        return false;
    }
    public function validateResetPassword($password, $confirmPassword)
    {
        $validationErrors = [];

        if (empty($password)) {
            $validationErrors['password'] = 'Введите новый пароль';
        } elseif (preg_match('/[а-яА-Я]/u', $password)) {
            $validationErrors['password'] = 'Пароль не должен содержать русские символы';
        } elseif (strlen($password) < 6) {
            $validationErrors['password'] = 'Пароль должен содержать не менее 6 символов';
        } elseif (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*]{6,}$/', $password)) {
            $validationErrors['password'] = 'Пароль должен содержать хотя бы одну букву и одну цифру';
        }

        if (empty($confirmPassword)) {
            $validationErrors['confirmPassword'] = 'Подтвердите новый пароль';
        } elseif ($password !== $confirmPassword) {
            $validationErrors['confirmPassword'] = 'Пароли не совпадают';
        }

        error_log("Validation errors: " . print_r($validationErrors, true));

        return $validationErrors;
    }

    public function editUserMail($id, $reset_token, $password) {
        $password = md5($password . $this->hash);
        $sql = 'UPDATE public.users SET reset_token = :reset_token, email = :email,  password = :password WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':reset_token', $reset_token);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        return true;
        }



    function findUserById($id) {
        $pdo = Connection::get()->connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function editUs($id, $email, $name, $lastname, $number, $address, $password = null, $login) {
        $pdo = Connection::get()->connect();
        

        $sql = 'UPDATE public.users SET email = :email, name = :name, lastname = :lastname, 
                number = :number, address = :address';
        
        $params = [
            ':id' => $id,
            ':email' => $email,
            ':name' => $name,
            ':lastname' => $lastname,
            ':number' => $number,
            ':address' => $address
        ];
        

        if (!empty($password)) {
            $password = md5($password . $this->hash);
            $sql .= ', password = :password';
            $params[':password'] = $password;
        }
        
        $sql .= ' WHERE id = :id';
        
        $statement = $pdo->prepare($sql);
        return $statement->execute($params);
    }

    public function register($login, $email, $password, $name, $lastname, $number) {
        $findUser = $this->findUser($login);
        $password = md5($password . $this->hash);
        if ($findUser === false){
            $sql = 'INSERT INTO public.users (login, email, password, name, lastname, number) VALUES (:login, :email, :password, :name, :lastname, :number)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':lastname', $lastname);
            $stmt->bindValue(':number', $number);
            $stmt->execute();
            $_SESSION['user'] = $login;
            $last_id = $this->pdo->lastInsertId();
            return $last_id;
        }
        return false;
    }


    






    public function login($login, $password) {
        $findUser = $this->findUser($login);
        $password = md5($password . $this->hash);
        if ($findUser !== null){
            $sql = 'SELECT * FROM public.users WHERE login = :login AND password = :password LIMIT 1';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->bindValue(':password', $password);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $row_count = $stmt->rowCount();
            if($row_count == 1) {
                $_SESSION['user'] = $user['login'];
                $_SESSION['user_id'] = $user['id'];
                return true;
            }
        }
        return false;
    }

    public function logout() {
        unset($_SESSION['user']);
    }

    public function isAuthed() {
        if (array_key_exists('user', $_SESSION) && $_SESSION['user'] !== null) {
            return true;
        } else {
            return false;
        }
    }

    public function getCurrentUser() {
        if ($this->isAuthed()) {
            return $this->findUser($_SESSION['user']);
        }
        return false;
    }




}