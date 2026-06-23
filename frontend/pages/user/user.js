const app = getApp();
Page({
  data: {
    userInfo: {}
  },
  onShow() {
    const userInfo = wx.getStorageSync('userInfo') || {};
    this.setData({ userInfo });
  },
  onLogin() {
    wx.getUserProfile({
      desc: '用于完善会员资料',
      success: res => {
        wx.login({
          success: loginRes => {
            wx.request({
              url: `${app.globalData.apiBase}/user_register.php`,
              method: 'POST',
              data: {
                code: loginRes.code,
                nickName: res.userInfo.nickName,
                avatarUrl: res.userInfo.avatarUrl
              },
              header: { 'content-type': 'application/x-www-form-urlencoded' },
              success: response => {
                if (response.data.code === 0) {
                  const userInfo = response.data.data;
                  wx.setStorageSync('userInfo', userInfo);
                  this.setData({ userInfo });
                  wx.showToast({ title: '登录/注册成功' });
                } else {
                  wx.showToast({ title: response.data.message || '登录失败', icon: 'none' });
                }
              }
            });
          }
        });
      },
      fail() {
        wx.showToast({ title: '授权失败', icon: 'none' });
      }
    });
  }
});
