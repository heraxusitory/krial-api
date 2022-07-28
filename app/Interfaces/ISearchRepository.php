<?php


namespace App\Interfaces;


use Illuminate\Support\Collection;

interface ISearchRepository
{
    public function search(string $query = ''): Collection;
}
