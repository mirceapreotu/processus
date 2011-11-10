(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  window.App.Log('loading model...member');
  App.Models.Member = Backbone.Model.extend({
    urlRoot: window.AppConfig.rpc_urlroot,
    options: {
      loginUrl: '!/login',
      loggedInUrl: '!/'
    },
    defaults: {
      has_session: false
    },
    initialize: function() {
      return window.App.Log(['init model.member']);
    },
    login: function(response) {
      window.App.Log(['model.member.login']);
      return this.getInitialData();
    },
    logout: function() {
      window.App.Log(['model.member.logout']);
      return FB.logout(__bind(function(response) {
        window.Member.attributes.has_session = false;
        return window.AppController.navigate(this.options.loginUrl, true);
      }, this));
    },
    getInitialData: function() {
      window.App.Log(['model.member.getInitialData']);
      return this.fetch({});
    },
    setInitialData: function() {
      window.App.Log(["model.member.setInitialData", window.Member.attributes]);
      $('#member-container').find('span').text(window.Member.attributes.user.fullName);
      $('#member-container').find('img').attr('src', "https://graph.facebook.com/" + window.Member.attributes.user.userId + "/picture?type=square");
      window.Member.attributes.has_session = true;
      window.ViewLayout.showAppPage();
      return window.AppController.navigate(this.options.loggedInUrl, true);
    },
    getAppFriends: function() {
      return window.App.Log(['model.member.getAppFriends']);
    }
  });
  App.Models.Member.prototype.sync = function(method, model, options) {
    return $.ajax({
      url: window.AppConfig.api_urlroot + '/app/',
      type: 'POST',
      contentType: 'application/json',
      data: '{"id": 1, "method": "App.User.getInitialData", "params": []}',
      dataType: 'json',
      success: function(resp, status) {
        options.success(resp.result);
        return window.Member.setInitialData();
      },
      error: function(resp, status) {
        return window.App.Log(['model.member.error', resp.responseText]);
      }
    });
  };
}).call(this);
