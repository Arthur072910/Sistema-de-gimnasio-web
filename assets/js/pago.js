document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btnContinuar");

    if (btn) {
        btn.addEventListener("click", function () {
            window.location.href = "plan.php";
        });
    }

});