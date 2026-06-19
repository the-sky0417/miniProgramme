const app = getApp();
Page({
  data: {
    banners: [
      { id: 1, image: '/assets/banner1.jpg' },
      { id: 2, image: '/assets/banner2.jpg' }
    ],
    goodsList: []
  },
  onLoad() {
    this.loadGoods();
  },
  loadGoods() {
    wx.request({
      url: `${app.globalData.apiBase}/goods_list.php`,
      method: 'GET',
      success: res => {
        if (res.data.code === 0) {
          this.setData({ goodsList: res.data.data });
        }
      }
    });
  }
});
