<div class="container mt-4">
    <h2>Ganti Password</h2>
    <p>Gunakan form di bawah ini untuk mengganti password akun Anda.</p>
    <form id="changePasswordForm">
                <div class="mb-3">
            <label for="currentPassword" class="form-label">Password Lama</label>
            <div class="input-group">
                <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                <div class="input-group-append">
                    <span class="input-group-text toggle-password-span" style="cursor: pointer;">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="newPassword" class="form-label">Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control" id="newPassword" name="new_password" required>
                <div class="input-group-append">
                    <span class="input-group-text toggle-password-span" style="cursor: pointer;">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <div class="input-group-append">
                    <span class="input-group-text toggle-password-span" style="cursor: pointer;">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>
            <small id="passwordMatchMessage" class="form-text"></small>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

</div>
<script>
    $(document).ready(function() {
        $("#confirmPassword, #newPassword").on("keyup", function() {
            let newPassword = $("#newPassword").val();
            let confirmPassword = $("#confirmPassword").val();
            let message = $("#passwordMatchMessage");

            if (newPassword.length > 0 && newPassword.length < 6) {
                message.text("Password minimal 6 karakter.").css("color", "red");
            } else if (newPassword !== confirmPassword) {
                message.text("Password tidak cocok!").css("color", "red");
            } else {
                message.text("Password cocok.").css("color", "green");
            }
        });

        $('.toggle-password-span').click(function() {
            const icon = $(this).find('i');
            const input = $(this).closest('.input-group').find('input');
            
            icon.toggleClass('bi-eye-slash bi-eye');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Form Ganti Password
        document.getElementById("changePasswordForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch("<?= base_url('user/update-password') ?>", {
                    method: "POST",
                    body: formData
                });

                if (response.ok) {
                    const result = await response.json();
                    Swal.fire("Berhasil!", result.message || "Password berhasil diubah.", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    const result = await response.json();
                    Swal.fire("Gagal!", result.message || "Terjadi kesalahan.", "error");
                }
            } catch (error) {
                Swal.fire("Oops...", `Terjadi kesalahan: ${error.message}`, "error");
            }
        });


    });
</script>