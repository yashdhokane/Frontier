@php
$time_interval = Session::get('time_interval', 0);
@endphp
@foreach ($tickets as $ticket)
	<tr>
		<td>
			<input type="checkbox" class="form-check-input primary jobIds" name="jobIds[]" value="{{ $ticket->id }}"
				onchange="checkAllSelected()">
		</td>
		<td class="job-details-column">
			<div class="text-wrap2 d-flex">
				<div class=" text-truncate">
					<a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium link">
						#{{ $ticket->id }}-{{ $ticket->job_title ?? null }}</a>
				</div>
				<span class="badge bg-light-warning text-warning font-medium">{{ $ticket->status
					}}</span>
			</div>
			<div style="font-size:12px;">
				@if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
					{{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
				@endif
				@if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
					{{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null
					}}/
				@endif
				@if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->model_number)
					{{ $ticket->JobAppliances->Appliances->model_number ?? null }}/
				@endif
				@if (
					$ticket->JobAppliances &&
					$ticket->JobAppliances->Appliances->serial_number
				)
							{{ $ticket->JobAppliances->Appliances->serial_number ?? null }}
				@endif
			</div>
		</td>
		<td class="text-uppercase">
			@if ($ticket->user)
				{{ $ticket->user->name }}
			@else
				Unassigned
			@endif
		</td>
		<td class="text-uppercase">
			@if ($ticket->technician)
				{{ $ticket->technician->name }}
			@else
				Unassigned
			@endif
		</td>
		<!-- Hidden column -->
		<td>
			@if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
				<div class="font-medium link">
					{{
					\Carbon\Carbon::parse($ticket->jobassignname->start_date_time)->addMinutes($time_interval)->format('m-d-Y')
					}}
				</div>
			@else
				<div></div>
			@endif
			<div style="font-size:12px;">
				{{
			\Carbon\Carbon::parse($ticket->jobassignname->start_date_time)->addMinutes($time_interval)->format('h:i
				A') }}
				to
				{{
			\Carbon\Carbon::parse($ticket->jobassignname->end_date_time)->addMinutes($time_interval)->format('h:i
				A') }}
			</div>
		</td>
	</tr>
@endforeach