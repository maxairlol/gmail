<x-app>
@section('content')

    <!-- Emails Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Emails</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="label"><strong>Label :</strong></label>
                    <select class="form-control w-auto" id='label'>
                        <option value="">Select Label</option>
                        @foreach($labels as $label)
                            <option value="{{ $label }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered w-100" id="emails">
                        <thead>
                        <tr>
                            <th>From</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Labels</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>From</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Labels</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Email Content -->
        <x-modal-email-content></x-modal-email-content>
@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js">
    </script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js">
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/26.0.0/classic/ckeditor.js"></script>

    <!-- Page level custom -->
    <script src="{{ asset('js/emails.js') }}"></script>
@endsection
</x-app>

