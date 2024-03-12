<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu Restoran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-4">

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Daftar Menu Restoran</h1>

    <!-- Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center">
        <div class="bg-white rounded-lg p-8 max-w-md">
            <h2 class="text-lg font-semibold mb-4">Edit Menu</h2>
            <form id="editForm" method="post" action="">
                <input type="hidden" id="edit_id" name="edit_id" value="">
                <div class="flex mb-2">
                    <label for="edit_nama" class="mr-2 flex-shrink-0">Nama:</label>
                    <input type="text" id="edit_nama" name="edit_nama" class="border rounded-md px-2 py-1">
                </div>
                <div class="flex mb-2">
                    <label for="edit_harga" class="mr-2 flex-shrink-0">Harga:</label>
                    <input type="text" id="edit_harga" name="edit_harga" class="border rounded-md px-2 py-1">
                </div>
                <div class="flex mb-4">
                    <label for="edit_status" class="mr-2 flex-shrink-0">Status:</label>
                    <input type="text" id="edit_status" name="edit_status" class="border rounded-md px-2 py-1">
                </div>
                <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Simpan</button>
                <button id="cancelEdit" type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 ml-2">Batal</button>
            </form>
        </div>
    </div>

    <?php
    // Membuat koneksi
    $servername = "localhost";
    $username = "root"; // Ganti dengan username Anda
    $password = ""; // Ganti dengan password Anda
    $database = "menu_restaurant";

    $conn = new mysqli($servername, $username, $password, $database);

    // Memeriksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Create
    if(isset($_POST['tambah'])){
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $status = $_POST['status'];

        $sql = "INSERT INTO menu (nama, harga, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nama, $harga, $status);
        if ($stmt->execute()) {
            echo '<div class="bg-green-200 text-green-800 px-4 py-2 rounded-md mb-4">Data berhasil ditambahkan</div>';
            // Calling JavaScript function to update table
            echo '<script>updateTable();</script>';
        } else {
            echo '<div class="bg-red-200 text-red-800 px-4 py-2 rounded-md mb-4">Error: ' . $stmt->error . '</div>';
        }
    }

    // Read
    $sql = "SELECT * FROM menu";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table id="menuTable" class="w-full bg-white shadow-md rounded-md mb-8">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="px-4 py-2">ID Menu</th>';
        echo '<th class="px-4 py-2">Nama</th>';
        echo '<th class="px-4 py-2">Harga</th>';
        echo '<th class="px-4 py-2">Status</th>';
        echo '<th class="px-4 py-2">Aksi</th>'; // Tambah kolom untuk aksi edit
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        // Output data setiap baris
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td class="border px-4 py-2">' . $row["id_menu"]. '</td>';
            echo '<td class="border px-4 py-2">' . $row["nama"]. '</td>';
            echo '<td class="border px-4 py-2">' . $row["harga"]. '</td>';
            echo '<td class="border px-4 py-2">' . $row["status"]. '</td>';
            // Tambah formulir edit
            echo '<td class="border px-4 py-2">';
            echo '<button type="button" class="editButton bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600" data-id="' . $row["id_menu"] . '">Edit</button>';
            // Tambah formulir hapus
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="id" value="' . $row["id_menu"] . '">';
            echo '<button type="submit" name="hapus" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mt-2">Hapus</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md mb-4">0 hasil</div>';
    }

    // Edit
    if(isset($_POST['edit'])){
        $edit_id = $_POST['edit_id'];
        // Redirect to edit page or show edit form here
        // For simplicity, I'm just echoing the edit_id
        echo '<div class="bg-blue-200 text-blue-800 px-4 py-2 rounded-md mb-4">Proses pengeditan untuk ID: ' . $edit_id . '</div>';
    }

    // Update (Edit)
    if(isset($_POST['update'])){
        $edit_id = $_POST['edit_id'];
        $edit_nama = $_POST['edit_nama'];
        $edit_harga = $_POST['edit_harga'];
        $edit_status = $_POST['edit_status'];

        $sql = "UPDATE menu SET nama=?, harga=?, status=? WHERE id_menu=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $edit_nama, $edit_harga, $edit_status, $edit_id);
        if ($stmt->execute()) {
            echo '<div class="bg-green-200 text-green-800 px-4 py-2 rounded-md mb-4">Data berhasil diperbarui</div>';
            // Calling JavaScript function to update table
            echo '<script>updateTable();</script>';
        } else {
            echo '<div class="bg-red-200 text-red-800 px-4 py-2 rounded-md mb-4">Error: ' . $stmt->error . '</div>';
        }
    }

    // Delete
    if(isset($_POST['hapus'])){
        $id = $_POST['id'];
        $sql = "DELETE FROM menu WHERE id_menu=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo '<div class="bg-green-200 text-green-800 px-4 py-2 rounded-md mb-4">Data berhasil dihapus</div>';
            // Calling JavaScript function to update table
            echo '<script>updateTable();</script>';
        } else {
            echo '<div class="bg-red-200 text-red-800 px-4 py-2 rounded-md mb-4">Error: ' . $stmt->error . '</div>';
        }
    }

    $conn->close();
    ?>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-lg font-semibold mb-2">Tambah Menu Baru</h2>
            <form method="post" action="">
                <div class="flex mb-2">
                    <label for="nama" class="mr-2 flex-shrink-0">Nama:</label>
                    <input type="text" id="nama" name="nama" class="border rounded-md px-2 py-1">
                </div>
                <div class="flex mb-2">
                    <label for="harga" class="mr-2 flex-shrink-0">Harga:</label>
                    <input type="text" id="harga" name="harga" class="border rounded-md px-2 py-1">
                </div>
                <div class="flex mb-4">
                    <label for="status" class="mr-2 flex-shrink-0">Status:</label>
                    <input type="text" id="status" name="status" class="border rounded-md px-2 py-1">
                </div>
                <button type="submit" name="tambah" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Tambah</button>
            </form>
        </div>

        
    </div>
</div>

<!-- JavaScript to control modal -->
<script>
    // Get modal element
    const modal = document.getElementById('editModal');

    // Get close button
    const cancelBtn = document.getElementById('cancelEdit');

    // When Edit button is clicked, show modal
    const editButtons = document.querySelectorAll('.editButton');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.parentElement.parentElement.querySelector('td:nth-child(2)').innerText;
            const harga = this.parentElement.parentElement.querySelector('td:nth-child(3)').innerText;
            const status = this.parentElement.parentElement.querySelector('td:nth-child(4)').innerText;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_status').value = status;

            modal.classList.remove('hidden');
        });
    });

    // Close modal when cancel button is clicked
    cancelBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Function to update table after an operation
    function updateTable() {
        // Reload the page to reflect the changes made
        location.reload();
    }
</script>

</body>
</html>
