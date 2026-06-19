const app = getApp();
Page({
  data: {
    orders: [],
    status: 'all',
    page: 1,
    pageSize: 10,
    loadingText: '加载中...'
  },
  onShow() {
    this.setData({ page: 1, orders: [] });
    this.loadOrders();
  },
  filterStatus(e) {
    const status = e.currentTarget.dataset.status;
    this.setData({ status, page: 1, orders: [] }, () => this.loadOrders());
  },
  onReachBottom() {
    this.setData({ page: this.data.page + 1 }, () => this.loadOrders());
  },
  loadOrders() {
    const userInfo = wx.getStorageSync('userInfo');
    if (!userInfo) return;
    wx.request({
      url: `${app.globalData.apiBase}/order_list.php`,
      data: {
        user_id: userInfo.id,
        status: this.data.status,
        page: this.data.page,
        pageSize: this.data.pageSize
      },
      method: 'GET',
      success: res => {
        if (res.data.code === 0) {
          const list = this.data.orders.concat(res.data.data);
          this.setData({ orders: list });
          if (res.data.data.length < this.data.pageSize) {
            this.setData({ loadingText: '没有更多订单' });
          }
        }
      }
    });
  }
});
