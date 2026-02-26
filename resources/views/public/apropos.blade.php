@extends('public.layouts.app')

@section('title', 'À Propos - SGTC')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1>À Propos de <span class="highlight">SGTC</span></h1>
            <p>Découvrez notre mission pour la modernisation des communes.</p>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2>Notre Vision</h2>
                    <p>Le Système de Gestion des Taxes Communales (SGTC) est une solution innovante conçue pour simplifier
                        les interactions entre les municipalités et les contribuables. Notre objectif est de rendre la
                        gestion des taxes plus transparente, efficace et accessible à tous.</p>
                    <p>Grâce à notre plateforme, les communes peuvent optimiser leur recouvrement tandis que les citoyens
                        bénéficient d'un service fluide pour s'acquitter de leurs obligations civiques.</p>
                    <div class="stats">
                        <div class="stat-item">
                            <span class="number">10+</span>
                            <span class="label">Communes</span>
                        </div>
                        <div class="stat-item">
                            <span class="number">50k+</span>
                            <span class="label">Contribuables</span>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="{{ asset('assets/images/hero_slider_bg.png') }}" alt="Innovation">
                </div>
            </div>
        </div>
    </section>
@endsection
