 <div class="row">

                <!-- Table Section -->
                <div class="col-lg-9 col-md-8">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-hover table-striped documents-tabletable">
                            <thead class="header-item">
                                <tr>
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>Uploaded Document</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                <tr data-id="{{ $document->id }}">
                                    <td class="document-name">{{ $document->document_name ?? '' }}</td>
                                    @php
                                    // Mapping for document type values to readable names
                                    $documentTypes = [
                                    'Health_Insurance' => 'Health Insurance',
                                    'driving_license' => 'Driving License',
                                    'pan' => 'PAN',
                                    'benefit' => 'Benefit',
                                    'W4/1099' => 'W4/1099',
                                    ];
                                    $displayDocumentType = $documentTypes[$document->document_type] ?? $document->document_type ?? '';
                                    @endphp
                                    <td class="document-type">{{ $displayDocumentType }}</td>
                                    <td>
                                        @if($document->file_path ?? '')
                                        <a href="{{ url('public/' . $document->file_path) }}" target="_blank" class="document-view">View</a>
                                        @else
                                        No document
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary editDocument" data-id="{{ $document->id }}" data-name="{{ $document->document_name }}" data-type="{{ $document->document_type }}" data-path="{{ $document->file_path }}">Edit</button>

                                        <form id="deleteDocumentForm-{{ $document->id }}" method="POST" action="{{ route('documents.destroy', $document->id) }}" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-id="{{ $document->id }}" class="btn btn-sm btn-secondary deleteDocument">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- Form Section -->
                <div class="col-lg-3 col-md-4">
                    <form method="POST" id="documentForm" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="document_id" name="document_id">
                        <input type="hidden" name="user_id" value="{{ $commonUser->id }}">

                        <div class="form-group">
                            <label for="document_name" class="required-field">Document Name</label>
                            <input type="text" class="form-control" id="document_name" name="document_name" required>
                        </div>

                        <div class="form-group mt-2">
                            <label for="document_type" class="required-field">Document Type</label>
                            <select class="form-select" id="document_type" name="document_type" required>
                                <option value="">Select Type...</option>
                                <option value="Health_Insurance">Health Insurance</option>
                                <option value="driving_license">Driving License</option>
                                <option value="pan">PAN</option>
                                <option value="benefit">Benefit</option>

                                <option value="W4/1099">W4/1099</option>
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="upload_document">Upload Document</label>
                            <input type="file" class="form-control" id="upload_document" name="upload_document">
                        </div>

                        <div class="form-group mt-3 text-center">
                            <button type="submit" id="submitFormBtndocumentForm" class="btn btn-secondary">Save</button>
                        </div>
                    </form>
                </div>


            </div>

            <script>
                document.getElementById('submitFormBtndocumentForm').addEventListener('click', function(e) {
                    e.preventDefault();

                    const formData = new FormData(document.getElementById('documentForm'));
                    const documentId = document.getElementById('document_id').value;

                    // Use the update route if documentId is present, otherwise use the store route
                    const url = documentId ?
                        `{{ route('documents.update', '') }}/${documentId}` // Update route
                        :
                        `{{ route('documents.store') }}`; // Store route

                    fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert(data.message);

                                if (documentId) {
                                    // Update existing row
                                    const row = document.querySelector(`.documents-tabletable tbody tr[data-id="${documentId}"]`);
                                    row.querySelector('.document-name').innerText = data.document.document_name;
                                    //row.querySelector('.document-type').innerText = data.document.document_type;
                                    const documentTypeMap = {
                                        'Health_Insurance': 'Health Insurance',
                                        'driving_license': 'Driving License',
                                        'pan': 'PAN',
                                        'benefit': 'Benefit',
                                        'W4/1099': 'W4/1099',
                                    };

                                    row.querySelector('.document-type').innerText = documentTypeMap[data.document.document_type] || data.document.document_type;
                                    // Update the view link
                                    const viewLink = row.querySelector('.document-view');
                                    viewLink.href = `{{ url('/') }}/public/${data.document.file_path}`; // Use root URL + file path
                                } else {
                                    // Add new row
                                    const newRow = `
    <tr data-id="${data.document.id}">
        <td class="document-name">${data.document.document_name}</td>
        <td class="document-type">${data.document.document_type === 'Health_Insurance' ? 'Health Insurance' :
                                    data.document.document_type === 'driving_license' ? 'Driving License' :
                                    data.document.document_type === 'pan' ? 'PAN' :
                                    data.document.document_type === 'benefit' ? 'Benefit' :
                                    data.document.document_type === 'W4/1099' ? 'W4/1099' : 
                                    data.document.document_type}</td>
        <td>
            <a href="{{ url('/') }}/public/${data.document.file_path}" target="_blank" class="document-view">View</a>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-secondary editDocument" data-id="${data.document.id}" data-name="${data.document.document_name}" data-type="${data.document.document_type}" data-path="${data.document.file_path}">Edit</button>
            <button type="button" class="btn btn-sm btn-secondary deleteDocument" data-id="${data.document.id}">Delete</button>
        </td>
    </tr>`;
                                    document.querySelector('.documents-tabletable tbody').insertAdjacentHTML('beforeend', newRow);
                                }
                                // Reset the form
                                document.getElementById('documentForm').reset();
                                document.getElementById('submitFormBtndocumentForm').innerText = 'Submit'; // Reset button text
                                document.getElementById('document_id').value = ''; // Clear document ID for new entries
                            } else {
                                alert(data.message || 'An error occurred'); // Handle error messages
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

                // Handle delete button
                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('deleteDocument')) {
                        const documentId = event.target.getAttribute('data-id');
                        if (confirm("Are you sure you want to delete this document?")) {
                            fetch(`{{ route('documents.destroy', '') }}/${documentId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        alert(data.message);
                                        // Remove row from the table
                                        const row = document.querySelector(`.documents-tabletable tbody tr[data-id="${documentId}"]`);
                                        if (row) {
                                            row.remove();
                                        } else {
                                            console.error(`Row with documentId ${documentId} not found.`);
                                        }
                                    } else {
                                        alert(data.message || 'Failed to delete the document.'); // Handle error messages
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    }
                });

                // Handle edit button
                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('editDocument')) {
                        const button = event.target;
                        document.getElementById('document_id').value = button.getAttribute('data-id');
                        document.getElementById('document_name').value = button.getAttribute('data-name');
                        document.getElementById('document_type').value = button.getAttribute('data-type');
                        document.getElementById('submitFormBtndocumentForm').innerText = 'Update'; // Change button text for update
                    }
                });
            </script>
      