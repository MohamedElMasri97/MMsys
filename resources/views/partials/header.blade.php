<nav class="navbar navbar-expand-lg navbar-light shadow" style="background:rgb(209, 255, 209);">
    <a class="navbar-brand" href="{{route('Home')}}">Median system</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav nav nav-pills ml-auto">
            <li class="nav-item {{ Route::currentRouteName() == 'Instrumentsview' ? 'active' : ''}}">
                <a class="nav-link {{ Route::currentRouteName() == 'Instrumentsview' ? 'disabled' : ''}}"
                    href="{{route('Instrumentsview')}}">
                    {{-- <i class="fa fa-cart-plus " style="font-size:1.3em;" aria-hidden="true"></i> --}}
                    {{-- <span class="badge badge-secondary">{{session()->has('cart') ? session()->get('cart')->totalQuentity:''}}</span> --}}
                    Instruments
                </a>
            </li>
            <li class="divider"></li>
            <li class="nav-item {{ Route::currentRouteName() == 'samplesview' ? 'active' : ''}}">
                <a class="nav-link {{ Route::currentRouteName() == 'samplesview' ? 'disabled' : ''}}"
                    href="">
                    Samples
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'Home' ? 'active' : ''}}">
                <a class="nav-link {{ Route::currentRouteName() == 'Home' ? 'disabled' : ''}}"
                    href="{{route('Home')}}">
                    Home
                </a>
            </li>
        </ul>
    </div>
</nav>
