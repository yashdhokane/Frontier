@extends('home')
@section('content')

    <!-- Hero Section -->
    <section class="jumbotron text-center bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4">Download Our App</h1>
            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed
                cursus ante dapibus diam.</p>
            <a href="#" class="btn btn-lg btn-light">Download for iOS</a>
            <a href="#" class="btn btn-lg btn-dark">Download for Android</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <i class="bi bi-phone display-1"></i>
                    <h3>Easy to Use</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-speedometer2 display-1"></i>
                    <h3>Fast Performance</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-shield-check display-1"></i>
                    <h3>Secure</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                </div>
            </div>
        </div>
    </section>


@section('script')
@endsection
@endsection