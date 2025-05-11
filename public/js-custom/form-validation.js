// $.ajaxSetup({
//     headers: {
//         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//     },
// });

// function setupFormValidation(formSelector, rules, onSuccessCallback) {
//     $(formSelector).validate({
//         rules: rules,
//         submitHandler: function (form) {
//             $.ajax({
//                 url: form.action,
//                 type: form.method,
//                 data: $(form).serialize(),
//                 success: function (response) {
//                     if (response.status) {
//                         Swal.fire({
//                             icon: "success",
//                             title: "Berhasil",
//                             text: response.message,
//                         }).then(function () {
//                             if (typeof onSuccessCallback === "function") {
//                                 onSuccessCallback(response);
//                             } else if (response.redirect) {
//                                 window.location = response.redirect;
//                             }
//                         });
//                     } else {
//                         $(".error-text").text("");
//                         $.each(response.msgField, function (prefix, val) {
//                             $("#error-" + prefix).text(val[0]);
//                         });
//                         Swal.fire({
//                             icon: "error",
//                             title: "Terjadi Kesalahan",
//                             text: response.message,
//                         });
//                     }
//                 },
//             });
//             return false;
//         },
//         errorElement: "span",
//         errorPlacement: function (error, element) {
//             error.addClass("invalid-feedback");
//             element.closest(".input-group").append(error);
//         },
//         highlight: function (element) {
//             $(element).addClass("is-invalid");
//         },
//         unhighlight: function (element) {
//             $(element).removeClass("is-invalid");
//         },
//     });
// }
