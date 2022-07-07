<?php

namespace App\Laravue\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $guarded = [];

    public function getUrl()
    {
        return config('app.url') . '/storage/' . $this->dir_path . '/' . $this->name;
    }

    public function get()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
