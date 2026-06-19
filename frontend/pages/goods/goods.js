const app = getApp();
Page({
  data: {
    keyword: '',
    goodsList: [],
    page: 1,
    pageSize: 10,
    loadingText: '下拉加载更多'
  },
  onLoad() {
    this.loadGoods();
  },
  onReachBottom() {
    this.setData({ page: this.data.page + 1 }, () => {
      this.loadGoods();
    });
  },
  onPullDownRefresh() {
    this.setData({ page: 1, goodsList: [] }, () => {
      this.loadGoods(() => wx.stopPullDownRefresh());
    });
  },
  onInput(e) {
    this.setData({ keyword: e.detail.value });
  },
  onSearch() {
    this.setData({ page: 1, goodsList: [] }, () => {
      this.loadGoods();
    });
  },
  loadGoods(callback) {
    wx.request({
      url: `${app.globalData.apiBase}/goods_list.php`,
      data: {
        keyword: this.data.keyword,
        page: this.data.page,
        pageSize: this.data.pageSize
      },
      method: 'GET',
      success: res => {
        if (res.data.code === 0) {
          const list = this.data.goodsList.concat(res.data.data);
          this.setData({ goodsList: list });
          if (res.data.data.length < this.data.pageSize) {
            this.setData({ loadingText: '没有更多商品' });
          }
        }
      },
      complete: () => {
        if (callback) callback();
      }
    });
  }
});
