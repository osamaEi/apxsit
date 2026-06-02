<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFiles extends Model
{
    use HasFactory;


    protected $table ="application_files";

    protected $fillable = [
        'application_id',
        'file_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'description',
        'uploaded_by',
        'is_verified',
        'verified_by',
        'verified_at',
        'notes'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the application that this file belongs to.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the user who uploaded this file.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the user who verified this file.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include document type files.
     */
    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'document');
    }

    /**
     * Scope a query to only include certificate type files.
     */
    public function scopeCertificates($query)
    {
        return $query->where('file_type', 'certificate');
    }

    /**
     * Mark the file as verified.
     */
    public function verify($userId, $notes = null)
    {
        $this->is_verified = true;
        $this->verified_by = $userId;
        $this->verified_at = now();
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        return $this->save();
    }
}