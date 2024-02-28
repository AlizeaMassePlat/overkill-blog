<?php

namespace App\Interface;

interface UserInterface {
    public function getId();
    public function setId(int $id);
    public function getEmail();
    public function setEmail(string $email);
    public function getPassword();
    public function setPassword(string $password);
    public function getFirstname();
    public function setFirstname(string $firstname);
    public function getLastname();
    public function setLastname(string $lastname);
    public function getRole();
    public function setRole(array $role);
    public function getPosts();
    public function setPosts(array $posts);
    public function getComments();
    public function setComments(array $comments);
    public function getWelcomeMessage(): string;
    public function canCreatePost(): bool;
    public function getProfileData(): array;
}   