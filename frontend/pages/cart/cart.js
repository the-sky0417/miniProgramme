Page({
  data: {
    cart: [],
    totalPrice: 0,
    allChecked: false
  },
  onShow() {
    this.loadCart();
  },
  loadCart() {
    const cart = wx.getStorageSync('cart') || [];
    const allChecked = cart.length > 0 && cart.every(item => item.checked);
    const totalPrice = cart.reduce((sum, item) => item.checked ? sum + item.price * item.quantity : sum, 0);
    this.setData({ cart, totalPrice, allChecked });
  },
  onItemCheck(e) {
    const id = Number(e.currentTarget.dataset.id);
    const checked = e.detail.value.length > 0;
    const cart = this.data.cart.map(item => {
      if (item.id === id) {
        item.checked = checked;
      }
      return item;
    });
    wx.setStorageSync('cart', cart);
    this.setData({ cart });
    this.loadCart();
  },
  onCheckAllChange(e) {
    const checked = e.detail.value.length > 0;
    const cart = this.data.cart.map(item => ({ ...item, checked }));
    wx.setStorageSync('cart', cart);
    this.setData({ cart, allChecked: checked });
    this.loadCart();
  },
  changeQuantity(e) {
    const id = Number(e.currentTarget.dataset.id);
    const action = e.currentTarget.dataset.action;
    const cart = this.data.cart.map(item => {
      if (item.id === id) {
        item.quantity = action === 'plus' ? item.quantity + 1 : Math.max(1, item.quantity - 1);
      }
      return item;
    });
    wx.setStorageSync('cart', cart);
    this.setData({ cart });
    this.loadCart();
  },
  removeItem(e) {
    const id = Number(e.currentTarget.dataset.id);
    const cart = this.data.cart.filter(item => item.id !== id);
    wx.setStorageSync('cart', cart);
    this.setData({ cart });
    this.loadCart();
  },
  goToCheckout() {
    const selected = this.data.cart.filter(item => item.checked);
    if (selected.length === 0) {
      return wx.showToast({ title: '请选择商品', icon: 'none' });
    }
    const userInfo = wx.getStorageSync('userInfo');
    if (!userInfo) {
      return wx.showModal({ title: '提示', content: '请先登录' });
    }
    const goods = selected.map(item => ({ id: item.id, quantity: item.quantity, price: item.price }));
    const total_price = selected.reduce((sum, item) => sum + item.price * item.quantity, 0);
    wx.request({
      url: `${getApp().globalData.apiBase}/order_create.php`,
      method: 'POST',
      data: {
        user_id: userInfo.id,
        goods: JSON.stringify(goods),
        total_price,
        status: '待付款'
      },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code === 0) {
          wx.showToast({ title: '下单成功' });
          const cart = this.data.cart.filter(item => !item.checked);
          wx.setStorageSync('cart', cart);
          this.setData({ cart });
          this.loadCart();
          wx.navigateTo({ url: '/pages/orders/orders' });
        } else {
          wx.showToast({ title: res.data.message || '下单失败', icon: 'none' });
        }
      }
    });
  },
  viewDetail(e) {
    const id = e.currentTarget.dataset.id;
    wx.navigateTo({ url: `/pages/goodsDetail/goodsDetail?id=${id}` });
  }
});
