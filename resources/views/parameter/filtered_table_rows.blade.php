@php
    $time_interval = Session::get('time_interval', 0);
@endphp
@if($tickets->isEmpty())
        
@else
    @foreach ($tickets as $ticket)
        <tr>
            <td>
                <a href="{{ route('tickets.show', $ticket->id) }}" class="fw-bold link"><span
                        class="mb-1 badge bg-secondary">{{ $ticket->id }}</span></a>
            </td>
            <td class="job-details-column">
                <div class="text-wrap2 d-flex">
                    <div class="w-25">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium link">
                            {{ $ticket->job_title ?? null }}</a>
                    </div>
                </div>
            </td>
            <td>{{ $ticket->status }} </td>
            <td>
                @if ($ticket->technician)
                    {{ $ticket->technician->name }}
                @else
                    Unassigned
                @endif
            </td>
            <td>
                @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
                    <div class="font-medium link">
                        {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
                    </div>
                @else
                    <div></div>
                @endif
            </td>
            <td> {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}</td>
            <td>{{ $modifyDateTime($ticket->jobassignname->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}</td>
            <td>
                @if ($ticket->user)
                    {{ $ticket->user->name }}
                @else
                    Unassigned
                @endif
            </td>
            <td>
                @if ($ticket->user)
                    {{ $ticket->user->email }}
                @else
                    ''
                @endif
            </td>
            <td>
                @if ($ticket->user)
                    {{ $ticket->user->mobile }}
                @else
                    ''
                @endif
            </td>
            <td>
                @if (isset($ticket->address) && $ticket->address !== '')
                    {{ $ticket->address }},
                @endif

                @if (isset($ticket->city) && $ticket->city !== '')
                    {{ $ticket->city }},
                @endif

                @if (isset($ticket->state) && $ticket->state !== '')
                    {{ $ticket->state }},
                @endif

                @if (isset($ticket->zipcode) && $ticket->zipcode !== '')
                    {{ $ticket->zipcode }}
                @endif
            </td>
            <td>
                @if ($ticket->warranty_type == 'in_warranty')
                    In Warranty
                @elseif($ticket->warranty_type == 'out_warranty')
                    Out Of Warranty
                @endif
            </td>
            <td>
                @if ($ticket->warranty_type == 'in_warranty')
                    {{ $ticket->warranty_ticket ?? null }}
                @elseif($ticket->warranty_type == 'out_warranty')
                    00
                @endif
            </td>
            <td>
                @if ($ticket->jobassignname->duration ?? null)
                        <?php
                    $durationInMinutes = $ticket->jobassignname->duration ?? null;
                    $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
                                                        ?>
                @endif
                {{ $durationInHours ?? '--' }} Hours
            </td>
            <td> {{ $ticket->priority ?? null }} </td>
            <td> {{ $ticket->subtotal ?? null }} </td>
            <td> {{ $ticket->tax ?? null }} </td>
            <td> {{ $ticket->discount ?? null }} </td>
            <td> {{ $ticket->gross_total ?? null }} </td>
            <td> {{ $ticket->is_confirmed ?? null }} </td>
            <td> {{ $ticket->is_published ?? null }} </td>
            <td> {{ $ticket->invoice_status ?? null }} </td>
            <td> {{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}</td>
            <td>  {{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}</td>
            <td> {{ $ticket->JobAppliances->Appliances->model_number ?? null }}</td>
            <td> {{ $ticket->JobAppliances->Appliances->serial_number ?? null }}</td>
            <td>  
                @foreach ($ticket->jobserviceinfo as $service)
                    {{ $service->service->service_name ?? null }} 
                    @if(!empty($service->service) && !empty($service->service->service_name))
                        ,
                    @endif
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobserviceinfo as $service)
                    {{ $service->base_price ?? null }} ,
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobserviceinfo as $service)
                    {{ $service->discount ?? null }} ,
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobserviceinfo as $service)
                    {{ $service->sub_total ?? null }} ,
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobproductinfo as $product)
                    {{ $product->product->product_name ?? null }}  
                     @if(!empty($product->product) && !empty($product->product->product_name))
                        ,
                    @endif
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobproductinfo as $product)
                    {{ $product->base_price ?? null }} ,
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobproductinfo as $product)
                    {{ $product->discount ?? null }} ,
                @endforeach
            </td>
            <td>  
                 @foreach ($ticket->jobproductinfo as $product)
                    {{ $product->sub_total ?? null }} ,
                @endforeach
            </td>
            <td> {{ $ticket->JobTechEvent->job_enroute ?? '-' }}</td>
            <td> {{ $ticket->JobTechEvent->job_start ?? '-' }}</td>
            <td> {{ $ticket->JobTechEvent->job_end ?? '-' }}</td>
            <td> {{ $ticket->JobTechEvent->job_invoice ?? '-' }}</td>
            <td> {{ $ticket->JobTechEvent->job_payment ?? '-' }}</td>

        </tr>
    @endforeach
@endif
