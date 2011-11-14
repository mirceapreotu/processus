(function() {
  window.App.Log('loading controller...');
  App.Controllers.App = Backbone.Router.extend({
    routes: {
      "!/login": "login",
      "!/logout": "logout",
      "!/profile": "profile",
      "!/": "newsfeed",
      "": "newsfeed"
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
        window.App.Log(['trigger fb.auth.login', response, document.cookie]);
        return window.AppController.fbConnectedCookieChecker(response);
      });
    },
    fbConnectedCookieChecker: function(response) {
      var cookieChecker, intervalMs, run;
      intervalMs = 1;
      cookieChecker = function(intervalInstance) {
        window.App.Log(["controller.fbConnectedCookieChecker... " + intervalMs + "ms", document.cookie]);
        if (document.cookie.match(/fbsr_/)) {
          window.clearInterval(run);
          return window.Member.login(response);
        }
      };
      return run = window.setInterval(cookieChecker, intervalMs);
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
    newsfeed: function() {
      if (window.Member.attributes.has_session === !true) {
        return this.redirectToLogin();
      } else {
        window.App.Log(['controller.feed']);
        return this.loadPage('newsfeed');
      }
    },
    profile: function() {
      if (window.Member.attributes.has_session === !true) {
        return this.redirectToLogin();
      } else {
        window.App.Log(['controller.profile']);
        return this.loadPage('profile');
      }
    }
  });
}).call(this);
