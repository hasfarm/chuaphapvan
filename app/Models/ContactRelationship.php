<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'related_contact_id',
        'relationship_type',
        'notes',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function relatedContact()
    {
        return $this->belongsTo(Contact::class, 'related_contact_id');
    }
}
