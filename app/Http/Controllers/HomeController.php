<?php

namespace App\Http\Controllers;

use App\Mail\ContactConfirmation;
use App\Mail\ContactNotification;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Partenaire;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $articles    = Article::publie()->limit(3)->get();
        $events      = Event::avenir()->limit(3)->get();
        $projects    = Project::actif()->limit(3)->get();
        $partenaires = Partenaire::actif()->orderBy('ordre')->orderBy('nom')->get();

        return view('home', compact('articles', 'events', 'projects', 'partenaires'));
    }

    public function about()
    {
        $members = TeamMember::actif()->get();
        return view('about', compact('members'));
    }

    public function sns()
    {
        $snsEvents = Event::publie()->where(function ($q) {
            $q->where('titre', 'like', '%Ti Dèj%')
              ->orWhere('titre', 'like', '%Santé%')
              ->orWhere('titre', 'like', '%Sport%')
              ->orWhere('titre', 'like', '%SNS%')
              ->orWhere('titre', 'like', '%Madin%Santé%');
        })->get();
        $prochains = Event::avenir()->limit(4)->get();
        return view('sns', compact('snsEvents', 'prochains'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'required|string|max:150',
            'message' => 'required|string|min:10',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 100 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'sujet.required' => 'Le sujet est obligatoire.',
            'sujet.max' => 'Le sujet ne doit pas dépasser 150 caractères.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
        ]);

        $contact = Contact::create($validated);

        try {
            Mail::to($contact->email)->send(new ContactConfirmation($contact));
            Mail::to('contact@njiezm.fr')->send(new ContactNotification($contact));
        } catch (\Exception $e) {
            \Log::error('Mail contact failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    }
}
