<x-app>
@section('content')

    <!-- Emails Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Emails</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="emails">
                    <thead>
                    <tr>
                        <th>From</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>User ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>From</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>User ID</th>
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

    <!-- Datatables -->
    <script>
        let last
        $(document).ready(function () {
            $('#emails').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('emails.load') }}",
                "columns": [
                    {data: 'from'},
                    {data: 'subject'},
                    {data: 'date'},
                    {data: 'user_id'},
                ],
                drawCallback: function(){
                    $('.paginate_button.last:not(.disabled)', this.api().table().container())
                        .on('click', function(){
                            page = 'last';
                            console.log(page);
                        });
                }
            });
        });

    </script>
    @endsection
</x-app>

