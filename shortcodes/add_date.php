function display_current_date() {
    return '<span id="user-date">Loading date...</span>';
}
add_shortcode('current_date', 'display_current_date');

function add_timezone_script() {
    ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var localDate = new Date().toLocaleDateString(undefined, options);
            var dateElement = document.getElementById("user-date");
            if (dateElement) {
                dateElement.textContent = localDate;
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_timezone_script');