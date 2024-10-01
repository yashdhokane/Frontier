  @if (Route::currentRouteName() != 'dash')
      @extends('home')
      @section('content')
      @endif
      <style>
          #main-wrapper[data-layout="vertical"][data-sidebartype="mini-sidebar"] .page-wrapper {
              margin-left: 0px !important;
          }

          /* .card-body {
                                                                                                                                                                                                                        padding: 0px !important;
                                                                                                                                                                                                                    } */

          .container-fluid {
              padding: 0px !important;
          }

          #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar {
              display: none !important;
          }

          #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar {
              display: none !important;
          }

          #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
              margin-left: 0px !important;
          }

          #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
              padding-top: 0px !important;
          }

          .page-wrapper {
              padding: 0px !important;
          }

          /* Make iframe content scrollable */
          html,
          body {
              overflow: auto !important;
              /* Allow scrolling */
              margin: 0;
              /* Remove default margins */
              padding: 0;
              /* Remove default padding */
          }
      </style>
      <!-- Page wrapper  -->
      <!-- -------------------------------------------------------------- -->
      <div class="" style="display:inline;">
          <!-- -------------------------------------------------------------- -->
          <div class="page-breadcrumb">
              <div class="row">


                  <div class="card threedottest" style="display:block;">
                      <div class="row card-body">
                          <!-- Search Input on the Left -->
                          <div class="col-6 align-self-center">
                              {{-- <form>
                                  <input type="text" class="form-control" id="searchInput" placeholder="Search Parts"
                                      onkeyup="filterTable()" />
                              </form> --}}
                              <h4 class="breadcrumb-item active" aria-current="page">Categories</h4>

                          </div>

                          <!-- Three Dot Dropdown on the Right -->
                          <div class="col-6 align-self-center">
                              <div class="d-flex justify-content-end">
                                  <!-- Dropdown Menu for Filters -->
                                  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                      <div class="dropdown">
                                          <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                              aria-expanded="false">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round" stroke-linejoin="round"
                                                  class="feather feather-more-vertical feather-sm">
                                                  <circle cx="12" cy="12" r="1"></circle>
                                                  <circle cx="12" cy="5" r="1"></circle>
                                                  <circle cx="12" cy="19" r="1"></circle>
                                              </svg>
                                          </a>
                                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                              <!-- Filters Section -->

                                              <!-- Parts, Tools, vehicle_iframe_index -->
                                              <li>
                                                  <a href="{{ route('product.index_iframe') }}"
                                                      class="dropdown-item {{ Route::currentRouteName() === 'product.index_iframe' ? 'btn-info' : '' }}">Parts</a>
                                              </li>
                                              <li>
                                                  <a href="{{ route('tool.index_iframe') }}"
                                                      class="dropdown-item {{ Route::currentRouteName() === 'tool.index_iframe' ? 'btn-info' : '' }}">Tools</a>
                                              </li>
                                              <li>
                                                  <a href="{{ route('vehicle_iframe_index') }}"
                                                      class="dropdown-item {{ Route::currentRouteName() === 'vehicle_iframe_index' ? 'btn-info' : '' }}">vehicle</a>
                                              </li>
                                              <li class="dropdown-submenu">
                                                  <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'iframe_part_assign' || Route::currentRouteName() === 'assign_tool.iframe' ? 'btn-info' : '' }}"
                                                      href="javascript:void(0)">Assign</a>
                                                  <ul class="dropdown-menu">
                                                      <li><a class="dropdown-item {{ Route::currentRouteName() === 'iframe_part_assign' ? 'btn-info' : '' }}"
                                                              href="{{ route('iframe_part_assign') }}">Parts</a></li>
                                                      <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool.iframe' ? 'btn-info' : '' }}"
                                                              href="{{ route('assign_tool.iframe') }}">Tools</a></li>
                                                  </ul>
                                              </li>
                                              <li class="dropdown-submenu">
                                                  <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'iframeaddvehicle' || Route::currentRouteName() === 'product.createproduct.iframe' || Route::currentRouteName() === 'tool.createtool.iframe' ? 'btn-info' : '' }}"
                                                      href="javascript:void(0)">Add New</a>
                                                  <ul class="dropdown-menu">
                                                      <li><a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct.iframe' ? 'btn-info' : '' }}"
                                                              href="{{ route('product.createproduct.iframe') }}">Parts</a>
                                                      </li>
                                                      <li><a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool.iframe' ? 'btn-info' : '' }}"
                                                              href="{{ route('tool.createtool.iframe') }}">Tools</a></li>
                                                      <li><a class="dropdown-item {{ Route::currentRouteName() === 'iframeaddvehicle' ? 'btn-info' : '' }}"
                                                              href="{{ route('iframeaddvehicle') }}">vehicle</a>
                                                      </li>
                                                      <li><a href="javascript:void(0)" id="btn-add-contact"
                                                              data-bs-toggle="modal" class="dropdown-item"
                                                              data-bs-target="#add-contact">Category</a></li>
                                                  </ul>
                                              </li>
                                              <li>
                                                  <a href="{{ route('partCategoryiframe') }}"
                                                      class="dropdown-item {{ Route::currentRouteName() === 'partCategoryiframe' ? 'btn-info' : '' }}">Categories</a>
                                              </li>
                                              <li>
                                                  <hr class="dropdown-divider">
                                              </li>
                                              {{-- <li>
                                                  <a href="#." id="filterButton" class="dropdown-item">
                                                      <i class="ri-filter-line"></i> Filters
                                                  </a>
                                              </li> --}}

                                          </ul>
                                      </div>
                                  </div>


                              </div>
                          </div>
                      </div>
                  </div>

              </div>
          </div>
          <!-- -------------------------------------------------------------- -->
          <!-- Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->

          @if (Session::has('success'))
              <div class="alert_wrap">
                  <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                      {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                          aria-label="Close"></button>
                  </div>
              </div>
          @endif

          @if (Session::has('error'))
              <div class="alert_wrap">
                  <div class="alert alert-danger">
                      {{ Session::get('error') }}
                  </div>
              </div>
          @endif
          <!-- ------------------------------------------------------------ -->
          <!-- End Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->
          <!-- -------------------------------------------------------------- -->
          <!-- Container fluid  -->
          <!-- -------------------------------------------------------------- -->
          <div class="show-categories" style="min-height:500px;">

              <div class="container-fluid px-3 mt-2">

                  <div class="">
                      <div class="row">
                          <div class="col-md-4 col-xl-2">
                              <h4 class="page-title"></h4>
                          </div>
                          <div>

                          </div>

                          <!-- Add Popup Model  1 latest -->
                          <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                              aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header d-flex align-items-center">
                                          <h4 class="modal-title" id="myModalLabel">New Category</h4>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                              aria-label="Close" fdprocessedid="k7jfjv"></button>
                                      </div>
                                      <div class="modal-body">
                                          <form action="{{ route('productcategory.store') }}" method="post"
                                              class="form-horizontal form-material" enctype="multipart/form-data">
                                              @csrf
                                              <div class="form-group">
                                                  <div class="col-md-12 mb-3"> <input type="text" class="form-control"
                                                          name="category_name" placeholder="Category Name" required />
                                                  </div>
                                                  <div class="col-md-12 mb-3">
                                                      <div class="">
                                                          <label for="" class="my-2">Category Image </label>
                                                          <input type="file" name="category_image"
                                                              class="form-control" />
                                                      </div>
                                                  </div>
                                              </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                              Save
                                          </button>
                                          <button type="button" class="btn btn-info waves-effect"
                                              data-bs-dismiss="modal">
                                              Cancel
                                          </button>

                                      </div>
                                      </form>
                                  </div>
                                  <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                          </div>
                          <!-- End Popup Model 1 latest-->

                      </div>
                  </div>


                  <div class="row">

                      <!-- column -->
                      @foreach ($productcategory as $item)
                          <div class="col-lg-3 col-md-6 col-xl-2">
                              <!-- Card -->

                              <div class="card">


                                  <a href="">

                                      @if ($item->category_image)
                                          <img class="card-img-top img-responsive"
                                              src="{{ asset('public/images/parts/' . $item->id . '/' . $item->category_image) }}"
                                              style="width:228.5px; height:200px;" alt="Card image cap" />
                                      @else
                                          <img class="card-img-top img-responsive"
                                              src="{{ asset('public/images/Noimage.png') }}"
                                              style="width:228.5px; height:200px;" alt="Default Image" />
                                      @endif
                                  </a>



                                  <div class="card-body">
                                      <h6 class="card-title">{{ $item->category_name }} <div
                                              class="dropdown dropstart srdrop ">

                                              <a href="#" class="link" id="dropdownMenuButton"
                                                  data-bs-toggle="dropdown" aria-expanded="false">

                                                  <i data-feather="more-vertical" class="feather-sm"></i>

                                              </a>

                                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                                  <li><a class="dropdown-item viewinfo" href="javascript:void(0)"
                                                          data-bs-toggle="modal" data-bs-target="#add-contact1"
                                                          id="{{ $item->id }}">Edit</a>
                                                  </li>

                                                  <li>
                                                      <form method="post"
                                                          action="{{ route('productcategory.delete', ['id' => $item->id]) }}">
                                                          @csrf
                                                          @method('DELETE')


                                                          <button type="submit" class="dropdown-item"
                                                              style="border: none; background: none; cursor: pointer;">
                                                              Delete
                                                          </button>
                                                      </form>
                                                  </li>
                                              </ul>

                                          </div>
                                      </h6>

                                  </div>


                              </div>
                          </div>
                      @endforeach



                      <!-- Card -->

                      <!-- edit Popup Model  1 -->
                      <div id="add-contact1" class="modal fade in" tabindex="-1" role="dialog"
                          aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header d-flex align-items-center">
                                      <h4 class="modal-title" id="myModalLabel">Edit Parts Category</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"
                                          aria-label="Close" fdprocessedid="k7jfjv"></button>
                                  </div>


                                  <div class="modal-body" id="appendbody">

                                  </div>

                                  <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                          </div>
                      </div>
                      <!-- edit End Popup Model 1 -->




                      <!-- column -->
                      <!-- column -->

                  </div>
                  <!-- Row -->


              </div>

          </div>



          <!-- -------------------------------------------------------------- -->
          <!-- Recent comment and chats -->
          <!-- -------------------------------------------------------------- -->
      </div>
      </div>

      @section('script')

          <script>
              $(document).on('click', '.viewinfo', function() {
                  $("#add-contact1").modal({
                      backdrop: "static",
                      keyboard: false,
                  });
                  var entry_id = $(this).attr('id');
                  $("#appendbody").empty();
                  $.ajax({
                      url: '{{ route('editproduct') }}',
                      type: 'get',
                      data: {
                          entry_id: entry_id

                      },
                      dataType: 'json',
                      success: function(data) {
                          $("#appendbody").html(data);
                      }
                  });
              });
          </script>
      @stop
      @if (Route::currentRouteName() != 'dash')
      @stop
  @endif
