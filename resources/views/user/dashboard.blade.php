<h2>Bienvenue sur le tableau de bord Utilisateur</h2>
<p>Bonjour {{ auth()->user()->name }} !</p>
<a href="{{ route('logout') }}">Déconnexion</a>
