<?php
/*
ini_set("display_errors",1);
include_once dirname(__FILE__)."/../../../src/Bootstrap.php";
Bootstrap::init();
*/
/**
 * @var $this App_View_Facebook_Canvas
 */
?>

try {
   if (!MeetIdaaa) {
       MeetIdaaa = {};
   }
}catch(e) {
   MeetIdaaa = {};
}

// +++++ MeetIdaaa.console ++++++++++++

if (!MeetIdaaa.console) {
   MeetIdaaa.console = {
       log : 	function(p1,p2)
       {

           try {


               var enabled = <?php
                   echo json_encode(
                        $this->getController()
                                ->getDebug()
                                ->isFirebugEnabled()
                    );
               ?>;
               if (enabled != true) {
                   return;
               }
               if (window.console)
               {
                   if (arguments.length>=2)
                   {
                       window.console.log(p1,p2);
                   }else{
                       console.log(p1);
                   }
               }

           }catch(e){
           }
       }
   }
}

// ++++ MeetIdaaa.shell +++++++++++++++
MeetIdaaa.shell = {


    nodeId: "shellDiv",
    log: function()
    {
        var args=[];
        for(var i=0;i<arguments.length;i++) {
            args[i] = arguments[i];
        }
        MeetIdaaa.console.log.apply(null,args);

    },
    exec: function() {

        var data = document.getElementById(MeetIdaaa.shell.nodeId).innerHTML;
        data = data.split("<br>").join("");
        MeetIdaaa.shell.log("MeetIdaaa.shell.exec() cmd="+data);
        var result = eval(data);
        MeetIdaaa.shell.log("MeetIdaaa.shell.exec() result=",result);
    },
    create: function(parentNode) {

        var node = document.getElementById(MeetIdaaa.shell.nodeId);
        if (!node) {

            var $parentNode = null;
            if (typeof(parentNode)=="string") {
                var $parentNode = document.getElementById(parentNode);
                if (!$parentNode) {
                    throw new Error("MeetIdaaa.shell.create() error: parentNode invalid!");
                }
            }
            if (typeof(parentNode)=="object") {
                var $parentNode = parentNode;
            }
            if (!$parentNode) {
                var $parentNode = document.body;
            }

            var node = document.createElement('div');
            node.id = MeetIdaaa.shell.nodeId;
			node.contentEditable='true';
            node.setAttribute("style", "border:1px solid #ccc; height:60px; width:200px; font-family:'Courier New', Courier, monospace; font-size:12px");

            $parentNode.appendChild(node);

            var btnNode = document.createElement('button');
            btnNode.id = ""+MeetIdaaa.shell.nodeId+"_ShellBtn";
            btnNode.innerHTML=" shell.exec() ...";
            btnNode.setAttribute("onclick", "MeetIdaaa.shell.exec();");
            $parentNode.appendChild(btnNode);

        }

        return document.getElementById(MeetIdaaa.shell.nodeId);

    }

}

       
// +++++++ MeetIdaaa.env (social env info) +++++++++++++++++
MeetIdaaa.env = <?php echo json_encode(array(
    "serverStage" => array(
        "name" => Bootstrap::getRegistry()->getServerStage()->stage,
        "host" => Bootstrap::getRegistry()->getServerStageHost(),
    ),
    "debug" => array(
        "isDebugMode" => $this->getController()->getDebug()->isDebugMode(),
        "isDeveloper" => $this->getController()->getDebug()->isDeveloper(),
        "isDumpVarEnabled" => $this->getController()->getDebug()
                ->isDumpVarEnabled(),
        "isFirebugEnabled" => $this->getController()
                ->getDebug()
                ->isFirebugEnabled(),
    ),
    "application" => array(
        "socialEnvProvider" => $this->getController()
                ->getApplication()
                ->getSocialEnvProvider(),
        "isFacebookApplication" => $this->getController()
                ->getApplication()
                ->isFacebookApplication(),
    ),

    "mvc" => array(
        "controller" => Lib_Utils_Class::getClass($this->getController(), true),
        "view" => Lib_Utils_Class::getClass($this, true),
        "template" => $this->getTemplateBasename(),
        "config" => array(
            "pageType" => $this->getController()->getPageType(),
            "page" => $this->getController()->getPageConfig()->toArray(),
            "pages" => $this->getController()
                    ->getPagesConfigParsed()
                    ->toArray(),
        ),
    ),



    //   MeetIdaaa.env.flash: special config for flash app
    "flash" => array(
        // which sub app loads the swf main container first?
        "startApp" => "content",
        // imageProxyUrl
        "imageProxyUrl" => $this->getController()->getUrl("proxy/image.php"),
        "useImageProxy" => true,
        // url to legal infos page
        "termsUrl" => $this->getController()->getUrl("terms/terms.pdf"),
        // default config for swf embedding
        "swfObject" => array(
            "url" => $this->getController()->getUrl("swf/app.swf"),
            "divId" => "content",
            "width" => 520,
            "height" => 920,
            "embedParams" => array(
                "wmode" => "opaque",
                "base" => $this->getController()->getUrl("swf/"),
                "menu" => false,
                "allowScriptAccess" => "always",
            ),
            "flashVars" => array(
                /*
                "pageType" => $this->getController()
                        ->getPageType(),

                 */
            ),
        ),
    ),

    //   MeetIdaaa.env.rpc: rpc config
    "rpc" => $this->getController()->getRpcConfig(),


    // MeetIdaaa.env.social
    "social" => array(
        "login" => array(
            "scope" => $this->getController()->getLoginScope(),
            "loginUrl" => $this->getController()->getLoginUrl(null),
            "logoutUrl" => $this->getController()->getLogoutUrl(null),
        ),
        "request" => $this->getController()->getApplication()
                ->getFacebookSignedRequestDecodedForFrontend(),
    ),


));?>;

