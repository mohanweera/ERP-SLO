$("#course_id").change(function () {
    var id = $(this).val();

    if (id) {
        $.ajax({
            type: 'GET',
            url: "/slo/courseReq/search/" + id,
            success: function (response) {
                console.log(response.course_name)
                let value = (response.course_name)
                $('.dataTables_filter input').val(value)

                // $('#start').val(value)

            }
        })
    }
});










