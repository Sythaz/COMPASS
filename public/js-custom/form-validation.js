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
                const validator = $(formId).validate(); // Mendapatkan objek validator
                var formData = new FormData(form); // Buat objek FormData

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (typeof successCallback === "function") {
                            successCallback(response, form);
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = "Terjadi kesalahan pada server.";

                        // 422 - Validasi gagal
                        // 422 - Validasi gagal
                        if (xhr.status === 422 && xhr.responseJSON) {
                            const response = xhr.responseJSON;

                            if (response.errors) {
                                const errors = {};

                                console.log(
                                    "Field dari Laravel:",
                                    Object.keys(response.errors)
                                );
                                console.log(
                                    "Field dari form:",
                                    $(formId)
                                        .find("[name]")
                                        .map((i, e) => e.name)
                                        .get()
                                );

                                $.each(
                                    response.errors,
                                    function (field, messages) {
                                        errors[field] = messages[0];
                                    }
                                );

                                validator.showErrors(errors);
                            }

                            Swal.fire({
                                icon: "error",
                                title: "Validasi Gagal",
                                text:
                                    response.message ||
                                    "Beberapa input tidak valid.",
                            });

                            return;
                        }

                        // 403 - Akses ditolak
                        if (xhr.status === 403) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    let parsed = JSON.parse(xhr.responseText);
                                    errorMessage =
                                        parsed.message || errorMessage;
                                } catch (e) {}
                            }

                            Swal.fire({
                                icon: "error",
                                title: "Akses Ditolak",
                                text: errorMessage,
                            });

                            return;
                        }

                        // 404 - URL tidak ditemukan
                        if (xhr.status === 404) {
                            Swal.fire({
                                icon: "error",
                                title: "Tidak Ditemukan",
                                text: "Halaman atau endpoint tidak ditemukan (404).",
                            });
                            return;
                        }

                        // 500 - Server Error
                        if (xhr.status === 500) {
                            Swal.fire({
                                icon: "error",
                                title: "Server Error",
                                text: "Terjadi kesalahan internal pada server.",
                            });
                            return;
                        }

                        // Error lainya
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: errorMessage,
                        });
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
