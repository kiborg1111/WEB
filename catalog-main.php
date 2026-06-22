<div class="catalog-main">
    <div class="main1">
        <div class="main-container1">
            <div class="title1">
                <?php
                if ($gender === 'male') {
                    echo 'Мужское';
                } elseif ($gender === 'female') {
                    echo 'Женское';
                } else {
                    echo 'Каталог';
                }
                ?>
            </div>
        </div>
        <div class="filter-cards">
            <button class="filter-toggle" id="filterToggle">
                <i class="fas fa-sliders-h"></i> 
            </button>

            <div class="filter" id="filterSidebar">
                <div class="brend">Категория</div>
                <div class="brend-card" id="category-filters"></div>

                <div class="price">Цена</div>
                <div class="slider" id="Slider"></div>
                <div class="price-values">
                    <span id="sliderMinValue"></span>
                    <span id="sliderMaxValue"></span>
                </div>

                <div class="brend">Бренд</div>
                <div class="brend-card" id="brand-filters"></div>

                <div class="size">Размер</div>
                <div class="size-card" id="size-filters"></div>

                <div class="color">Цвет</div>
                <div class="color-card" id="color-filters"></div>
            </div>

            <div class="card-container">
                <div class="group-product" id="catalog-products-container">
                    <div class="card-product">Загрузка...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cartNotification" class="cart-notification">
    <span id="notificationMessage">Товар добавлен в корзину</span>
</div>

<script src="/kickzone/js/nouislider.min.js"></script>
<script>
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('cartNotification');
        const messageEl = document.getElementById('notificationMessage');
        
        notification.classList.remove('success', 'error', 'show', 'hide');
        notification.classList.add(type);
        
        messageEl.textContent = message || 'Товар добавлен в корзину';
        notification.classList.add('show');
        
        clearTimeout(window.notificationTimeout);
        window.notificationTimeout = setTimeout(() => {
            notification.classList.remove('show');
            notification.classList.add('hide');
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filterToggle = document.getElementById('filterToggle');
        const filterSidebar = document.getElementById('filterSidebar');
        
        if (filterToggle && filterSidebar) {
            filterToggle.addEventListener('click', function() {
                filterSidebar.classList.toggle('active');
                const isOpen = filterSidebar.classList.contains('active');
                this.innerHTML = isOpen ? 
                    '<i class="fas fa-times"></i>' : 
                    '<i class="fas fa-sliders-h"></i>';
            });
        }

        const slider = document.getElementById('Slider');
        
        if (slider && window.noUiSlider) {
            noUiSlider.create(slider, {
                start: [2000, 25000],
                connect: true,
                step: 500,
                range: {
                    'min': 2000,
                    'max': 25000
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
                filterProducts();
            });
        }
    });

    let allProducts = [];

    async function loadProducts() {
        try {
            const url = GENDER ? '/kickzone/api/products.php?gender=' + GENDER : '/kickzone/api/products.php';
            const response = await fetch(url);
            const data = await response.json();
            if (data.success) {
                allProducts = data.products;
                initFilters();
                renderProducts(allProducts);
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }

    function renderProducts(products) {
        const container = document.getElementById('catalog-products-container');
        if (!container) return;
        
        if (products.length === 0) {
            container.innerHTML = '<div class="no-product">Нет товаров</div>';
            return;
        }
        
        container.innerHTML = products.map(product => `
            <div class="card-product">
                <a href="/kickzone/product-card.php?id=${product.id}" style="text-decoration: none; color: inherit;">
                    <img src="/kickzone/uploads/products/${product.image || 'placeholder.jpg'}" style="width: 100%; height: 200px; object-fit: cover;">
                    <h3>${product.name}</h3>
                    <p>${Number(product.price).toLocaleString()} ₽</p>
                </a>
                <button class="add-to-cart-btn" onclick="addToCart(${product.id}, 1); event.stopPropagation();">
                    <i class="fa-solid fa-basket-shopping"></i>
                </button>
            </div>
        `).join('');
    }

    function filterProducts() {
        const selectedCategories = Array.from(document.querySelectorAll('#category-filters input:checked')).map(cb => cb.value);
        const selectedBrands = Array.from(document.querySelectorAll('#brand-filters input:checked')).map(cb => cb.value);
        const selectedColors = Array.from(document.querySelectorAll('#color-filters input:checked')).map(cb => cb.value);
        const selectedSizes = Array.from(document.querySelectorAll('#size-filters input:checked')).map(cb => cb.value);
        
        const slider = document.getElementById('Slider');
        let minPrice = 2000;
        let maxPrice = 50000;
        if (slider && slider.noUiSlider) {
            const values = slider.noUiSlider.get();
            minPrice = parseFloat(values[0]);
            maxPrice = parseFloat(values[1]);
        }
        
        let filtered = [...allProducts];
        
        filtered = filtered.filter(p => parseFloat(p.price) >= minPrice && parseFloat(p.price) <= maxPrice);
        
        if (selectedCategories.length > 0) {
            filtered = filtered.filter(p => selectedCategories.includes(p.category_name));
        }
        if (selectedBrands.length > 0) {
            filtered = filtered.filter(p => selectedBrands.includes(p.brand));
        }
        if (selectedColors.length > 0) {
            filtered = filtered.filter(p => selectedColors.includes(p.color));
        }
        if (selectedSizes.length > 0) {
            filtered = filtered.filter(p => {
                if (!p.sizes) return false;
                const productSizes = p.sizes.split(', ').map(s => s.trim());
                return productSizes.some(s => selectedSizes.includes(s));
            });
        }
        
        renderProducts(filtered);
    }

    function initFilters() {
        const categories = [...new Set(allProducts.map(p => p.category_name).filter(c => c))];
        const brands = [...new Set(allProducts.map(p => p.brand).filter(b => b))];
        const colors = [...new Set(allProducts.map(p => p.color).filter(c => c))];
        const sizes = [...new Set(
            allProducts
                .flatMap(p => p.sizes ? p.sizes.split(', ').map(s => s.trim()) : [])
                .filter(s => s)
        )];
        
        const categoryContainer = document.getElementById('category-filters');
        const brandContainer = document.getElementById('brand-filters');
        const colorContainer = document.getElementById('color-filters');
        const sizeContainer = document.getElementById('size-filters');
        
        if (categoryContainer) {
            categoryContainer.innerHTML = categories.map(c => `<label><input type="checkbox" value="${c}" class="filter-checkbox"> ${c}</label>`).join('');
        }
        if (brandContainer) {
            brandContainer.innerHTML = brands.map(b => `<label><input type="checkbox" value="${b}" class="filter-checkbox"> ${b}</label>`).join('');
        }
        if (colorContainer) {
            colorContainer.innerHTML = colors.map(c => `<label><input type="checkbox" value="${c}" class="filter-checkbox"> ${c}</label>`).join('');
        }
        if (sizeContainer) {
            sizeContainer.innerHTML = sizes.sort((a,b) => a-b).map(s => `<label><input type="checkbox" value="${s}" class="filter-checkbox"> ${s}</label>`).join('');
        }
        
        document.querySelectorAll('.filter-checkbox').forEach(cb => {
            cb.addEventListener('change', filterProducts);
        });
    }

    document.addEventListener('DOMContentLoaded', loadProducts);
</script>