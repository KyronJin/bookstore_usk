<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Tampilkan form kontak
    public function index()
    {
        return view('contact');
    }

    // Simpan pesan kontak ke database
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'regex:/^[\d\+\-\s]+$/', 'max:20'],
            'subject'  => ['required', 'string', 'max:255'],
            'message'  => ['required', 'string'],
        ]);

        Contact::create($request->all());

        return back()->with('success', 'Pesan Anda berhasil dikirim! Tim kami akan segera merespons ke email Anda.');
    }
}
