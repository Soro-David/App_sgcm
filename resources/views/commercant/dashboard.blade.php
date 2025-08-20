@extends('commercant.layouts.app')

@section('title', 'Tableau de Bord')

@section('content')

    {{-- <div class="card shadow-lg p-4" style="width: 900px; border-radius: 20px; border: 1px solid #dee2e6;">
        
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="mb-1 text-uppercase fw-bold">Carte de Contribuable</h4>
                <p class="mb-0 text-muted">Commune : <strong>{{ $commercant->mairie->nom ?? 'Non défini' }}</strong></p>
            </div>
            <div>
                <small class="text-muted">ID Commerce : {{ $commercant->num_commerce }}</small>
            </div>
        </div>

        <!-- Corps principal en format paysage -->
        <div class="row align-items-center">
            <!-- Colonne 1 : Photo de profil -->
            <div class="col-md-3 text-center">
                <img src="{{ $commercant->photo_profil ? Storage::url($commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                     alt="Photo de profil"
                     class="rounded-circle shadow-sm"
                     style="width: 160px; height: 160px; object-fit: cover; border: 4px solid #fff;">
                <h5 class="mt-3 fw-bold">{{ $commercant->nom }}</h5>
            </div>

            <!-- Colonne 2 : Infos -->
            <div class="col-md-6">
                <ul class="list-unstyled mb-0 fs-6">
                    <li class="mb-2"><strong>Téléphone :</strong> {{ $commercant->telephone ?? 'Non fourni' }}</li>
                    <li class="mb-2"><strong>Email :</strong> {{ $commercant->email ?? 'Non fourni' }}</li>
                    <li class="mb-2"><strong>Adresse :</strong> {{ $commercant->adresse ?? 'Non fournie' }}</li>
                    <li class="mb-2"><strong>Secteur :</strong> {{ $commercant->secteur->nom ?? 'Non défini' }}</li>
                    <li class="mb-2"><strong>Type de Contribuable :</strong> {{ $commercant->typeContribuable->nom ?? 'Non défini' }}</li>
                </ul>
            </div>

            <!-- Colonne 3 : QR Code -->
            <div class="col-md-3 text-center">
                @if($commercant->qr_code_path)
                    <img src="{{ Storage::url($commercant->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 180px;">
                    <p class="mt-2 text-muted small">Scannez pour plus d'infos</p>
                @else
                    <div class="alert alert-warning">QR Code indisponible.</div>
                @endif
            </div>
        </div>

        <!-- Pied de carte -->
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('agent.commerce.index') }}" class="btn btn-outline-secondary btn-sm me-2">Retour à la liste</a>
            <a href="#" onclick="window.print();" class="btn btn-outline-primary btn-sm">Imprimer</a>
        </div>
    </div> --}}

  <div class="row">
    <div class="col-xl-6 grid-margin stretch-card flex-column">
        <h5 class="mb-2 text-titlecase mb-4">Status statistics</h5>
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body d-flex flex-column justify-content-between">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-muted">Transactions</p>
                <p class="mb-0 text-muted">+1.37%</p>
              </div>
              <h4>1352</h4>
              <canvas id="transactions-chart" class="mt-auto" height="65"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body d-flex flex-column justify-content-between">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <p class="mb-2 text-muted">Sales</p>
                  <h6 class="mb-0">563</h6>
                </div>
                <div>
                  <p class="mb-2 text-muted">Orders</p>
                  <h6 class="mb-0">720</h6>
                </div>
                <div>
                  <p class="mb-2 text-muted">Revenue</p>
                  <h6 class="mb-0">5900</h6>
                </div>
              </div>
              <canvas id="sales-chart-a" class="mt-auto" height="65"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row h-100">
        <div class="col-md-6 stretch-card grid-margin grid-margin-md-0">
          <div class="card">
            <div class="card-body d-flex flex-column justify-content-between">
              <p class="text-muted">Sales Analytics</p>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="mb-">27632</h3>
                <h3 class="mb-">78%</h3>
              </div>
              <canvas id="sales-chart-b" class="mt-auto" height="38"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row h-100">
                <div class="col-6 d-flex flex-column justify-content-between">
                  <p class="text-muted">CPU</p>
                  <h4>55%</h4>
                  <canvas id="cpu-chart" class="mt-auto"></canvas>
                </div>
                <div class="col-6 d-flex flex-column justify-content-between">
                  <p class="text-muted">Memory</p>
                  <h4>123,65</h4>
                  <canvas id="memory-chart" class="mt-auto"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 grid-margin stretch-card flex-column">
      <h5 class="mb-2 text-titlecase mb-4">Income statistics</h5>
      <div class="row h-100">
        <div class="col-md-12 stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                  <p class="mb-3">Monthly Increase</p>
                  <h3>67842</h3>
                </div>
                <div id="income-chart-legend" class="d-flex flex-wrap mt-1 mt-md-0"></div>
              </div>
              <canvas id="income-chart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="row">
    <div class="col-xl-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Overall sales</h6>
            <div class="dropdown">
              <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Last 30 days
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                <h6 class="dropdown-header">Settings</h6>
                <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="daoughnut-chart-sm">
            <canvas id="sales-chart-c" class="mt-2"></canvas>
          </div>
          <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 mt-4">
            <div class="d-flex flex-column justify-content-center align-items-center">
              <p class="text-muted">Gross Sales</p>
              <h5>492</h5>
              <div class="d-flex align-items-baseline">
                <p class="text-success mb-0">0.5%</p>
                <i class="typcn typcn-arrow-up-thick text-success"></i>
              </div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center">
              <p class="text-muted">Purchases</p>
              <h5>87k</h5>
              <div class="d-flex align-items-baseline">
                <p class="text-success mb-0">0.8%</p>
                <i class="typcn typcn-arrow-up-thick text-success"></i>
              </div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center">
              <p class="text-muted">Tax Return</p>
              <h5>882</h5>
              <div class="d-flex align-items-baseline">
                <p class="text-danger mb-0">-04%</p>
                <i class="typcn typcn-arrow-down-thick text-danger"></i>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="dropdown">
              <button class="btn bg-white p-0 pb-1 pt-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Last 7 days
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                <h6 class="dropdown-header">Settings</h6>
                <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
            <p class="mb-0">overview</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-4 grid-margin stretch-card">
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card newsletter-card bg-gradient-warning">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <h5 class="mb-3 text-white">Newsletter</h5>
                <form class="form d-flex flex-column align-items-center justify-content-between w-100">
                  <div class="form-group mb-2 w-100">
                    <input type="text" class="form-control" placeholder="email address">
                  </div>
                  <button class="btn btn-danger btn-rounded mt-1" type="submit">Subscribe</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 stretch-card">
          <div class="card profile-card bg-gradient-primary">
            <div class="card-body">
              <div class="row align-items-center h-100">
                <div class="col-md-4">
                  <figure class="avatar mx-auto mb-4 mb-md-0">
                    <img src="assets/images/faces/face20.jpg" alt="avatar">
                  </figure>
                </div>
                <div class="col-md-8">
                  <h5 class="text-white text-center text-md-left">Phoebe Kennedy</h5>
                  <p class="text-white text-center text-md-left">kennedy@gmail.com</p>
                  <div class="d-flex align-items-center justify-content-between info pt-2">
                    <div>
                      <p class="text-white fw-bold">Birth date</p>
                      <p class="text-white fw-bold">Birth city</p>
                    </div>
                    <div>
                      <p class="text-white">16 Sep 2019</p>
                      <p class="text-white">Netherlands</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Sales statistics</h6>
            <div class="dropdown">
              <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Last 7 days
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4">
                <h6 class="dropdown-header">Settings</h6>
                <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <canvas id="sales-chart-d" height="320"></canvas>
        </div>
      </div>
    </div>
  </div> --}}

@endsection


@push('js')
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush