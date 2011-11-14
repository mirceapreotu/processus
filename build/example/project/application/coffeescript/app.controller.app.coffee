window.App.Log 'loading controller...'

App.Controllers.App = Backbone.Router.extend

  routes:
    "!/login"     : "login"
    "!/logout"    : "logout"
    "!/"          : "home"
    ""            : "home"

  ###############################################

  initialize: ->
    window.App.Log ['init controller']
    window.Member = new App.Models.Member
    window.ViewLayout = new App.Views.Layout

    # facebook init
    FB.init
      appId: window.AppConfig.fbAppId
      status: true
      cookie: true
      oauth: true
      xfbml: true

    # trigger successful authentication
    FB.Event.subscribe 'auth.login', (response) ->
      window.App.Log ['trigger fb.auth.login', response, document.cookie]
      window.AppController.fbConnectedCookieChecker(response)   

  ###############################################

  fbConnectedCookieChecker: (response) ->
    intervalMs = 1

    cookieChecker = (intervalInstance) ->
      window.App.Log ["controller.fbConnectedCookieChecker... #{intervalMs}ms", document.cookie]
      if document.cookie.match(/fbsr_/)
        window.clearInterval(run)
        window.Member.login(response)

    run = window.setInterval(cookieChecker, intervalMs)

  ###############################################

  loadPage: (elmId) ->
    window.App.Log ['show page #', elmId]

    # reset navigation to current section
    $('.nav li').removeClass('active')
    $("#nav-#{elmId}").addClass('active')

    # load only current section
    $('.page').hide()
    $("##{elmId}").fadeIn()

  ###############################################

  redirectToLogin: ->
    window.App.Log ['controller.redirectToLogin']
    window.AppController.navigate('#!/login', true)    

  ###############################################

  login: ->
    window.App.Log ['controller.login']

    # we got a session
    FB.getLoginStatus (response) ->
      window.App.Log ['fb.getLoginStatus', response]

      if response.status is 'connected'
        window.Member.login()
      else
        window.ViewLayout.showLoginPage()

  ###############################################

  logout: ->
    window.App.Log ['controller.logout']
    window.Member.logout()

  ###############################################

  home: ->
    if window.Member.attributes.has_session is not true
      this.redirectToLogin()
    else
      window.App.Log ['controller.home']
      this.loadPage('home')
