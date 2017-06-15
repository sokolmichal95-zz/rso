<?php
        require_once('functions.php');
        $user=session_check();
?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js">
</script>
</head>
<body>
<?php show_menu($user); ?>
</body>
</html>