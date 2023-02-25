<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactsController
{
    public function index(Request $request)
    {
        $contacts = Contact::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->simplePaginate();

        return \view('contacts.index', [
            'contacts' => $contacts,
            'count' => Contact::count(),
            'time' => $request->query('time'),
        ]);
    }
}
