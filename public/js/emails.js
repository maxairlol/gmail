$(document).ready(() => {

    /* Emails Datatable*/
    const table = $('#emails').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        lengthChange: false,
        ajax: {
            url: '/emails/load',
            data: function (data) {
                data.label = $('#label').val()
            }
        },
        columns: [
            {data: 'from'},
            {data: 'subject'},
            {data: 'date'},
            {data: 'labels', name: 'labels.name'},
            {data: 'action'},
        ],
    });

    $('#label').change(() => {
        table.draw();
    });

    /* CKEDITOR*/
    ClassicEditor
        .create(document.querySelector('#email-modal-content'))
        .catch(error => {
            console.error(error);
        });

    var yourData = '<p>This is a new paragraph.</p>';

    //insert your data in the editor
    $("#email-modal-content").val(yourData);
});
