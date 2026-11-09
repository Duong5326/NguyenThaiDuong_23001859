window.TheSanPham = {
  props: {
    ten: { type: String, required: true },
    gia: { type: Number, required: true },
    danhMuc: { type: String, default: 'Chưa phân loại' },
    anh: {
      type: String,
      default: 'https://placehold.co/640x400/e8eef9/29477a?text=San+pham'
    },
    moTa: { type: String, default: 'Sản phẩm chất lượng, phù hợp cho nhu cầu sử dụng hằng ngày.' },
    soLuongTon: { type: Number, default: 0 }
  },

  emits: ['mua'],

  data() {
    return {
      dangMoMoTa: false
    };
  },

  methods: {
    formatCurrency(value) {
      return `${value.toLocaleString('vi-VN')} đ`;
    }
  },

  template: `
    <article class="product-card">
      <img class="product-image" :src="anh" :alt="'Ảnh ' + ten">
      <div class="product-content">
        <div class="product-heading">
          <div>
            <h2>{{ ten }}</h2>
            <p class="category">{{ danhMuc }}</p>
          </div>
          <span
            class="badge"
            :class="soLuongTon > 0 ? 'in-stock' : 'out-of-stock'"
          >
            {{ soLuongTon > 0 ? 'Còn hàng' : 'Hết hàng' }}
          </span>
        </div>
        <p class="price">{{ formatCurrency(gia) }}</p>
        <p class="stock">Số lượng tồn: {{ soLuongTon }}</p>
        <button
          type="button"
          class="secondary-button"
          @click="dangMoMoTa = !dangMoMoTa"
          :aria-expanded="dangMoMoTa"
        >
          {{ dangMoMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }}
        </button>
        <p v-if="dangMoMoTa" class="description">{{ moTa }}</p>
        <button
          type="button"
          class="buy-button"
          :disabled="soLuongTon <= 0"
          @click="$emit('mua')"
        >
          {{ soLuongTon > 0 ? 'Mua' : 'Hết hàng' }}
        </button>
      </div>
    </article>
  `
};