MeetIdaaa.getEnv = function() {
    return MeetIdaaa.env;
}
MeetIdaaa.isFacebookApplication = function()
{
    return (MeetIdaaa.getEnv().application.isFacebookApplication==true);
}

// ++++++++++++++++++ MeetIdaaa.model +++++++++++++++++++++++

MeetIdaaa.model = {};
MeetIdaaa.model.viewer = <?php echo json_encode($this->getController()->getViewerDTOIfExists())?>;
MeetIdaaa.model.isViewerRegistered = function()
{
    var viewer = MeetIdaaa.model.viewer;
    if (!viewer) {
        return false;
    }
    if (viewer.isRegistered != true) {
        return false;
    }

    return true;
}
MeetIdaaa.model.appConfig = null;
MeetIdaaa.model.getAppConfig = function() {
        return MeetIdaaa.model.appConfig;
        
}

// +++++++++++++++++++ MeetIdaaa.swf ++++++++++++++++++++++++
MeetIdaaa.swf = {};
MeetIdaaa.swf.load = function(url, divId, appWidth, appHeight)
{
   MeetIdaaa.console.log("MeetIdaaa.swf.load() args=",arguments);


   if (!url) {
       var url = MeetIdaaa.getEnv().flash.swfObject.url;
   }
   if (!divId) {
       var divId = MeetIdaaa.getEnv().flash.swfObject.divId;
   }
   if (!appWidth) {
       var appWidth = MeetIdaaa.getEnv().flash.swfObject.width;
   }

   if (!appHeight) {
       var appHeight = MeetIdaaa.getEnv().flash.swfObject.height;
   }

   var flashVars = MeetIdaaa.getEnv().flash.swfObject.flashVars;
   var embedParams = MeetIdaaa.getEnv().flash.swfObject.embedParams;

   MeetIdaaa.console.log("MeetIdaaa.loadSWF flashVars="+flashVars,flashVars);


   var flashVarsEncoded = {
       config: encodeURIComponent(JSON.stringify(flashVars))
   }


   // debug

   MeetIdaaa.console.log("embed swf ...", {
       url : url,
       divId: divId,
       width: appWidth,
       height: appHeight,
       embedParams: embedParams,
       flashVars: flashVarsEncoded
   });

   // embed it now

   swfobject.embedSWF(
       url,
       ""+divId,
       ""+appWidth,
       ""+appHeight,
       "9.0.0.0",
       null, // "expressInstall.swf"
       // flashVars //
       flashVarsEncoded,
       // embed params
       embedParams,
       // attributes
       {
       }
   );
}

// +++++++++++ MeetIdaaa.html ++++++++++++++++++++++++++++++
MeetIdaaa.html = {

        hideDivById : function(nodeId) {

            MeetIdaaa.console.log("MeetIdaaa.html.hideDivById() nodeId="+nodeId);

            var node = document.getElementById(nodeId);
            var oldStyle = node.style.display;
            if (node.style.display!="none") {
                node.style.display = "none";
            }
            return oldStyle;
        },
        showDivById : function(nodeId, displayType) {

            MeetIdaaa.console.log("MeetIdaaa.html.showDivById() args=",arguments);
        
            var node = document.getElementById(nodeId);
            var oldStyle = node.style.display;


            var defaultDisplayType = "inline";

            if ((!oldStyle)||(oldStyle=="none")) {
                if (!displayType) {
                    var displayType = defaultDisplayType;
                }
                MeetIdaaa.console.log(" try to set displayType to "+displayType);
                node.style.display=displayType;
                MeetIdaaa.console.log("MeetIdaaa.html.showDivById() node.style.display="+node.style.display);
                return oldStyle;
            }

            if ((displayType) && (oldStyle!=displayType)) {
                if (!displayType) {
                    var displayType = defaultDisplayType;
                }
                MeetIdaaa.console.log(" try to set displayType to "+displayType);
                node.style.display=displayType;
                MeetIdaaa.console.log("MeetIdaaa.html.showDivById() node.style.display="+node.style.display);
                return oldStyle;
            }

            MeetIdaaa.console.log(" .... nothing to change");
            return oldStyle;

        }
}

