const con = document.querySelector('#con');
const emg = document.querySelector('#emg');
const spe = document.querySelector('#spe');
const isNurse = document.querySelector('#isNurse')
const nurse = document.querySelector('#nurse')



$(document).ready(function () {
    id = parseInt(course_id);


    fetch('/slo/isNursing/' + id)
        .then(res => res.text())
        .then(data => {
            console.log(data)
            let str = data
            if (str.match(/^.*Nursing.*$/)) {
                console.log("nursing")
                // $('#nurseHtml').append(html)
                isNurse.value = 'yes'
                nurse.style.display = 'block'
            } else {
                console.log('no nursing')
                isNurse.value = ''
                nurse.style.display = 'none'
            }
        })
        .catch(err => console.log(err));

    showNurse()
})


con.addEventListener('click', function () {
    con.style.visibility = 'hidden'
    $('#newHtmlContact').append(htmlCon)
})

emg.addEventListener('click', function () {
    emg.style.visibility = 'hidden'
    $('#newHtmlEmg').append(htmlEmg)
})

spe.addEventListener('click', function () {
    spe.style.visibility = 'hidden'
    $('#newHtmlSpe').append(htmlSpe)
})

function showNurse() {
    if (isNurse.value == 'yes') {
        nurse.style.display = 'block'
        console.log('block')
    } else {
        nurse.style.display = 'none'
        console.log('none')
    }
}

let htmlSpe = ''
htmlSpe += '<div class="row">'
htmlSpe += '<div class="col-md-10"><div class="form-group"><label for="special">Special Requirements/Details</label>'
htmlSpe += '<textarea class="form-control myDropdown" name="special_req" id="" placeholder="Special Requirements"></textarea>'
htmlSpe += '</div></div></div>'
htmlSpe += '<div class="row">'
htmlSpe += '<div class="col-md-10"><div class="form-group"><label for="special" class="mr-4">Preferred Hand</label>'
htmlSpe += '<label class="radio-inline"><input type="radio" name="preferred_hand" value="right" checked>Right</label>'
htmlSpe += '<label class="radio-inline"><input type="radio" name="preferred_hand" value="left">Left</label>'
htmlSpe += '</div></div></div>'
htmlSpe += '<div class="row">'
htmlSpe += '<div class="col-md-10"><div class="form-group">'
htmlSpe += '<input class="form-control myDropdown" name="slipper_size" id="" placeholder="Slipper Size">'
htmlSpe += '</div></div></div>'
htmlSpe += '<div class="row">'
htmlSpe += '<div class="col-md-10"><div class="form-group">'
htmlSpe += '<input class="form-control myDropdown" name="locker_key" id="" placeholder="Locker Key">'
htmlSpe += '</div></div></div>'
htmlSpe += '<div class="row">'
htmlSpe += '<div class="col-md-10"><div class="form-group">'
htmlSpe += '<input class="form-control myDropdown" name="host" id="" placeholder="Hostel">'
htmlSpe += '</div></div></div>'


let htmlEmg = ''
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group"><label for="emergency">Contact person in case of emergency</label>'
htmlEmg += '<input type="text" class="form-control myDropdown" name="emg_name" id="" placeholder="Name">'
htmlEmg += '</div></div></div>'
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group">'
htmlEmg += '<input class="form-control myDropdown" name="relationship" id="" placeholder="Relationship">'
htmlEmg += '</div></div></div>'
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group">'
htmlEmg += '<textarea class="form-control myDropdown" name="address" id="" placeholder="Address"></textarea>'
htmlEmg += '</div></div></div>'
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group"><label for="Telephone">Telephone</label>'
htmlEmg += '<input type="text" class="form-control myDropdown" name="emg_tel_residence" id="" placeholder="Residence">'
htmlEmg += '</div></div></div>'
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group">'
htmlEmg += '<input class="form-control myDropdown" name="emg_tel_work" id="" placeholder="Work">'
htmlEmg += '</div></div></div>'
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group">'
htmlEmg += '<input class="form-control myDropdown" name="emg_tel_mobile1" id="" placeholder="Mobile 1">'
htmlEmg += '</div></div></div>'
htmlEmg += '<div class="row">'
htmlEmg += '<div class="col-md-10"><div class="form-group">'
htmlEmg += '<input class="form-control myDropdown" name="emg_tel_mobile2" id="" placeholder="Mobile 2">'
htmlEmg += '</div></div></div>'


let htmlCon = ''
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group"><label for="qualifications">Permanent Address:</label>'
htmlCon += '<input type="text" class="form-control myDropdown" name="per_address" id="" placeholder="Address">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="per_city" id="" placeholder="City">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="per_country" id="" placeholder="Country">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="per_postal_code" id="" placeholder="Postal Code">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group"><label for="qualifications">Telephone No:</label>'
htmlCon += '<input type="text" class="form-control myDropdown" name="tel_residence" id="" placeholder="Residence">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="tel_work" id="" placeholder="Work">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="tel_mobile2" id="" placeholder="Mobile 2">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group"><label for="qualifications">Email:</label>'
htmlCon += '<input type="text" class="form-control myDropdown" name="email1" id="" placeholder="E-mail 1">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="email2" id="" placeholder="E-mail 2">'
htmlCon += '</div></div></div>'
htmlCon += '<div class="row">'
htmlCon += '<div class="col-md-10"><div class="form-group">'
htmlCon += '<input type="text" class="form-control myDropdown" name="kiu_mail" id="" placeholder="kiu_mail">'
htmlCon += '</div></div></div>'


let html = ''
html += '<div class="row">'
html += '<div class="col-md-10"><div class="form-group"><label for="qualifications">Details:</label>'
html += '<input type="text" class="form-control myDropdown" name="" id="" placeholder="Hospital">'
html += '<select class="form-control " name="hospital_id" id="hospital_id" required>'
html += '<option>Select Hospital</option>'
html += '@foreach($hospitals as $h)'
html += '<option value="{{$h->gen_hospital_id}}">{{$h->hospital_name}}</option>'
html += '@endforeach'
html += '</select>'
html += '</div></div></div>'
html += '<div class="row">'
html += '<div class="col-md-10"><div class="form-group">'
html += '<input type="text" class="form-control myDropdown" name="" id="" placeholder="Ward">'
html += '</div></div></div>'
html += '<div class="row">'
html += '<div class="col-md-10"><div class="form-group">'
html += '<input type="text" class="form-control myDropdown" name="" id="" placeholder="NTS">'
html += '</div></div></div>'
