<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Users
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@primamedika.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Klinik',
            'email' => 'kasir@primamedika.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        // Create Payment Methods
        PaymentMethod::create(['name' => 'Cash', 'description' => 'Pembayaran tunai']);
        PaymentMethod::create(['name' => 'Transfer', 'description' => 'Transfer bank']);
        PaymentMethod::create(['name' => 'QRIS', 'description' => 'Pembayaran QRIS']);
        PaymentMethod::create(['name' => 'DANA', 'description' => 'E-wallet DANA']);

        // Create Sample Patients
        Patient::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
            'birth_date' => '1990-05-15',
        ]);

        Patient::create([
            'name' => 'Siti Rahayu',
            'phone' => '082345678901',
            'address' => 'Jl. Sudirman No. 45, Bandung',
            'birth_date' => '1985-08-20',
        ]);

        Patient::create([
            'name' => 'Ahmad Fauzi',
            'phone' => '083456789012',
            'address' => 'Jl. Gatot Subroto No. 67, Surabaya',
            'birth_date' => '1978-12-10',
        ]);
    }
}