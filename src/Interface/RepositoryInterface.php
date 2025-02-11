<?php

namespace App\Interface;

interface RepositoryInterface
{
  public function save($model);
  public function insert($model);
  public function update($model);
  public function findOneById(int $id);
  public function findAll(): array;
  public function delete(int $id);
  
}
