$(document).ready(function() {
    function renderTeacher() {
        let url = 'http://localhost/PHP-MVC-CRUD/backEnd/apiteachercontroller/api'
        let html;
        var formCreateTeacher = $('.from_create_teacher');
        var formUpdateTeacher = $('.form_update_teacher');
        $ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let elm = '';
                if (data) {
                    data.map((teacher) => {
                        elm = `
                              <tr>
                                <th scope="row">${student.id}</th>
                                <td>${student.code}</td>
                                <td>${student.name}</td>
                                <td>${student.birthday}</td>
                                <td>${student.gender == '0' ? 'male' : 'female'}</td>
                                <td>${student.address}</td>
                                <td>${student.number_phone}</td>
                                <td>
                                    <button class="btn btn-primary edit_teacher" data-id="${student.id}">Update
                                    <button class="btn btn-danger delete_teacher" data-id="${student.id}">Delete</button>
                                </td>                                                                            
                            </tr>
                        `
                        html += elm;
                    });
                }
                $('#tab-teacher .content tbody').html(html);
            },
            error: function(e) {
                $('#tab-teacher.content tbody').text(`${e.responseJSON.message}`);
            },
            complete: function() {
                $('.form_update_teacher').hide();
            }
        });
    }
    renderTeacher();

    $('.cancel').click(function(e) {
        formCreateTeacher.hide();
        formUpdateTeacher.hide();
        e.preventDefault();
    })

    btnCreateTeacher.click(function(e) {
        formUpdateTeacher.hide();
        formCreateTeacher.show();
        e.preventDefault();
    });

    let btnCreateTeacher = $('#create-teacher');
    let urlCreateTeacher = 'http://localhost/PHP-MVC-CRUD/BackEnd/apiteachercontroller/api';

    formCreateTeacher.submit(function(e) {
        let name = $('#student_name').val();
        let code = $('#student_code').val();
        let birthday = $('#student_birthday').val();
        let gender = $('#student_gender').val();
        let address = $('#student_address').val()
        let number_phone = $('#student_number_phone_update').val();

        if (!code && code == '') {
            $('.error_code').text('field required!');
        } else {
            $('.error_code').text('');
        }

        if (!name && name == '') {
            $('.error_name').text('field required!');
        } else {
            $('.error_name').text('');
        }

        if (!birthday && birthday == '') {
            $('.error_birthday').text('field required!');
        } else {
            $('.error_birthday').text('');
        }

        if (!gender && gender == '') {
            $('.error_gender').text('field required!');
        } else {
            $('.error_gender').text('');
        }

        if (!address && address == '') {
            $('.error_address').text('field required!');
        } else {
            $('.error_address').text('');
        }

        if (!number_phone && number_phone == '') {
            $('.error_number_phone').text('field required!');
        } else {
            $('.error_number_phone').text('');
        }

        let data = '';
        if (id && name && code && birthday && gender && address && number_phone) {
            data = {
                id,
                name,
                code,
                birthday,
                gender,
                address,
                number_phone
            }
        }
        $.ajax({
            url: urlCreateTeacher,
            type: 'POST',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(data),
            beforeSend: function() {
                return data ? true : false;
            },
            success: function(result) {
                $('#tab-teacher .notion').show();
                $('#tab-teacher .notion').html(`
                    <p class="alert alert-success">${result.message}</p>
                `);
                $('#tab-students .notion').hide(2000);
                renderTeacher();
            },
            error: function(e) {
                console.log(e);
            },
            complete: function() {
                formCreateTeacher[0].reset();
                formCreateTeacher.hide();
            }
        })
        e.preventDefault();
    })

    $(document).on('click', '.delete_teacher', function(e) {
        let result = confirm('Are you sure?');
        if (result) {
            let id = $(this).attr('data-id');
            console.log(id)
            let data = (id) ? { id } : '';
            let urlDeleteStudent = `http://localhost/PHP-MVC-CRUD/BackEnd/apiteacher/delete/${id}`;
            $.ajax({
                url: urlDeleteStudent,
                type: "DELETE",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(data),
                beforeSend: function() {
                    return data ? true : false;
                },
                success: function(result) {
                    $('#tab-teacher .notion').show();
                    $('#tab-teacher .notion').html(`
                        <p class="alert alert-success">${result.message}</p>
                    `);
                    $('#tab-teacher .notion').hide(2000);
                    renderStudent();
                },
                error: function(msg) {
                    $('.message').text('404');
                }
            })
        }

        e.preventDefault();
    })

    $(document).on('click', '.edit_student', function(e) {
        $('.message').text('');
        // formUpdate.show();
        let id = $(this).attr('data-id');
        let data = (id) ? { id } : '';
        let urlSingleTeacher = `http://localhost/PHP-MVC-CRUD/BackEnd/apiteacher/single/${id}`;
        $.ajax({
            url: urlSingleTeacher,
            type: 'GET',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(data),
            beforeSend: function() {
                formCreateTeacher.hide();
            },
            success: function(result) {
                $('#student_code_update').attr('value', result.code);
                $('#student_name_update').attr('value', result.name);
                $('#student_birthday_update').attr('value', result.birthday);
                $('#student_gender_update').attr('value', result.gender);
                $('#student_address_update').attr('value', result.address);
                $('#student_number_phone_update').attr('value', result.address);
                $('.btn-update-teacher').attr('data-id', result.id);
                formUpdateTeacher.show();
            },
            error: function(msg) {
                $('.message').text('404');
            }
        })
    });

    $('.btn-update-teacher').on('click', function(e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        let name = $('#teacher_name_update').val();
        let code = $('#teacher_code_update').val();
        let birthday = $('#teacher_birthday_update').val();
        let gender = $('#teacher_gender_update').val();
        let address = $('#teacher_address_update').val();
        let number_phone = $('#teacher_number_phone_update').val();

        if (!code && code == '') {
            $('.error_code').text('field required!');
        } else {
            $('.error_code').text('');
        }

        if (!name && name == '') {
            $('.error_name').text('field required!');
        } else {
            $('.error_name').text('');
        }

        if (!birthday && birthday == '') {
            $('.error_birthday').text('field required!');
        } else {
            $('.error_birthday').text('');
        }

        if (!gender && gender == '') {
            $('.error_gender').text('field required!');
        } else {
            $('.error_gender').text('');
        }

        if (!address && address == '') {
            $('.error_address').text('field required!');
        } else {
            $('.error_address').text('');
        }

        if (!number_phone && number_phone == '') {
            $('.error_number_phone').text('field required!');
        } else {
            $('.error_number_phone').text('');
        }

        let data = '';
        if (id && name && code && birthday && gender && address && number_phone) {
            data = {
                id,
                name,
                code,
                birthday,
                gender,
                address,
                number_phone
            }
        }
        console.log(data);
        let urlUpdateTeacher = `http://localhost/PHP-MVC-CRUD/backend/apiteacher/update/${id}`;

        $.ajax({
            url: urlUpdateTeacher,
            type: 'PUT',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(data),
            beforeSend: function() {
                return data ? true : false;
            },
            success: function(result) {
                $('#tab-teacher .notion').show();
                $('#tab-teacher .notion').html(`
                    <p class="alert alert-success">${result.message}</p>
                `);
                $('#tab-teacher .notion').hide(2000);
                renderTeacher();
            },
            error: function(e) {
                console.log(e);
            },
            complete: function() {
                formUpdateTeacher.hide();
                formUpdateTeacher[0].reset();
            }
        })

    })
});