@extends('home')

@section('content')

    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Basic Draggable options</h4>
                <form id="positionForm" method="POST" action="{{ route('savePositions') }}">
                    @csrf
                    <input type="hidden" name="positions" id="positions">
                    <div class="row draggable-cards" id="draggable-area">

                        @foreach ($cardPositions as $cardPosition)
                            @if ($cardPosition->element_id == 1)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info">
                                            <h4 class="mb-0 text-white">Card Title 1</h4>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 1</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 1</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->element_id == 2)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger">
                                            <h4 class="mb-0 text-white">Card Title 2</h4>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 2</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 2</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->element_id == 3)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-success">
                                            <h4 class="mb-0 text-white">Card Title 3</h4>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 3</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 3</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->element_id == 4)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning">
                                            <h4 class="mb-0 text-white">Card Title 4</h4>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 4</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 4</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save Positions</button>
                </form>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>

    <div class="chat-windows"></div>

@section('script')
    <style>
        .gu-mirror {
            opacity: 0.6;
            position: fixed;
            z-index: 9999;
            pointer-events: none;
        }
    </style>
    <script>
        $(function() {
            dragula([document.getElementById('draggable-area')])
                .on('drag', function(e) {
                    e.className = e.className.replace('card-moved', '');
                })
                .on('over', function(e, t) {
                    t.className += ' card-over';
                })
                .on('out', function(e, t) {
                    t.className = t.className.replace('card-over', '');
                });


            $('#positionForm').on('submit', function(event) {
                var positions = [];
                $('#draggable-area .col-md-6').each(function(index, element) {
                    positions.push({
                        element_id: $(element).data('id'),
                        position: index
                    });
                });
                $('#positions').val(JSON.stringify(positions));
            });
        });
    </script>
@endsection

@endsection
