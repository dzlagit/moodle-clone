<?php
    $sql = "SELECT * FROM resources";#
    $res = mysqli_query($conn, $sql);

    echo "<table border='1'>
        <tr>
            <th>ID</th>"

while ($row = mysqli_fetch_assoc($res)) {
        extract($row);
        echo "<tr>
                <td>$id</td>;
                <td>$username</td>;
                <td>$name</td>;
                <td><a href='resources'/$$fileName</td>;
                <td>$dateUploaded</td>";
                </tr>
    }
?>