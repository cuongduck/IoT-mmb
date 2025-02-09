async function updateLineMixingTable(line) {
    try {
        const response = await fetch(`api/line_details/get_line_mixing_data.php?line=${line}`);
        const result = await response.json();

        if (!result.success) {
            console.error('Error loading data:', result.message);
            return;
        }

        const tableBody = document.getElementById('mixingTableBody');
        if (!tableBody) {
            console.error('Table body element not found');
            return;
        }

        // Xóa dữ liệu cũ
        tableBody.innerHTML = '';

        // Thêm dữ liệu mới
        result.data.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';
            
            // Format date
            const date = new Date(row.Date);
            const formattedDate = date.toLocaleString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Format numbers with 1 decimal place if not null
            const formatValue = (value) => {
                if (value === null || value === undefined) {
                    return '-';
                }
                return parseFloat(value).toFixed(1);
            };

            tr.innerHTML = `
                <td class="px-4 py-2 border text-center whitespace-nowrap">${formattedDate}</td>
                <td class="px-4 py-2 border text-right">${formatValue(row[`${line}_KL_Coi1_Bom`])}</td>
                <td class="px-4 py-2 border text-right">${formatValue(row[`${line}_KL_Coi1_Xa`])}</td>
                <td class="px-4 py-2 border text-right">${formatValue(row[`${line}_KL_Coi1_KS`])}</td>
                <td class="px-4 py-2 border text-right">${formatValue(row[`${line}_KL_Coi2_Bom`])}</td>
                <td class="px-4 py-2 border text-right">${formatValue(row[`${line}_KL_Coi2_Xa`])}</td>
                <td class="px-4 py-2 border text-right">${formatValue(row[`${line}_KL_Coi2_KS`])}</td>
            `;
            tableBody.appendChild(tr);
        });

    } catch (error) {
        console.error('Error updating mixing table:', error);
    }
}