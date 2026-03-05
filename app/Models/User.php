<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',

        // Jika kamu pakai flag admin (opsional):
        // 'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            // Jika kamu pakai flag admin (opsional):
            // 'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        /**
         * PILIH SALAH SATU LOGIC DI BAWAH INI
         * (jangan semuanya). Ini yang paling umum:
         */

        // (A) Paling cepat untuk menghilangkan 403 (tidak disarankan untuk app publik):
        return true;

        // (B) Recommended: batasi admin saja (butuh kolom is_admin / roles):
        // return (bool) $this->is_admin;

        // (C) Batasi domain email + verifikasi email:
        // return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();

        // (D) Multi panel: contoh hanya panel 'admin' yang dibatasi:
        // if ($panel->getId() === 'admin') {
        //     return (bool) $this->is_admin;
        // }
        // return true;
    }
}
