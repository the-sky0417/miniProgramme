const app = getApp();
Page({
  data: {
    goods: {}
  },
  onLoad(options) {
    this.goodsId = options.id;
    this.fetchGoods();
  },
  fetchGoods() {
    wx.request({
      url: `${app.globalData.apiBase}/goods_detail.php`,
      data: { id: this.goodsId },
      success: res => {
        if (res.data.code === 0) {
          this.setData({ goods: res.data.data });
        }
      }
    });
  },
  addToCart() {
    const cart = wx.getStorageSync('cart') || [];
    const exist = cart.find(item => item.id === this.data.goods.id);
    if (exist) {
      exist.quantity += 1;
    } else {
      cart.push({ ...this.data.goods, quantity: 1, checked: true });
    }
    wx.setStorageSync('cart', cart);
    wx.showToast({ title: '已加入购物车', icon: 'success' });
  },
  createOrder() {
    const userInfo = wx.getStorageSync('userInfo');
    if (!userInfo) {
      return wx.showModal({ title: '提示', content: '请先登录' });
    }
    wx.request({
      url: `${app.globalData.apiBase}/order_create.php`,
      method: 'POST',
      data: {
        user_id: userInfo.id,
        goods: JSON.stringify([{ id: this.data.goods.id, quantity: 1, price: this.data.goods.price }]),
        total_price: this.data.goods.price,
        status: '待付款'
      },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code === 0) {
          wx.showToast({ title: '下单成功' });
          wx.navigateTo({ url: '/pages/orders/orders' });
        }
      }
    });
  },
  previewImage() {
    wx.previewImage({ urls: [this.data.goods.cover] });
  }
});