// +++++++++++++ MeetIdaaa.rpc ++++++++++++++++++++++++++++++
MeetIdaaa.rpc = {};

MeetIdaaa.callRPC = function (method, params, responseHandler, scope)
{
    return MeetIdaaa.rpc.invoke(method, params, responseHandler, scope);
}
<?php if(Bootstrap::getRegistry()->isDebugMode()):?>
MeetIdaaa.rpc.describeApi = function (params, responseHandler, scope)
{
   MeetIdaaa.console.log("MeetIdaaa.rpc.describeApi(params) ",params);

   var ns = MeetIdaaa.env.rpc.js.serviceNamespace;
   if (ns) {
       var _method = ns+"."+method;
       var method = _method;
   }

    if (!params) {
        var params =[];
    }
   var command = {
    method: "describeApi",
    params: params
   };

   MeetIdaaa.console.log("MeetIdaaa.rpc.describeApi() command=",command);

   var url = MeetIdaaa.env.rpc.js.gatewayUrl+'?v0&command='+JSON.stringify(command);

   $.ajax({
       url: url,
       processData: false,

       type:"GET",
       success: function(responseText) {

           var responseData = responseText;

           // call custom response handler
           if (responseHandler) {
               if (!scope) {
                   scope = null;
               }
               responseHandler.apply(scope, [responseData]);
           }
       }
   });
}
<?php endif;?>
MeetIdaaa.rpc.invoke = function (method, params, responseHandler, scope)
{

   MeetIdaaa.console.log("MeetIdaaa.rpc.invoke args=",arguments);
   var ns = MeetIdaaa.env.rpc.js.serviceNamespace;
   if (ns) {
       var _method = ns+"."+method;
       var method = _method;
   }
   var url = MeetIdaaa.env.rpc.js.gatewayUrl;
   var baseParams = MeetIdaaa.env.rpc.js.baseParams;


   if (!params) {
       var params = [];
   }
   var rpc = {
       "method":method,
       "params":params
   };
   for (var key in baseParams) {
       rpc[key] = baseParams[key];
   }




   // do the direct call
   var data = JSON.stringify(rpc);

   MeetIdaaa.console.log("data =",data);     


   $.ajax({
               url: url,
               processData: false,
               data: data,
               type:"POST",
               success: function(responseText) {

                   var responseData = null;
                   if (typeof(responseText)=="object") {
                        // jquery has decoded the response
                        var responseData = responseText;
                   } else {
                        // decode the response
                        try {
                            var responseData = JSON.parse(responseText);
                        } catch(error) {
                            error.responseText = responseText;
                            var responseData = {
                                error:error
                            }
                        }
                   }

                   if (responseData.error) {
                       responseData.result = null;
                   }
                   MeetIdaaa.console.log("rpc responseData="+responseData, responseData);

                   // global errors?
                   if (responseData.error) {
                       var stopErrorDelegation = false;
                       var stopErrorDelegation = MeetIdaaa.rpc.onError(responseData.error, method, params, responseHandler, scope);
                       if (stopErrorDelegation == true) {
                            return;
                       }
                   }

                   // response handler
                   if (responseHandler) {
                       if (!scope) {
                           scope = null;
                       }
                       responseHandler.apply(scope, [responseData]);
                   }

               }
           });
}



// ++++++++++++++++++++++++++++ rpc error handler +++++++++++++++++++++


MeetIdaaa.rpc.onError = function(error, method, params, responseHandler, scope)
{

    // YOU MAY WANT TO OVERRIDE THIS ONE IN LOCAL VIEWS
    var stopErrorDelegation = false;



    switch(error.message) {

        case "LOGIN": {
           MeetIdaaa.social.login();
           var stopErrorDelegation = true; // do not invoke local error handler
           break;
        }

        

        default: {


            var userMessage = "An error occurred!";
            if (
                    (typeof(error.userMessage)=="string")
                    && (error.userMessage.length>0)
                ) {
                    var userMessage = error.userMessage;
                }


            alert(userMessage);

            var stopErrorDelegation=true;
            return stopErrorDelegation;

            break;
        }
    }

    MeetIdaaa.console.log("MeetIdaaa.onRpcError() stopErrorDelegation="+stopErrorDelegation+" args=",arguments);

    return (stopErrorDelegation == true);
}




