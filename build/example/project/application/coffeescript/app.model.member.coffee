window.App.Log 'loading model...member'

App.Models.Member = Backbone.Model.extend

  urlRoot:
    window.AppConfig.rpc_urlroot

  ###############################################

  options:
    loginUrl: '!/login'
    loggedInUrl: '!/'

  ###############################################

  defaults:
    has_session: false

  ###############################################

  initialize: ->
    window.App.Log ['init model.member']

  ###############################################

  login: (response) ->
    window.App.Log ['model.member.login']

    # get initial data
    this.getInitialData()

  ###############################################

  logout: ->
    window.App.Log ['model.member.logout']

    FB.logout (response) =>
      window.Member.attributes.has_session = false
      window.AppController.navigate(this.options.loginUrl, true)

  ###############################################

  getInitialData: ->
    window.App.Log ['model.member.getInitialData']
    this.fetch({})

  ###############################################

  setInitialData: ->
    window.App.Log ["model.member.setInitialData", window.Member.attributes]

    $('#member-container').find('span').text(window.Member.attributes.user.fullName)
    $('#member-container').find('img').attr('src', "https://graph.facebook.com/#{window.Member.attributes.user.userId}/picture?type=square")

    # mark as logged-in
    window.Member.attributes.has_session = true

    # show app page
    window.ViewLayout.showAppPage()

    # redirect
    window.AppController.navigate(this.options.loggedInUrl, true)

  ###############################################

  getAppFriends: ->
    window.App.Log ['model.member.getAppFriends']


## /////////////////////////////////////////// ##


App.Models.Member.prototype.sync = (method, model, options) ->
  $.ajax
    url: window.AppConfig.api_urlroot + '/app/'
    type: 'POST'
    contentType: 'application/json'
    data: '{"id": 1, "method": "App.User.getInitialData", "params": []}'
    dataType: 'json'
    success: (resp, status) ->
      options.success(resp.result)
      window.Member.setInitialData()
    error: (resp, status) ->
      window.App.Log ['model.member.error', resp.responseText]
