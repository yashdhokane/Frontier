@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
@endif
<!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ3Q1f+7pH22o6d7b1rUl1qQU1F6hsR00sHV8k5azQ9o1Nq0zFbZV+E8v5T1" crossorigin="anonymous">

<style>
    .paginate_laravel svg {
        width: 15px;
    }

    .paginate_laravel nav > div:first-child {
        display: none;
    }

    .paginate_laravel nav > div:nth-child(2) {
        display: flex;
        justify-content: space-between;
    }
</style>


<div class="container-fluid">
    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->
    <div class="row">
        <div class="col-12">
            <!-- --------------------- Start Search Result For "{{ $query }}" ---------------- -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Search Results For "{{ $query }}"</h4>
                    <h6 class="card-subtitle"> {{ $results->total() }} result(s) ({{ $results->lastPage() }} pages)</h6>

                   <ul class="search-listing list-style-none">
    @forelse($results as $row)
        <li class="border-bottom pt-3">
            <h4 class="mb-0">
                <a class="text-cyan font-medium p-0"
                   id="search-link-{{ $loop->iteration }}" 
                   href="
                       @if($row->type == 'Service')
                           https://dispatchannel.com/portal/book-list/services-list/{{ $row->id }}
                       @elseif($row->type == 'Product')
                           https://dispatchannel.com/portal/book-list/parts/{{ $row->id }}/edit
                       @elseif($row->type == 'User')
                           https://dispatchannel.com/portal/dispatcher/show/{{ $row->id }}
                       @elseif($row->type == 'Job')
                           https://dispatchannel.com/portal/tickets/{{ $row->id }}
                       @elseif($row->type == 'Customer')
                           https://dispatchannel.com/portal/customers/show/{{ $row->id }} 
                       @endif
                   "
                   class="text-cyan font-medium p-0" 
                   id="search-link-{{ $loop->iteration }}">
                   {{ $row->result }}
                </a>
            </h4>
            <p style="display:none;">{{ $row->type }}</p>
            <p>{{ $row->short_description }}</p>
        </li>
    @empty
        <li class="text-center">
            <p>No results found.</p>
        </li>
    @endforelse
</ul>


                    <!-- Pagination Links -->
                     <div class="justify-content-center mt-3 paginate_laravel">
                {{ $results->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
                </div>
            </div>
            <!-- --------------------- End Search Result For "{{ $query }}" ---------------- -->
        </div>
    </div>
</div>

@section('script')
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let searchInput = document.querySelector('.app-search input');
        let resultsTableBody = document.querySelector("#resultsTable tbody");

        searchInput.addEventListener('input', function () {
            let query = this.value.trim();

            if (query.length > 2) {
                fetch({{ route('global.search') }}?query=${query})
                    .then(response => response.json())
                    .then(data => {
                        resultsTableBody.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(row => {
                                resultsTableBody.innerHTML += <tr>
                                    <td>${row.type}</td>
                                    <td>${row.result}</td>
                                </tr>;
                            });
                        } else {
                            resultsTableBody.innerHTML = <tr>
                                <td colspan="2" class="text-center">No results found.</td>
                            </tr>;
                        }
                    })
                    .catch(error => console.error("Error fetching search results:", error));
            }
        });
    });
</script>


@endsection

@if (Route::currentRouteName() != 'dash')
    @endsection
@endif