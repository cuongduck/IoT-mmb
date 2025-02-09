<?php
require_once 'config/database.php';

// Xử lý xóa
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM KHSX WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">Xóa thành công!</span>
            </div>';
    } else {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">Có lỗi khi xóa!</span>
            </div>';
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete'])) {
    if (isset($_POST['action'])) {
        $line = $_POST['Line'];
        $ten_sp = $_POST['Ten_sp'];
        $tu_ngay = $_POST['Tu_ngay'];
        $den_ngay = $_POST['den_ngay'];
        
        if ($_POST['action'] == 'add') {
            $sql = "INSERT INTO KHSX (Line, Ten_sp, Tu_ngay, den_ngay) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $line, $ten_sp, $tu_ngay, $den_ngay);
        } else if ($_POST['action'] == 'edit' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "UPDATE KHSX SET Line=?, Ten_sp=?, Tu_ngay=?, den_ngay=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $line, $ten_sp, $tu_ngay, $den_ngay, $id);
        }
        
        if ($stmt->execute()) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">Thao tác thành công!</span>
                </div>';
        } else {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">Có lỗi xảy ra!</span>
                </div>';
        }
    }
}

// Fetch existing record if editing
$editData = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM KHSX WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
}
?>

<!-- Script xác nhận xóa -->
<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa kế hoạch này không?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>

<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            <?php echo $editData ? 'Sửa Kế Hoạch Sản Xuất' : 'Thêm Kế Hoạch Sản Xuất'; ?>
        </h2>

        <form method="POST" class="space-y-4">
            <?php if ($editData) { ?>
                <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
            <?php } ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="Line" class="block text-sm font-medium text-gray-700 mb-1">Line</label>
                    <select name="Line" id="Line" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <?php
                        $lines = ['Line 1', 'Line 2', 'Line 3', 'Line 4', 'Line 5', 'Line 6', 'Line 7', 'Line 8'];
                        foreach ($lines as $line) {
                            $selected = ($editData && $editData['Line'] == $line) ? 'selected' : '';
                            echo "<option value='$line' $selected>$line</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="Ten_sp" class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm</label>
                    <input type="text" name="Ten_sp" id="Ten_sp" required 
                           value="<?php echo $editData ? htmlspecialchars($editData['Ten_sp']) : ''; ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label for="Tu_ngay" class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                    <input type="datetime-local" name="Tu_ngay" id="Tu_ngay" required
                           value="<?php echo $editData ? date('Y-m-d\TH:i', strtotime($editData['Tu_ngay'])) : ''; ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label for="den_ngay" class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                    <input type="datetime-local" name="den_ngay" id="den_ngay" required
                           value="<?php echo $editData ? date('Y-m-d\TH:i', strtotime($editData['den_ngay'])) : ''; ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <input type="hidden" name="action" value="<?php echo $editData ? 'edit' : 'add'; ?>">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <?php echo $editData ? 'Cập nhật' : 'Thêm mới'; ?>
                </button>
                <?php if ($editData) { ?>
                    <a href="?page=production_plan" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Hủy
                    </a>
                <?php } ?>
            </div>
        </form>
    </div>

    <!-- Display existing records -->
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <h3 class="text-xl font-bold p-4 bg-gray-50 border-b">Danh sách kế hoạch sản xuất</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Line</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên SP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Từ ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đến ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $sql = "SELECT * FROM KHSX ORDER BY ID ASC";
                    $result = $conn->query($sql);
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td class='px-6 py-4 whitespace-nowrap'>{$row['Line']}</td>
                                <td class='px-6 py-4 whitespace-nowrap'>{$row['Ten_sp']}</td>
                                <td class='px-6 py-4 whitespace-nowrap'>" . date('d/m/Y H:i', strtotime($row['Tu_ngay'])) . "</td>
                                <td class='px-6 py-4 whitespace-nowrap'>" . date('d/m/Y H:i', strtotime($row['den_ngay'])) . "</td>
                                <td class='px-6 py-4 whitespace-nowrap space-x-2'>
                                    <a href='?page=production_plan&edit={$row['id']}' 
                                       class='text-blue-600 hover:text-blue-900 mr-2'>Sửa</a>
                                    <form id='delete-form-{$row['id']}' method='POST' class='inline'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <input type='hidden' name='delete' value='1'>
                                        <button type='button' 
                                                onclick='confirmDelete({$row['id']})' 
                                                class='text-red-600 hover:text-red-900'>
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>