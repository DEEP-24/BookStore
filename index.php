<?php
// Start output buffering
ob_start();

$pageTitle = "Home Page";
$pageDescription = "This is the home page";
?>

<!-- Page content -->
<main>
    <h1>Welcome</h1>
    <h2>Team members</h2>
    <ol>
        <li>
            <p>Member 1</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
        </li>
        <li>
            <p>Member 2</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
        </li>
        <li>
            <p>Member 3</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
        </li>
        <li>
            <p>Member 4</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
        </li>
    </ol>
</main>

<?php
// Get the captured content and end output buffering
$content = ob_get_clean();

// Include the template file, which includes the header, content, and footer
include 'templates/template.php';
?>