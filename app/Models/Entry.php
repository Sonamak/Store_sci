<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Entry extends Model {
    use HasFactory;
    Use SoftDeletes;
    
    protected $softDelete = true;

    protected $fillable = [
        "name",
        "attachment_path",
        "is_private",
        "download_count",
        "whatsapp_count",
    ];

    protected $appends = [
        'attachment_url',
        'file_type',
    ];

    // Autorun
    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->uploaded_by = Auth::id();
        });
    }

    // Relationships
    public function uploader() {
        return $this->belongsTo('App\Models\User', 'uploaded_by', 'id');
    }

    // Appends
    public function getAttachmentUrlAttribute() {
        if(empty($this->attachment_path) || !Storage::exists($this->attachment_path)) {
            return null;
        }

        return Storage::url($this->attachment_path);
    }
    public function getFileTypeAttribute() {
        if(empty($this->attachment_path) || !Storage::exists($this->attachment_path)) {
            return null;
        }

        $file = explode('.', $this->attachment_path);
        return $file[count($file) - 1];
    }
}
