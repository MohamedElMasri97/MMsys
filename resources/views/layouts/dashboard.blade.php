<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title')</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
    @yield('style')
</head>

<body>

    <div class="wrapper">
        @include('partials.dashboardsidebar')
        <!-- Sidebar  -->

        <!-- Page Content  -->
        <div id="content">
            @include('partials.dashboardnav')
            @yield('content')

        </div>
    </div>

    {{-- <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script> --}}

    <script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/vue.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/all.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
            $('#theme').on('click',function(){
                // If the OS is set to dark mode...
                if (prefersDarkScheme.matches) {
                    console.log('dark')
                    // ...then apply the .light-theme class to override those styles
                    document.body.classList.toggle("light-theme");
                    // Otherwise...
                } else {
                    console.log()
                    // ...apply the .dark-theme class to override the default light styles
                    document.body.classList.toggle("dark-theme");
                }
            })
        });

    </script>
    @yield('script')
</body>

</html>
