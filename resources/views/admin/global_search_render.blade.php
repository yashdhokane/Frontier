@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
@endif
<!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ3Q1f+7pH22o6d7b1rUl1qQU1F6hsR00sHV8k5azQ9o1Nq0zFbZV+E8v5T1" crossorigin="anonymous">

 <style>
//     .paginate_laravel svg {
//         width: 15px;
//     }

//     .paginate_laravel nav > div:first-child {
//         display: none;
//     }

//     .paginate_laravel nav > div:nth-child(2) {
//         display: flex;
//         justify-content: space-between;
//     }
 </style>

<style>
    .fixed-height-card {
        height: 600px; /* Set a fixed height */
        overflow-y: auto; /* Enable vertical scrolling if content overflows */
    }
    .col-md-4{
         margin-top: 10px;
    }
</style>

<div class="container-fluid">
    <div class="row">
     <h4 class="card-title">Search Results For "{{ $query }}" {{ $totalResultsCount }} result(s)</h4>
        <h6 class="card-subtitle"> <h6 class="card-subtitle"> </h6>
</h6>

       @if($jobs->isNotEmpty())
    <div class="col-md-4">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Jobs</h4>
                @foreach($jobs as $row)
                    <a href="{{ url('tickets/'.$row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($users->isNotEmpty())
    <div class="col-md-4">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Dispatchers</h4>
                @foreach($users as $row)
                    <a href="{{ url('dispatcher/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($customers->isNotEmpty())
    <div class="col-md-4">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Customers</h4>
                @foreach($customers as $row)
                    <a href="{{ url('customers/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($services->isNotEmpty())
    <div class="col-md-4">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Services</h4>
                @foreach($services as $row)
                    <a href="{{ url('book-list/services-list/'.$row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($products->isNotEmpty())
    <div class="col-md-4">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Parts</h4>
                @foreach($products as $row)
                    <a href="{{ url('book-list/parts/'.$row->id.'/edit') }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

    </div>
</div>




@section('script')


<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
      <!-- Include imagesLoaded.js -->
      <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
      <script>
          window.onload = function() {
              var elem = document.querySelector('#masonry-grid');
              imagesLoaded(elem, function() {
                  var msnry = new Masonry(elem, {
                      itemSelector: '.masonry-item',
                      columnWidth: '.masonry-item',
                      percentPosition: true
                  });
              });
          };
      </script>
<script>
 $(document).ready(function () {
    let table = new DataTable('#globalSearchTable', {
        paging: true,          // Enables pagination
        searching: true,       // Enables search
        ordering: true,        // Enables sorting
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Option to show all records
        pageLength: 10,        // Default records per page
        dom: 'Bfrtip',         // Enable buttons
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Download Excel',
                title: 'Global Search Data',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: 'Download PDF',
                title: 'Global Search Data',
                className: 'btn btn-danger'
            }
        ]
    });
});

    </script>




@endsection

@if (Route::currentRouteName() != 'dash')
    @endsection
@endif