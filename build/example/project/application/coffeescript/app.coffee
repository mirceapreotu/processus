build_root = ''

###############################################

window.AppConfig =
  public_root:  "#{build_root}"
  assets_root:  "#{build_root}/assets"
  api_urlroot:  "/api/v1"
  fbAppId: '184206854998730'
  log_silence:  false

###############################################

window.App =
  Controllers: {}
  Models: {}
  Collections: {}
  Views: {}

  Log: (args) ->
    if typeof console isnt 'undefined' and window.AppConfig.log_silence is false
      console.log args

  Start: ->
    window.App.Log 'Starting App'
    window.App.Log ['Browser', BrowserDetect.OS, BrowserDetect.browser, BrowserDetect.version]

    if BrowserDetect.browser is 'Explorer' and BrowserDetect.version < 9
      $('#browser-notification').modal('show')
    else
      window.AppController = new App.Controllers.App()
      Backbone.history.start root: window.AppConfig.public_root
