<?php

namespace App\Http\Controllers;

use App\Events\ContactFormCreated;
use App\Http\Requests\ContactRequest;
use App\TuChance\Models\ContactForm;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * @param ContactRequest $request
     * @return JsonResponse
     */
    public function contact(ContactRequest $request)
    {
        $contact = new ContactForm($request->validated());
        $contact->save();

        event(new ContactFormCreated($contact));

        return new JsonResponse(['sent' => true]);
    }
}
