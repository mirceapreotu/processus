(function() {
  window.App.Log('loading controller...');
  App.Controllers.App = Backbone.Router.extend({
    routes: {
      "!/login": "login",
      "!/logout": "logout",
      "!/": "home",
      "": "home"
    },
    initialize: function() {
      window.App.Log(['init controller']);
      window.Member = new App.Models.Member;
      window.ViewLayout = new App.Views.Layout;
      FB.init({
        appId: window.AppConfig.fbAppId,
        status: true,
        cookie: true,
        oauth: true,
        xfbml: true
      });
      return FB.Event.subscribe('auth.login', function(response) {
        window.App.Log(['trigger fb.auth.login', response]);
        return window.Member.login(response);
      });
    },
    loadPage: function(elmId) {
      window.App.Log(['show page #', elmId]);
      $('.nav li').removeClass('active');
      $("#nav-" + elmId).addClass('active');
      $('.page').hide();
      return $("#" + elmId).fadeIn();
    },
    redirectToLogin: function() {
      window.App.Log(['controller.redirectToLogin']);
      return window.AppController.navigate('#!/login', true);
    },
    login: function() {
      window.App.Log(['controller.login']);
      FB.init({
        appId: window.AppConfig.fbAppId,
        status: true,
        cookie: true,
        oauth: true,
        xfbml: true
      });
      FB.Event.subscribe('auth.login', function(response) {
        window.App.Log(['trigger fb.auth.login', response]);
        return window.Member.login(response);
      });
      return FB.getLoginStatus(function(response) {
        window.App.Log(['fb.getLoginStatus', response]);
        if (response.status === 'connected') {
          return window.Member.login();
        } else {
          return window.ViewLayout.showLoginPage();
        }
      });
    },
    logout: function() {
      window.App.Log(['controller.logout']);
      return window.Member.logout();
    },
    home: function() {
      if (window.Member.attributes.has_session === !true) {
        return this.redirectToLogin();
      } else {
        window.App.Log(['controller.home']);
        return this.loadPage('home');
      }
    }
  });
}).call(this);
