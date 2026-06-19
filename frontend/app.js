App({
  onLaunch() {
    const userInfo = wx.getStorageSync('userInfo') || null;
    this.globalData = {
      userInfo,
      apiBase: 'http://localhost/miniProgramme/backend/api'
    };
  }
});
