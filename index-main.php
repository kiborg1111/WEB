<div class="index-main">
    <div class="main">
        <div class="main-container">
            <div class="title">
                Кроссовки <br> которых <br> нет у всех
            </div>
            <div class="titlw-photo">
                <img src="../kickzone/photo/2.png" alt="Арт">
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
                    <a href="/kickzone/catalog.php" class="btn-catalog">Каталог</a>
                </div>
            </div>
        </div>
        <div class="new">
            <div class="text" id="scroll-section">Новинки</div>
            <div class="glass">
                <img src="/kickzone/photo/glass.png" alt="глаза">
            </div>
        </div>
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
fetch('/kickzone/api/products.php')
    .then(response => response.json())
    .then(data => {
        if (data.success && data.products.length > 0) {
            const container = document.getElementById('products-group');
            const products = data.products.slice(0, 6);
            
            container.innerHTML = products.map(product => `
                <a href="/kickzone/product-card.php?id=${product.id}" class="card-link">
                    <div class="card" style="background: url('/kickzone/uploads/products/${product.image}') center/cover no-repeat;">
                        <div>
                            <div>${product.name}</div>
                            <div>${Number(product.price).toLocaleString()} ₽</div>
                        </div>
                    </div>
                </a>
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