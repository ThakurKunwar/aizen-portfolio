<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Honeypot: real people never see this field. Say thanks and store nothing.
        if (! empty($data['website'])) {
            return $this->thanks();
        }

        $message = ContactMessage::create([
            'name'    => Str::of($data['name'])->replaceMatches('/\s+/', ' ')->trim()->toString(),
            'email'   => $data['email'],
            'message' => $data['message'],
            'ip'      => $request->ip(),
        ]);

        $this->notify($message);

        return $this->thanks();
    }

    /** The message is already saved, so a mail problem must never lose it or show an error. */
    private function notify(ContactMessage $contact): void
    {
        try {
            Mail::raw(
                "From: {$contact->name} <{$contact->email}>\n\n{$contact->message}\n",
                fn (Message $mail) => $mail
                    ->to(config('portfolio.email'))
                    ->replyTo($contact->email, $contact->name)
                    ->subject('Portfolio message from '.$contact->name)
            );
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function thanks(): RedirectResponse
    {
        return redirect(route('home').'#contact')
            ->with('status', 'Thanks. Your message is in my inbox and I will reply soon.');
    }
}
