<script type="text/javascript">
    var study_program_dt;
    var url_study_program
    $(document).ready(function () {
        study_program_dt = $("#study-program-datatables").DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            bLengthChange: false,
            ajax: {
                headers: {Authorization: Cookies.get('token-auth')},
                url: "{{ env('API_URL') }}/study-program/"+ Cookies.get('token') +"/datatables",
                error: function () {
                    window.location.replace("{{ route('login') }}")
                }
            },
            columns: [
                {data: 'study_program', name: 'study_program'},
                {data: 'cost', name: 'cost'},
                {data: 'actions', name: 'actions', class: 'text-center', orderable: false}
                ]
        });
    });
    function reloadStudyProgram() {
        study_program_dt.ajax.reload()
    }
    function create_study_program() {
        $('[name=_method]').val("POST")
        url_study_program = "{{ env('API_URL') }}/study-program/" + Cookies.get('token')
        $("#study-program-modal-label").html("Tambah Paket Program Belajar")
        $("#study-program-modal-btn").attr("class", "btn btn-success")
        $("#study-program-modal-btn").html("SAVE")
        $('.study-program-modal form')[0].reset()
        $('.study-program-modal').modal("show")
    }
    function edit_study_program(id) {
        $('[name=_method]').val("PATCH")
        url_study_program = "{{ env('API_URL') }}/study-program/" + id
        $("#study-program-modal-label").html("Update Paket Program Belajar")
        $("#study-program-modal-btn").attr("class", "btn btn-info")
        $("#study-program-modal-btn").html("UPDATE")

        $.ajax({
            type: "GET",
            url: "{{ env('API_URL') }}/study-program/" + id + '/edit',
            headers: {Authorization: Cookies.get('token-auth')},
            dataType: "JSON",
            success: function (data) {
                $('[name=study_program]').val(data.study_program)
                $('[name=cost]').val(data.cost)
                $('.study-program-modal').modal("show")
            },
            error: function () {
                alert("Something Wrong !")
            }
        });
    }
    $(function () {
        $('.study-program-modal form').validator().on('submit', function (e) {
            if (!e.isDefaultPrevented()){
                $.ajax({
                    type: "POST",
                    url: url_study_program,
                    headers: {Authorization: Cookies.get('token-auth')},
                    data: $('.study-program-modal form').serialize(),
                    success: function (data) {
                        $('.study-program-modal').modal("hide")
                        $('.study-program-modal form')[0].reset()
                        study_program_dt.ajax.reload()
                    },
                    error: function () {
                        alert("Something Wrong !")
                    }
                });
                return false
            }
        });
    });
    function destroy_study_program(id) {
        if (confirm("Yakin Hapus ?")){
            $.ajax({
                type: "POST",
                url : "{{ env('API_URL') }}/study-program/" + id,
                headers: {Authorization: Cookies.get('token-auth')},
                data: {_method: "DELETE", _token: "{{ csrf_token() }}"},
                success: function (data) {
                    study_program_dt.ajax.reload();
                },
                error: function () {
                    alert("Something Wrong !")
                }
            })
        }
    }
</script>