(function() {
  var build_root;
  build_root = '';
  window.AppConfig = {
    public_root: "" + build_root,
    assets_root: "" + build_root + "/assets",
    api_urlroot: "/api/v1",
    fbAppId: '162288590534951',
    log_silence: false
  };
  window.App = {
    Controllers: {},
    Models: {},
    Collections: {},
    Views: {},
    Log: function(args) {
      if (typeof console !== 'undefined' && window.AppConfig.log_silence === false) {
        return console.log(args);
      }
    },
    Start: function() {
      window.App.Log('Starting App');
      window.App.Log(['Browser', BrowserDetect.OS, BrowserDetect.browser, BrowserDetect.version]);
      if (BrowserDetect.browser === 'Explorer' && BrowserDetect.version < 9) {
        return $('#browser-notification').modal('show');
      } else {
        window.AppController = new App.Controllers.App();
        return Backbone.history.start({
          root: window.AppConfig.public_root
        });
      }
    }
  };
}).call(this);
