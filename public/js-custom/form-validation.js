/**
 * Inisialisasi validasi form secara reusable
 * @param {string} formId - ID dari form (misalnya '#form-login')
 * @param {object} rules - Aturan validasi jQuery Validate
 * @param {object} messages - Pesan error untuk setiap field
 * @param {function} successCallback - Fungsi yang dijalankan saat submit berhasil
 */
function customFormValidation(formId, rules, messages, successCallback) {
    $(document).ready(function () {
        // Setup CSRF token untuk semua AJAX request
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        // Validasi form
        $(formId).validate({
            rules: rules,
            messages: messages,
            submitHandler: function (form) {
              var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    // data: $(form).serialize(),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (typeof successCallback === "function") {
                            successCallback(response, form);
                        }
                    },
                    error: function (xhr) {
                        Swal.fire(
                            "Error",
                            "Terjadi kesalahan pada server.",
                            "error"
                        );
                    },
                });
                return false;
            },
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                element.closest(".custom-validation").append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
}
