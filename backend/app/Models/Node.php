<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = [
        'label', 
        'description', 
        'level'
    ];

    public function nodeParents()
    {
        return $this->belongsToMany(Node::class, 'node_edges', 'to_node_id', 'from_node_id');
    }

    public function nodeChildrens()
    {
        return $this->belongsToMany(Node::class, 'node_edges', 'from_node_id', 'to_node_id');
    }
}
