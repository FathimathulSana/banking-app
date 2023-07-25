
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeButton = document.querySelector('.close-button');
        const alertBox = document.getElementById('alert-box');

        closeButton.addEventListener('click', function() {
            alertBox.style.display = 'none';
        });
    });
</script>
