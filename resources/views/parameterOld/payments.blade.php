<div class="col-12 card card-border shadow">
                <div class="card-body">

                    <table id="file_export" class="table table-hover table-striped">
                        <thead>
                            <!-- start row -->
                            <tr>
                                <th>ID</th>
                                <th>Manufacturer</th>
                                <th>Job Details</th>
                                <th>Customer</th>
                                <th>Technician</th>
                                <th>Inv. Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <!-- end row -->
                        </thead>
                        <tbody>
                            <!-- start row -->
                            @foreach ($payments as $index => $item)
                                <tr>
                                    <td><a
                                            href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                    </td>
                                    <td>{{ $item->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}
                                    </td>

                                    <td>{{ $item->JobModel->job_code ?? null }}<br>{{ $item->JobModel->job_title ?? null }}
                                    </td>
                                    <td>{{ $item->user->name ?? null }}</td>
                                    <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                    <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}</td>
                                    <td>${{ $item->total ?? null }}</td>
                                    <td style="text-transform: capitalize;">{{ $item->status ?? null }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ url('invoice-detail/' . $item->id) }}"><i
                                                        data-feather="eye" class="feather-sm me-2"></i>
                                                    View</a>

                                                <!-- Comments option -->
                                                <a class="dropdown-item add-comment" href="javascript:void(0)"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#commentModal{{ $index }}">
                                                    <i data-feather="message-circle" class="feather-sm me-2"></i>
                                                    Comments
                                                </a>
                                                @if ($item->status != 'paid')
                                                    <a class="dropdown-item"
                                                        href="{{ url('update/payment/' . $item->id) }}"><i
                                                            data-feather="edit-2" class="feather-sm me-2"></i>
                                                        Mark Complete</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <!-- Modal for adding comment -->
                                <div class="modal fade" id="commentModal{{ $index }}" tabindex="-1"
                                    aria-labelledby="commentModalLabel{{ $index }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="commentModalLabel{{ $index }}">
                                                    Add Comment
                                                </h5>
                                                {{-- <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button> --}}
                                            </div>
                                            <!-- Comment form -->
                                            <form action="{{ url('store/comment/' . $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="comment">Comment:</label>
                                                        <textarea class="form-control" id="comment" name="payment_note" rows="3">
                                                                            
                                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>