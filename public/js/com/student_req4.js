const edu = document.querySelector('#edu');
const pro = document.querySelector('#pro');
const work = document.querySelector('#work');
const ref = document.querySelector('#ref');
const req = document.querySelector('#req');
const e1 = document.querySelector('#e1');
const e2 = document.querySelector('#e2');
const e3 = document.querySelector('#e3');
const e4 = document.querySelector('#e4');
const p1 = document.querySelector('#p1');
const p2 = document.querySelector('#p2');
const p3 = document.querySelector('#p3');
const w1 = document.querySelector('#w1');
const w2 = document.querySelector('#w2');
const w3 = document.querySelector('#w3');
const r1 = document.querySelector('#r1');
const r2 = document.querySelector('#r2');
const r3 = document.querySelector('#r3');
const reqSection = document.querySelector('#req-section');
const other = document.querySelector('#other');



other.style.display='none'

reqSection.style.display = 'none'
req.addEventListener('click', function () {
    id = parseInt(course_id);
    req.style.visibility = 'hidden'
    sid = parseInt(student_id)
    reqSection.style.display = 'block'


    //
    fetch('/slo/getCourseRequirements/' + id)
        .then(res => res.json())
        .then(data => {
            console.log(' requirements' + data)
            //  let groupStart = data;
            console.log()


            console.log(sid)
            if (data[0]['edu_req']) {
                console.log(data[0]['edu_req'].length)
                edu.value = data[0]['edu_req'].length;
                count = data[0]['edu_req'].length
                console.log(count)
                if (count === 3) {
                    e1.style.display = "block"
                    e2.style.display = "block"
                    e3.style.display = "block"
                } else if (count === 2) {
                    e1.style.display = "block"
                    e2.style.display = "block"
                    e3.style.display = "none"
                } else if (count === 1) {
                    e1.style.display = "block"
                    e2.style.display = "none"
                    e3.style.display = "none"
                } else if (count === 0) {
                    e1.style.display = "none"
                    e2.style.display = "none"
                    e3.style.display = "none"
                }


            } else {
                e1.style.display = 'none'
                e2.style.display = "none"
                e3.style.display = "none"
            }

            if (data[0]['pro_req']) {
                console.log(data[0]['pro_req'].length)
                pro.value = data[0]['pro_req'].length
                count = data[0]['pro_req'].length
                console.log(count)
                if (count === 3) {
                    p1.style.display = "block"
                    p2.style.display = "block"
                    p3.style.display = "block"
                } else if (count === 2) {
                    p1.style.display = "block"
                    p2.style.display = "block"
                    p3.style.display = "none"
                } else if (count === 1) {
                    p1.style.display = "block"
                    p2.style.display = "none"
                    p3.style.display = "none"
                } else if (count === 0) {
                    p1.style.display = "none"
                    p2.style.display = "none"
                    p3.style.display = "none"
                }
            } else {
                p1.style.display = 'none'
                p2.style.display = "none"
                p3.style.display = "none"
            }


            if (data[0]['work_req']) {
                console.log(data[0]['work_req'].length)
                work.value = data[0]['work_req'].length
                count = data[0]['work_req'].length
                console.log(count)
                if (count === 3) {
                    w1.style.display = "block"
                    w2.style.display = "block"
                    w3.style.display = "block"
                } else if (count === 2) {
                    w1.style.display = "block"
                    w2.style.display = "block"
                    w3.style.display = "none"
                } else if (count === 1) {
                    w1.style.display = "block"
                    w2.style.display = "none"
                    w3.style.display = "none"
                } else if (count === 0) {
                    w1.style.display = "none"
                    w2.style.display = "none"
                    w3.style.display = "none"
                }

            } else {
                w1.style.display = 'none'
                w2.style.display = 'none'
                w3.style.display = 'none'
            }


            if (data[0]['ref_req']) {
                console.log(data[0]['ref_req'].length)
                ref.value =data[0]['ref_req'].length
                count = data[0]['ref_req'].length
                console.log(count)
                if (count === 3) {
                    r1.style.display = "block"
                    r2.style.display = "block"
                    r3.style.display = "block"
                } else if (count === 2) {
                    r1.style.display = "block"
                    r2.style.display = "block"
                    r3.style.display = "none"
                } else if (count === 1) {
                    r1.style.display = "block"
                    r2.style.display = "none"
                    r3.style.display = "none"
                } else if (count === 0) {
                    r1.style.display = "none"
                    r2.style.display = "none"
                    r3.style.display = "none"
                }
            } else {
                r1.style.display = 'none'
                r2.style.display = 'none'
                r3.style.display = 'none'
            }

        })
})


$('#add_form').on('submit', function (e) {
    e.preventDefault();


    $.ajax({
        type: "POST",
        url: "/slo/addQualification",
        data: $('#add_form').serialize(),

        success: function (response) {
            console.log(response + 'iii')

            alert('data saved')
        },
        error: function (error) {
            console.log(error)
            alert('data not saved')
        }
    })


})
