if ($conn->query($sql_insert) === TRUE) {
    $new_id = $conn->insert_id; // Ambil ID booking yang baru dijana
    echo "<script>
            alert('Booking Successfully Created!');
            window.location.href='booking_details.php?id=$new_id';
          </script>";
}