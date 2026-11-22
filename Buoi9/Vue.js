const app = Vue.createApp({
  data() {
    return {
      products: [],
      form: { id: null, name: '', price: '', stock: '' },
      isEdit: false,
      errorMessage: '',
      selectedFile: null,
      previewUrl: '',
      currentImage: '',
      pagination: { page: 1, limit: 5, total: 0, total_pages: 1 }
    }
  },

  mounted() {
    this.loadProducts(1);
  },

  methods: {
    async loadProducts(page = this.pagination.page) {
      try {
        const res = await fetch(`api/products.php?page=${page}&limit=5`);
        const data = await res.json();

        if (!data.success) {
          this.errorMessage = data.message;
          return;
        }

        this.products = data.data;
        this.pagination = data.pagination;
        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Không thể tải danh sách sản phẩm.';
      }
    },

    changePage(page) {
      if (page < 1 || page > this.pagination.total_pages) return;
      this.loadProducts(page);
    },

    handleImageChange(event) {
      const file = event.target.files[0] || null;
      this.selectedFile = null;

      if (this.previewUrl && this.previewUrl.startsWith('blob:')) {
        URL.revokeObjectURL(this.previewUrl);
      }

      if (!file) {
        this.previewUrl = this.currentImage;
        return;
      }

      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        this.errorMessage = 'Chỉ chấp nhận file JPG, JPEG hoặc PNG.';
        event.target.value = '';
        this.previewUrl = this.currentImage;
        return;
      }
      if (file.size > 2 * 1024 * 1024) {
        this.errorMessage = 'Dung lượng ảnh không được vượt quá 2 MB.';
        event.target.value = '';
        this.previewUrl = this.currentImage;
        return;
      }

      this.selectedFile = file;
      this.previewUrl = URL.createObjectURL(file);
      this.errorMessage = '';
    },

    resetForm() {
      if (this.previewUrl && this.previewUrl.startsWith('blob:')) {
        URL.revokeObjectURL(this.previewUrl);
      }
      this.form = { id: null, name: '', price: '', stock: '' };
      this.isEdit = false;
      this.errorMessage = '';
      this.selectedFile = null;
      this.previewUrl = '';
      this.currentImage = '';
      const input = document.getElementById('image');
      if (input) input.value = '';
    },

    async uploadImage(productId) {
      if (!this.selectedFile) return { success: true };

      const body = new FormData();
      body.append('product_id', productId);
      body.append('image', this.selectedFile);

      try {
        const res = await fetch('api/upload.php', { method: 'POST', body });
        return await res.json();
      } catch (error) {
        return { success: false, message: 'Không thể upload ảnh. Vui lòng thử lại.' };
      }
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

        const productId = this.isEdit ? this.form.id : data.data.id;
        this.form.id = productId;
        this.isEdit = true;

        const uploadResult = await this.uploadImage(productId);
        if (!uploadResult.success) {
          this.errorMessage = uploadResult.message;
          await this.loadProducts(this.pagination.page);
          return;
        }

        this.resetForm();
        if (method === 'POST') {
          await this.loadProducts(1);
        } else {
          await this.loadProducts(this.pagination.page);
        }
      } catch (error) {
        this.errorMessage = 'Lỗi khi lưu sản phẩm.';
      }
    },

    editProduct(product) {
      this.resetForm();
      this.form = {
        id: product.id,
        name: product.name,
        price: product.price,
        stock: product.stock
      };
      this.isEdit = true;
      this.currentImage = product.image ? product.image : '';
      this.previewUrl = this.currentImage;
    },

    async deleteProduct(id) {
      if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

      try {
        const res = await fetch('api/products.php?id=' + id, { method: 'DELETE' });
        const data = await res.json();

        if (!data.success) {
          this.errorMessage = data.message;
          return;
        }

        await this.loadProducts(this.pagination.page);
      } catch (error) {
        this.errorMessage = 'Lỗi khi xóa sản phẩm.';
      }
    }
  }
});

app.mount('#app');
