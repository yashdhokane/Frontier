<div class="container py-4">
    <div class="row">
        @foreach ($vehicle as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card card-border shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 justify-content-between">
                            <div class="d-flex">
                                @if ($item->vehicle && $item->vehicle->vehicle_image)
                                    <img src="{{ asset('public/vehicle_image/' . $item->vehicle->vehicle_image) }}"
                                        alt="{{ $item->vehicle->vehicle_name ?? 'Vehicle Image' }}" class="rounded-circle me-3"
                                        width="60" height="60">
                                @else
                                    <img src="{{ asset('public/images/vehicle.jfif') }}" alt="Default Vehicle Image"
                                        class="rounded-circle me-3" width="60" height="60">
                                @endif

                                <div>
                                    <h5 class="card-title mb-0">
                                        <a href="{{ route('fleet.fleetedit', ['id' => $item->vehicle_id]) }}"
                                            class="text-primary">
                                            {{ $item->vehicle->vehicle_name ?? 'N/A' }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-0">{{ $item->vehicle->vehicle_no ?? 'N/A' }}</p>
                                </div>
                            </div>


                        </div>
                        <div class="mb-2"><strong>Technician Assigned:</strong>
                            {{ $item->vehicle->technician->name ?? 'N/A' }}</div>
                        <div class="mb-2"><strong>Last Modified:</strong>
                            @if ($item->vehicle && $item->vehicle->updated_at)
                               {{ \Carbon\Carbon::parse($item->vehicle->updated_at)->format('jS F Y h:i A') }}

                            @else
                                N/A
                            @endif
                        </div>


                        <div class="mb-2">
                            <strong>Insurance Valid Until:</strong>
                        @if ($item && $item->valid_upto)
                            @php
                                $validUpto = \Carbon\Carbon::parse($item->valid_upto);
                                $remainingDays = $validUpto->diffInDays(\Carbon\Carbon::now());
                            @endphp
                            {{ $validUpto->format('jS F Y') }} 
                             <div>
                                @if ($validUpto->isFuture())
                                    ({{ $remainingDays }} days remaining)
                                @else
                                    (Expired {{ $validUpto->diffForHumans() }})
                                @endif
                             </div>
                        @else
                            N/A
                        @endif

                        </div>
                        <div class="mt-3">
                            <div class="btn-group">

                                @if ($item->vehicle->technician && $item->vehicle->technician->name)
                                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#VehicleModal" data-tech-id="{{ $item->vehicle->technician->id }}">
                                        <i class="ri-send-plane-line align-middle"></i> Send Message
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="modal fade" id="VehicleModal" tabindex="-1" aria-labelledby="VehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="VehicleModalLabel">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tech_id" value="">
                <div class="mb-3">
                    <label for="message">Message:</label>
                    <textarea class="form-control" id="vehicle-message" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="vehicleMessagesubmit">Submit</button>
            </div>
        </div>
    </div>
</div>