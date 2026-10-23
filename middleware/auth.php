<?php
const LOGIN_URL = "/pages/auth/login.php";
const Book_Index_URL = "/pages/auth/register.php";
const FORBiDDEN_URL = "/pages/errors/403.php";


function sessionStart() 
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}


function isloggedIn()
{
    sessionStart();
    $isloggedIn = isset($_SESSION['user_id']) 

    return $isloggedIn;

}


function getAuthUser()
{
    sessionStart();
    $user = $_SESSION['user'] ?? null;

    return $user;
}

function hasRole( array $role)
{
    
    $user = getAuthUser();
    
   $isUserHasAuthorizeRole = $user !== null && in_array($user['role'], $roles);

   return $isUserHasAuthorizeRole;

}

function requireAuth()
{
    if (!isloggedIn()) {
    $_SESSION['errors'] = [
        'login' => 'Silakan login terlebih dahulu.'
    ];

    header("Location: " . LOGIN_URL);
    exit;
    }
}  

function requireRole(array $roles)
{
    requireAuth();

    if (!hasRole($roles)) {
        header("Location: " . FORBiDDEN_URL);
        exit;
    }
}
?>