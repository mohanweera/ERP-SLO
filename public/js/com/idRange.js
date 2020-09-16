let start_id = document.getElementById('start')

$("#course_id").change(function () {
    var id = $(this).val();

    if (id) {
        $.ajax({
            type: 'GET',
            url: "/slo/idRange/start/" + id,

            success: function (response) {
                if (response.end == 0) {

                    $('#start').val('')

                } else {
                    console.log(response)
                    console.log(response.end)
                    let value = (response.end[0].end)
                    value++;

                    $('#start').val(value)

                }
            }
        })
    }

});

/*
$("#course_id").change(function () {
    var id = $(this).val();

    if (id) {
        $.ajax({
            type: 'GET',
            url: "/slo/idRange/start/" + id,
        })
            .then((success) =>
                console.log('ok')
            )
            .failure((failureResponse) =>
                console.log('no'))
    }


});
*/
