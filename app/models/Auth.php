<?php

class Auth
{
    public static function id(): ?int
    {
        if (isset($_SESSION['userID'])) {
            return (int)$_SESSION['userID'];
        }

        //remember me implementation
        //as stated in https://stackoverflow.com/questions/244882/what-is-the-best-way-to-implement-remember-me-for-a-website
        if (isset($_COOKIE['remember_token'])) {
            $cookieValue = urldecode($_COOKIE['remember_token']);
            $parts = explode(':', $cookieValue);
            if (count($parts) === 2) {
                [$selector, $validator] = $parts;

                $userModel = new User();
                $tokenData = $userModel->getRememberToken($selector);

                if ($tokenData) {
                    if (hash_equals($tokenData['hashed_validator'], hash('sha256', $validator))) {
                        $_SESSION['userID'] = (int)$tokenData['user_id'];
                        $userId = (int)$tokenData['user_id'];
                        $userData = $userModel->getUserById($userId);
                        $_SESSION['username'] = $userData['Username'];
                        $_SESSION['avatar'] = $userData['Avatar'];
                        $semesterModel = new Semesters();
                        $activeSemester = $semesterModel->getActiveSemester($userId);
                        $_SESSION['active_semester_name'] = $activeSemester ? $activeSemester['Name'] : 'Brak aktywnego semestru';

                        //rotate token with same selector
                        $newValidator = bin2hex(random_bytes(32));
                        $newHashedValidator = hash('sha256', $newValidator);
                        $expires = date('Y-m-d H:i:s', time() + (86400 * 30)); //30 days

                        $userModel->updateRememberToken($selector, $newHashedValidator, $expires);

                        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

                        setcookie(
                            'remember_token',
                            $selector . ':' . $newValidator,
                            [
                                'expires' => time() + (86400 * 30),
                                'path' => '/',
                                'secure' => $isSecure,
                                'httponly' => true,
                                'samesite' => 'Lax'
                            ]
                        );
                        return (int)$_SESSION['userID'];
                    } else {
                        // THEFT ASSUMED
                        $userModel->deleteAllUserTokens((int)$tokenData['user_id']);
                    }
                }
            }
            //if expired
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
            setcookie('remember_token', '', time() - 3600, '/', '', $isSecure, true);
        }
        return null;
    }
    public static function check(): bool
    {
        return self::id() !== null;
    }

    public static function createRememberCookie(int $userId): void
    {
        $selector = bin2hex(random_bytes(6));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        $expires = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days

        (new User())->saveRememberToken($userId, $selector, $hashedValidator, $expires);

        // Cookie setup
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
        setcookie(
            'remember_token',
            $selector . ':' . $validator,
            [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'domain' => '',
                'secure' => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }
}