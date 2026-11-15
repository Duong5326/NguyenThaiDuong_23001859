const app = Vue.createApp({
  data() {
    return {
      products: [],
      form: { id: null, name: '', price: '', stock: '' },
      isEdit: false,
      errorMessage: ''
    }
  },

  mounted() {
    this.loadProducts();
  },

  methods: {
    async loadProducts() {
      try {
        const res = await fetch('api/products.php');
        const data = await res.json();

        if (!data.success) {
          this.errorMessage = data.message;
          return;
        }

        this.products = data.data;
        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Không thể tải danh sách sản phẩm.';
      }
    },

    resetForm() {
      this.form = { id: null, name: '', price: '', stock: '' };
      this.isEdit = false;
      this.errorMessage = '';
    },

    async saveProduct() {
      const payload = {
        name: this.form.name,
        price: Number(this.form.price),
        stock: Number(this.form.stock)
      };

      let url = 'api/products.php';
      let method = 'POST';

      if (this.isEdit && this.form.id) {
        url += '?id=' + this.form.id;
        method = 'PUT';
      }

      try {
        const res = await fetch(url, {
          method,
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (!data.success) {
          this.errorMessage = data.message;
          return;
        }

        this.resetForm();
        this.loadProducts();
      } catch (error) {
        this.errorMessage = 'Lỗi khi lưu sản phẩm.';
      }
    },

    editProduct(product) {
      this.form = {
        id: product.id,
        name: product.name,
        price: product.price,
        stock: product.stock
      };
      this.isEdit = true;
      this.errorMessage = '';
    },

    async deleteProduct(id) {
      const confirmed = confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');
      if (!confirmed) return;

      try {
        const res = await fetch('api/products.php?id=' + id, {
          method: 'DELETE'
        });

        const data = await res.json();

        if (!data.success) {
          this.errorMessage = data.message;
          return;
        }

        this.loadProducts();
      } catch (error) {
        this.errorMessage = 'Lỗi khi xóa sản phẩm.';
      }
    }
  }
});

app.mount('#app');
