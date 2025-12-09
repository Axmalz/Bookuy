<?php

namespace App\Models;

// Hapus atau comment use Notifiable jika tidak pakai fitur notifikasi bawaan Laravel via email/dll
// use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory; // Hapus Notifiable dari sini jika bikin konflik

    // ... (fillable, hidden, casts tetap sama) ...
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'gender',
        'semester',
        'description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELASI ---

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // FIX: Relasi Manual ke Notification (One to Many biasa)
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
