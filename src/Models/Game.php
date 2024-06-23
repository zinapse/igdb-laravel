<?php

namespace Zinapse\IgdbLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'igdb_games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'igdb_id', 'name', 'developer_id',
    ];
    
}