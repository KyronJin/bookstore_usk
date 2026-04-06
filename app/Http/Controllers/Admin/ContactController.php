<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    // Tampilkan daftar pesan masuk
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contacts', compact('contacts'));
    }

    // Hapus pesan
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Pesan dari ' . $contact->name . ' berhasil dihapus.');
    }
}
