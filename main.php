<main>
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
                <div class="group">
                    <div class="card">Карточка 1</div>
                    <div class="card">Карточка 2</div>
                    <div class="card">Карточка 3</div>
                    <div class="card">Карточка 4</div>
                    <div class="card">Карточка 5</div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    (function() {
        var container = document.getElementById('scroll_js');
        var leftBtn = document.querySelector('.left');
        var rightBtn = document.querySelector('.right');
        
        if (!container || !leftBtn || !rightBtn) return;
        
        // Функция обновления видимости кнопок
        function updateButtons() {
            var scrollLeft = container.scrollLeft;
            var maxScroll = container.scrollWidth - container.clientWidth;
            
            // На первой карточке - скрываем левую кнопку полностью
            if (scrollLeft <= 10) {
                leftBtn.style.display = 'none';
            } else {
                leftBtn.style.display = 'flex';
            }
            
            // На последней карточке - скрываем правую кнопку полностью
            if (scrollLeft >= maxScroll - 10) {
                rightBtn.style.display = 'none';
            } else {
                rightBtn.style.display = 'flex';
            }
        }
        
        // Функция скролла влево
        function scrollLeft() {
            var scrollLeft = container.scrollLeft;
            if (scrollLeft > 10) {
                container.scrollBy({ left: -window.innerWidth, behavior: 'smooth' });
                updateButtons();
            }
        }
        
        // Функция скролла вправо
        function scrollRight() {
            var scrollLeft = container.scrollLeft;
            var maxScroll = container.scrollWidth - container.clientWidth;
            if (scrollLeft < maxScroll - 10) {
                container.scrollBy({ left: window.innerWidth, behavior: 'smooth' });
                updateButtons();
            }
        }
        
        // Назначаем обработчики
        leftBtn.onclick = scrollLeft;
        rightBtn.onclick = scrollRight;
        
        // Обновляем при скролле
        container.addEventListener('scroll', updateButtons);
        
        // Начальное обновление
        updateButtons();
    })();
</script>