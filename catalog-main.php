<div class="catalog-main">
    <div class="main1">
        <div class="main-container1">
            <div class="title1">
                Каталог
            </div>
        </div>
        <div class="filter-cards">
            <div class="filter">
                <div class="price">Цена</div>
                <div class="slider" id="Slider"></div>
                <div class="price-values">
                    <span id="sliderMinValue"></span>
                    <span id="sliderMaxValue"></span>
                </div>
                <div class="brend">Бренд</div>
                <div class="brend-card"></div>
                <div class="size">Размер</div>
                <div class="size-card"></div>
                <div class="color">Цвет</div>
                <div class="color-card"></div>
            </div>

            <div class="card-container">
                <div class="group-product" id="catalog-products-container">
                    <div style="text-align: center; width: 100%;">Загрузка товаров...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('Slider');
        
        if (slider && window.noUiSlider) {
            noUiSlider.create(slider, {
                start: [2000, 50000],
                connect: true,
                step: 500,
                range: {
                    'min': 2000,
                    'max': 50000
                },
                format: {
                    to: value => Math.round(value),
                    from: value => Math.round(value)
                }
            });
            
            const minValueSpan = document.getElementById('sliderMinValue');
            const maxValueSpan = document.getElementById('sliderMaxValue');
            
            slider.noUiSlider.on('update', function(values, handle) {
                if (handle === 0) {
                    minValueSpan.textContent = Math.round(values[0]);
                }
                if (handle === 1) {
                    maxValueSpan.textContent = Math.round(values[1]);
                }
            });
        }
    });

    getProducts().then(products => {
        const container = document.getElementById('catalog-products-container');
        if (!container) return;
        
        if (products.length === 0) {
            container.innerHTML = '<div style="text-align: center; width: 100%;">Нет товаров</div>';
            return;
        }
        
        container.innerHTML = products.map(product => `
            <div class="card-product">
                <img src="/uploads/products/${product.image || 'placeholder.jpg'}" style="width: 100%; height: 200px; object-fit: cover;">
                <h3>${product.name}</h3>
                <p>${Number(product.price).toLocaleString()} ₽</p>
                <button class="add-to-cart-btn" data-id="${product.id}">В корзину</button>
            </div>
        `).join('');
        
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const productId = btn.dataset.id;
                const result = await addToCart(productId, 1);
                alert(result.success ? 'Товар добавлен в корзину' : 'Ошибка: ' + result.message);
            });
        });
    });
</script>