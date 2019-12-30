<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Msg extends Model
{
    public const UPDATED_AT = null;
    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $fillable = [
        'user_id', 'target_id', 'content', 'content_text',
    ];
    protected $casts = [
        'content' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
            $model->content_text = $model->getContentText();
        });
    }

    protected function getContentText()
    {
        return trim(array_reduce($this->content, function ($carry, $i) {
            return is_array($i)
                ? $carry
                : $carry.$i;
        }, ''));
    }
}
