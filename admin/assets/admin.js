document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM загружен, ищу select...');
    
    document.querySelectorAll('.status-select').forEach(select => {
        console.log('Найден select для заказа', select.dataset.orderId);
        
        select.addEventListener('change', function() {
            const orderId = this.dataset.orderId;
            const newStatus = this.value;
            const row = this.closest('tr');
            
            fetch('../api/admin/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const dot = row.querySelector('.status-dot');
                    if (dot) {
                        dot.className = 'status-dot status-dot-' + newStatus;
                        console.log('Кружок обновлён:', newStatus);
                    }
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        });
    });
});