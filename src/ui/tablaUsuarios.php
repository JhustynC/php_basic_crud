<?php
require_once __DIR__ . '/../data/userRepository.php';

$userRepo = new UserRepository();
$users = $userRepo->getAllUsers();
$roles = $userRepo->getAllRoles();

include 'userTable.php';
renderUserTable($users, $roles);
?>
