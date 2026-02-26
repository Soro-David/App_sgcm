@extends('public.layouts.app')

@section('title', 'Contact - SGTC')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1>Nous <span class="highlight">Contacter</span></h1>
            <p>Une question ? Notre équipe est là pour vous répondre.</p>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-form-wrapper">
                    <form class="contact-form">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" placeholder="Votre nom">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" placeholder="votre@email.com">
                        </div>
                        <div class="form-group">
                            <label>Sujet</label>
                            <input type="text" placeholder="Comment pouvons-nous vous aider ?">
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea rows="5" placeholder="Votre message..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer le message</button>
                    </form>
                </div>
                <div class="contact-info">
                    <div class="info-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Adresse</h4>
                            <p>Abidjan, Côte d'Ivoire</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Téléphone</h4>
                            <p>+225 00 00 00 00 00</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>contact@sgtc.ci</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
