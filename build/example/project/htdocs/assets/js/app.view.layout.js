(function() {
  window.App.Log('loading view...layout');
  App.Views.Layout = Backbone.View.extend({
    el: $('#matrix'),
    initialize: function() {
      window.App.Log(['init view.layout']);
      return $('.topbar').dropdown();
    },
    showLoginPage: function() {
      window.App.Log(['view.layout.showLoginPage']);
      $('#matrix').hide();
      return $('#login-page').fadeIn();
    },
    showAppPage: function() {
      window.App.Log(['view.layout.showAppPage']);
      $('#login-page').hide();
      return $('#matrix').fadeIn();
    }
  });
}).call(this);
