<div class="index-main">
    <div class="main">
        <div class="main-container">
            <div class="title">
                Кроссовки <br> которых <br> нет у всех
            </div>
        </div>
        <div class="boot">
            <img src="../kickzone/photo/1.png" alt="Кроссовок">
        </div>
        <div class="catalog-container">
            <div class="main-catalog">
                <div class="catalog-image">
                    <img src="../kickzone/photo/title.jpg" alt="Каталог">
                </div>
                <div class="catalog-line"></div>
                <div class="catalog-button">
                    <a href="catalog.php" class="btn-catalog">Каталог</a>
                </div>
            </div>
        </div>
        <div class="text" id="scroll-section">Новинки</div>
        <div class="scroll">
            <button class="left">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="right">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div class="scroll-container" id="scroll_js">
                <div class="group" id="products-group">
                    <div class="card"></div>
                    <div class="card"></div>
                    <div class="card"></div>
                    <div class="card"></div>
                    <div class="card"></div>
                    <div class="card"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Загрузка товаров из БД
fetch('http://localhost:8888/kickzone/api/products.php')
    .then(response => response.json())
    .then(data => {
        if (data.success && data.products.length > 0) {
            const container = document.getElementById('products-group');
            const products = data.products.slice(0, 6);
            
            container.innerHTML = products.map(product => `
                <div class="card" style="background: url('http://localhost:8888/kickzone/uploads/products/${product.image}') center/cover no-repeat; position: relative; border: 2px solid black; border-radius: 10px;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 12px; text-align: center; border-radius: 0 0 8px 8px;">
                        <div style="font-family: 'font2', sans-serif; font-size: 16px; font-weight: 600; margin-bottom: 5px;">${product.name}</div>
                        <div style="font-family: 'font1', sans-serif; font-size: 14px;">${Number(product.price).toLocaleString()} ₽</div>
                    </div>
                </div>
            `).join('');
        }
    })
    .catch(error => console.error('Ошибка:', error));

    // Твой оригинальный скрипт стрелок
    (function() {
        var container = document.getElementById('scroll_js');
        var leftBtn = document.querySelector('.left');
        var rightBtn = document.querySelector('.right');
        
        if (!container || !leftBtn || !rightBtn) return;
        
        function updateButtons() {
            var scrollLeft = container.scrollLeft;
            var maxScroll = container.scrollWidth - container.clientWidth;
            
            if (scrollLeft <= 10) {
                leftBtn.style.display = 'none';
            } else {
                leftBtn.style.display = 'flex';
            }
            
            if (scrollLeft >= maxScroll - 10) {
                rightBtn.style.display = 'none';
            } else {
                rightBtn.style.display = 'flex';
            }
        }
        
        function scrollLeft() {
            var scrollLeft = container.scrollLeft;
            if (scrollLeft > 10) {
                container.scrollBy({ left: -window.innerWidth, behavior: 'smooth' });
                updateButtons();
            }
        }
        
        function scrollRight() {
            var scrollLeft = container.scrollLeft;
            var maxScroll = container.scrollWidth - container.clientWidth;
            if (scrollLeft < maxScroll - 10) {
                container.scrollBy({ left: window.innerWidth, behavior: 'smooth' });
                updateButtons();
            }
        }
        
        leftBtn.onclick = scrollLeft;
        rightBtn.onclick = scrollRight;
        container.addEventListener('scroll', updateButtons);
        updateButtons();
    })();
</script>