<?php
require_once 'config.php';

if(isset($_POST['district_id'])) {
    $district_id = $_POST['district_id'];
    
    $query = "SELECT * FROM municipalities WHERE district_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo '<option value="">Select a municipality</option>';
    while($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['municipality_name'].'</option>';
    }
}
?>