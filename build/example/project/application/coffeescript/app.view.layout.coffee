window.App.Log 'loading view...layout'

App.Views.Layout = Backbone.View.extend

  el: $('#matrix')

  ###############################################

  initialize: ->
    window.App.Log ['init view.layout'];
    $('.topbar').dropdown()

  ###############################################

  showLoginPage: ->
      window.App.Log ['view.layout.showLoginPage']
      $('#matrix').hide()
      $('#login-page').fadeIn()

  ###############################################

  showAppPage: ->
      window.App.Log ['view.layout.showAppPage']
      $('#login-page').hide()
      $('#matrix').fadeIn()

