@extends('home')
@section('content')
    <style>
        .table-responsive .description-column {
            max-width: 200px;
            /* Adjust the maximum width as needed */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <div class="page-wrapper" style="display: inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">{{ \App\Models\ServiceCategory::find($category_id)->category_name ?? null }}</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Price Book</a>
                                </li>
                                <li class="breadcrumb-item"><a href="">Services</a></li>

                                @if ($category_id)
                                    <li class="breadcrumb-item">
                                        {{ \App\Models\ServiceCategory::find($category_id)->category_name ?? null }}
                                    </li>
                                @else
                                    <li class="breadcrumb-item"><a href="">Category</a></li>
                                @endif
                            </ol>
                        </nav>
                    </div>
                </div>
            
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}

            </div>
        @endif
        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <div class="container-fluid">
            <!-- -------------------------------------------------------------- -->
            <!-- Start Page Content -->
            <!-- -------------------------------------------------------------- -->
            <div class="widget-content searchable-container list">
                <!-- ---------------------
                            start Contact
                        ---------------- -->
                <div class="card card-body card-border shadow">
                    <div class="row">
                        <div class="col-md-4 col-xl-2">
                            <form>
                                <input type="text" class="form-control product-search" id="input-search"
                                    placeholder="Search Service..." />
                            </form>
                        </div>
                        <div
                            class="
                    col-md-8 col-xl-10
                    text-end
                    d-flex
                    justify-content-md-end justify-content-center
                    mt-3 mt-md-0
                  ">
                            <div class="action-btn show-btn" style="display: none">
                                <a href="javascript:void(0)"
                                    class="
                        delete-multiple
                        btn-light-danger btn
                        me-2
                        text-danger
                        d-flex
                        align-items-center
                        font-medium
                      ">
                                    <i data-feather="trash-2" class="feather-sm fill-white me-1"></i>
                                    Delete All Row</a>
                            </div>
                            <a href="{{ route('services.createServices') }}" id="btn-add-contact" class="btn btn-info">
                                <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                                Add New Service</a>
                        </div>
                    </div>
                </div>
                <!-- ---------------------
                            end Contact
                        ---------------- -->

                <div class="card card-body  card-border shadow">
                    <div class="table-responsive table-custom">
                        <table class="table table-hover table-striped search-table v-middle text-nowrap">
                            <thead class="header-item">
								<tr>
									<th>Services</th>
									<th>Description</th>
									<th>Service Code</th>
									<th>Base Pricing</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
                            </thead>

                            <tbody id="service-table-body">
                                @foreach ($service as $service)
                                    <!-- start row -->
                                    <tr class="search-items">

                                        <td>
 
                                                     <a href="{{ route('services.edit', ['service_id' => $service->service_id]) }}"
                                                        class="text-dark edit ms-2">  {{ $service->service_name ?? null }}</a>
 
                                         </td>
                                        <td class="description-column">{{ $service->service_description ?? null }}</td>
                                        <td>{{ $service->service_code ?? null }}

                                        </td>
                                        <td>
                                            {{ $service->service_cost ?? null }}
                                        </td>
										<td>
											@if ($service->service_active == 'yes')
												<span class="mb-1 ucfirst badge bg-success">Active</span>
											@else
												<span class="mb-1 ucfirst badge bg-danger">Inactive</span>
											@endif
 										</td>
                                         
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-light-primary text-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($service->service_active == 'yes')
                                                        <a class="dropdown-item"
                                                            href="{{ url('inactive/service/' . $service->service_id) }}"><i
                                                                data-feather="edit-2"
                                                                class="feather-sm me-2"></i>
                                                            Inactive</a>
                                                    @else 
                                                        <a class="dropdown-item"
                                                            href="{{ url('active/service/' . $service->service_id) }}"><i
                                                                data-feather="edit-2"
                                                                class="feather-sm me-2"></i>
                                                            Active</a>
                                                    @endif

                                                     <a class="dropdown-item" href="{{ route('services.edit', ['service_id' => $service->service_id]) }}"><i
                                                            data-feather="edit-2" class="feather-sm me-2"></i> Edit</a>
                                                          <form method="POST" action="{{ route('services.delete', ['service_id' => $service->service_id]) }}">
    @csrf
    @method('DELETE')

    <button type="submit" class="dropdown-item">
        <i data-feather="trash-2" class="feather-sm me-2"></i>Delete
    </button>
</form>
 
                                                </div>
                                            </div>
                                        </td>
                                       

                                    </tr> 
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- -------------------------------------------------------------- -->
            <!-- End PAge Content -->
            <!-- -------------------------------------------------------------- -->
        </div>
        <!-- Share Modal -->
        <div class="modal fade" id="Sharemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-header d-flex align-items-center">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <i class="ri-share-fill me-2 align-middle"></i> Share With
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <button type="button" class="btn btn-info">
                                    <i class="ti-user text-white"></i>
                                </button>
                                <input type="text" class="form-control" placeholder="Enter Name Here"
                                    aria-label="Username" />
                            </div>
                            <div class="row">
                                <div class="col-3 text-center">
                                    <a href="#Whatsapp" class="text-success">
                                        <i class="display-6 ri-whatsapp-fill"></i><br /><span
                                            class="text-muted">Whatsapp</span>
                                    </a>
                                </div>
                                <div class="col-3 text-center">
                                    <a href="#Facebook" class="text-info">
                                        <i class="display-6 ri-facebook-fill"></i><br /><span
                                            class="text-muted">Facebook</span>
                                    </a>
                                </div>
                                <div class="col-3 text-center">
                                    <a href="#Instagram" class="text-danger">
                                        <i class="display-6 ri-instagram-fill"></i><br /><span
                                            class="text-muted">Instagram</span>
                                    </a>
                                </div>
                                <div class="col-3 text-center">
                                    <a href="#Skype" class="text-cyan">
                                        <i class="display-6 ri-skype-fill"></i><br /><span class="text-muted">Skype</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-send-plane-fill align-middle"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>

@section('script')
    <script src="{{ asset('public/admin/dist/js/pages/contact.js') }}"></script>
    <script>
    document.getElementById('input-search').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#service-table-body .search-items');
        let firstMatch = null;

        rows.forEach(row => {
            const serviceName = row.querySelector('td a').textContent.toLowerCase();
            const description = row.querySelector('.description-column').textContent.toLowerCase();
            const serviceCode = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const basePricing = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(5) span').textContent.toLowerCase();

            if (serviceName.includes(searchTerm) || 
                description.includes(searchTerm) || 
                serviceCode.includes(searchTerm) || 
                basePricing.includes(searchTerm) || 
                status.includes(searchTerm)) {
                row.style.display = '';
                if (!firstMatch) {
                    firstMatch = row;
                }
            } else {
                row.style.display = 'none';
            }
        });

        // Scroll to the first matching row if found
        if (firstMatch) {
            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

@stop
@stop
