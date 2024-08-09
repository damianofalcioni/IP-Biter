<?php
/*
    IP-Biter: The Hacker-friendly Tracking Framework
    Copyright (C) 2017-2023  Damiano Falcioni (damiano.falcioni@gmail.com)
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.
    
    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>. 
    
    -------------------------------------------------------------------------
    
README.md

# IP-Biter - Framework
#### The Hacker-friendly Tracking Framework
IP-Biter is an open source, easy to deploy, tracking framework that generate high configurable and unique tracking images and links to embed in e-mails, 
sites or chat systems and visualize, in a hacker-friendly dashboard, high detailed reports of the tracked users who visualize the image or open the links.

![](https://user-images.githubusercontent.com/8982949/33372623-f6abdc46-d4fe-11e7-921c-536300d02237.jpg)

## Features
- Very high configurable tracking image generation
- Tracking links generation
- Tracking hidden and not recognizable from the target point of view
- Integrated Dashboard
- Integrated Overview Dashboard (Admin only)
- Self-tracking prevention
- Possibility to stop and start the tracking at any time
- Possibility to hide the Dashboard and protect its access with a password
- Live tracking reports from the Dashboard
- Tracking reports live delivered to a configurable mail address
- Different IP analysis services
- User-Agent analysis service
- Integrate URL shortening service
- AllInOne PHP file
- No need for a Database
- Open Source

...and many many more!

Give it a try!

![](https://user-images.githubusercontent.com/8982949/33380631-09b9720e-d51c-11e7-9da1-b6886569e399.png)

## Getting Started
#### Deploy IP-Biter
0) Copy ipb.php in your PHP server and optionally create a .htaccess file as described in the next security notes
    - Some configurable parameters are available in the firsts uncommented PHP lines of the ipb.php file, identified by the comment "START CONFIGURATION SECTION"
#### Access the Dashboard
1) Access the dashboard through ipb.php?op=$dashboardPage (replacing $dashboardPage with its effective value)
    - $dashboardPage is the PHP variable defined in the "START CONFIGURATION SECTION" of the ipb.php file. The default value is "dashboard" so the default URL is `ipb.php?op=dashboard`
    - If the PHP variable $dashboardPage is empty you can access the dashboard through the URL `ipb.php`
    - If the PHP variable $dashboardPageSecret is not empty then a login page will appear, asking for the $dashboardPageSecret value
#### Create a new configuration
2) When the dashboard is opened without parameters, a new configuration is created
    - Another empty new configuration can be generate clicking the "New" button
3) Configure the tracking image and the advanced setting if needed
    - It is possible to left the original image url empty. In this case an empty image will be used.
4) Add tracking links if needed
    - It is possible to left the original link empty. In this case the link will generate a 404 page.
5) **Save the configuration**
6) Distribute the generated image or the links to start the tracking
    - You can click the copy button and paste in a html rich email editor like gmail
    - NOTE: If you try to open the generated image or links but have in the same browser the dashboard page opened and loaded, your request will not be tracked (self-tracking prevention feature)
    
#### Load an existing configuration
7) When the dashboard is opened with the parameter "uuid", the associated configuration is loaded
    - Another configuration can be loaded pasting the "Track UUID" in the dashboard relative field and clicking the "Load" button
8) The reports will be automatically visualized in the "Tracking Reports" section of the dashboard

## Admin Overview Page
1) Access the Admin page through ipb.php?op=$adminPage (replacing $adminPage with its effective value)
    - $adminPage is the PHP variable defined in the "START CONFIGURATION SECTION" of the ipb.php file. The default value is "admin" so the default URL is `ipb.php?op=admin`
    - If the PHP variable $adminPage is empty the admin page will be not available
    - If the PHP variable $adminPageSecret is not empty then a login page will appear, asking for the $adminPageSecret value
2) All the defined configuration will be visualized in a table.

## Security Notes
- Change the folders name and the dashboard page in the configuration section in order to improve the security
- Add the following lines to the .htaccess file in order to deny the access to the "configs" and "reports" folders:
```
DirectoryIndex ipb.php
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(configs/|reports/|error.log) - [F]
</IfModule>
```

## Live DEMO
<!-- 
Hi and welcome to a tracking link live demonstration. 
The one below is an autogenerated link that redirect to http://ipbiter.rf.gd/?op=dashboard (the demo page) and in the meanwhile, will track you :P
From this link you are not able to access the relative dashboard. 
Did not trust me?
Try to hack it as a challenge and report me your success; you will be rewarded with a coffee <3
-->
Have a look at the [DEMO](https://damianofalcioni.alwaysdata.net/ipb.php?op=l&tid=4a33afe3-2a49-455f-b1a1-19e28aa12faf&lid=f2d41e3b-da57-4efb-8490-e0678d5090d2)

## Support Me <3
<!--
Hi and welcome again to a tracking image live demonstration. 
The one below is an autogenerated link that show the image at https://user-images.githubusercontent.com/8982949/33011169-6da4af5e-cddd-11e7-94e5-a52d776b94ba.png.
The link will track you as soon as the image is loaded in the browser :)
From this link you are not able to access the relative dashboard. 
Did not trust me?
Try to hack it as a challenge and report me your success; you will be rewarded with another coffee <3
-->
[![Buy me a coffee](https://damianofalcioni.alwaysdata.net/ipb.php?op=i&tid=4a33afe3-2a49-455f-b1a1-19e28aa12faf)](https://www.paypal.me/damianofalcioni/0.99)

*/

/*START CONFIGURATION SECTION*/
$dashboardPage = 'dashboard';
$dashboardPageSecret = '';
$adminPage = 'admin';
$adminPageSecret = '';
$configFolder = 'configs';
$reportFolder = 'reports';
$errorLogFile = 'error.log';
$darkTheme = true;
$debugMode = false;
$anonymRedirectService = 'https://url.rw/?'; //Leave it empty in order to not use a referer protection service
/*END CONFIGURATION SECTION*/

error_reporting($debugMode?-1:0);
if(function_exists('ini_set'))
    ini_set("display_errors", $debugMode?1:0);

$logError = function($message) use ($errorLogFile){
    file_put_contents(__DIR__.'/'.$errorLogFile, date('d/m/Y H:i:s', time()).' '.$_SERVER['REMOTE_ADDR'].' '.$message."\r\n", FILE_APPEND);
};

function shutdownHandler($logError) {
    $error = error_get_last();
    if ($error!=null)
        $logError("ERROR on line ".$error['line'].": ".$error['message']);
}
register_shutdown_function('shutdownHandler', $logError);

if(!function_exists('getallheaders')){
    function getallheaders() {
        $retval = array();
        foreach($_SERVER as $key => $val){
            $keySplit = explode('_' , $key);
            if(array_shift($keySplit) == 'HTTP'){
                array_walk($keySplit, function(&$singleKey){
                    $singleKey = ucfirst(strtolower($singleKey));
                });
                $retval[join('-', $keySplit)] = $val;
            }
        } 
        return $retval; 
    }
}

if(
    ((!isset($_REQUEST['op']) && $dashboardPage == '') || (isset($_REQUEST['op']) && $_REQUEST['op'] == $dashboardPage)) &&
    ((!isset($_REQUEST['secret']) && $dashboardPageSecret == '') || (isset($_REQUEST['secret']) && $_REQUEST['secret'] == $dashboardPageSecret))
){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html class="no-js" lang="en">
<head>
    <title>IP-Biter Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <link rel="icon" href="data:image/png;base64,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">
    <link rel="stylesheet" type="text/css" href="<?php echo $darkTheme==false?'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css':'https://bootswatch.com/3/slate/bootstrap.min.css';?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
var Dashboard = {
  documentTitle : 'IP-Biter Dashboard',
  _dashboardSecret : '<?php echo isset($_REQUEST['secret'])?$_REQUEST['secret']:'';?>',
  _anonymRedirectService : '<?php echo $anonymRedirectService;?>',
  _imageCustomHeaderIds : {},
  _trackingLinksIds : {},
  _trackTimestamp : 0,
  _backgroudTrackListUpdateId : 0,
  _backgroudTrackListUpdateIntervallInSeconds : 10,
  _isFocused : true,
  
  trackingUUID : '',

  services : {
      callUrlShortnerService : function(url, successCallback, failureCallback){
          Utils.callService('shortening', 'url='+encodeURIComponent(url), null, function(data){
              successCallback(data.shortenedUrl);
          }, failureCallback);
      },
      callConfigSaverService : function(configJson, successCallback, failureCallback){
          Utils.callService('save', null, configJson, successCallback, failureCallback);
      },
      callLoadConfigService : function(uuid, successCallback, failureCallback){
          Utils.callService('loadConfig', 'id='+uuid, null, function(data){
              successCallback(data.config);
          }, failureCallback);
      },
      callLoadTrackingReportService : function(uuid, successCallback, failureCallback){
          Utils.callService('loadTrack', 'id='+uuid, null, function(data){
              successCallback(data.track);
          }, failureCallback);
      },
      callConfigDeleteService : function(uuid, successCallback, failureCallback){
          Utils.callService('deleteConfig', 'id='+uuid, null, successCallback, failureCallback);
      },
      callTrackDeleteService : function(uuid, successCallback, failureCallback){
          Utils.callService('deleteTrack', 'id='+uuid, null, successCallback, failureCallback);
      },
      callPingTrackService : function(uuid, time, successCallback, failureCallback){
          Utils.callService('ping', 'id='+uuid+'&time='+time+'&rnd='+Utils.generateUUID(), null, function(data){
              successCallback(data.valid);
          }, failureCallback);
      },
      callWhoisService : function(ip, successCallback, failureCallback){
          Utils.callService('ipwhois', 'ip='+ip, null, function(data){
              successCallback(data.whoisResults);
          }, failureCallback);
      }
  },
  
  loadUUID : function(){
      var uuid = $('#trackUUIDTxt').val();
      Dashboard.cleanAll();
      $('#trackUUIDTxt').val(uuid);
      $('#configurationDiv').collapse('hide');
      $('#reportsDiv').collapse('show');
      
      Dashboard.services.callLoadConfigService(uuid, function(configJson){
          Dashboard.setCookie();
          Dashboard.trackingUUID = configJson.trackUUID;
          $('#trackingEnabledChk').prop('checked', configJson.trackingEnabled);
          $('#mailIdTxt').val(configJson.mailId);
          $('#notificationMailTxt').val(configJson.notificationAddress);
          $('#trackingImageOriginalUrlTxt').val(configJson.trackingImage).trigger('change');
          $('#trackingImageHTTPStatusTxt').val(configJson.trackingImageStatusCode);
          configJson.trackingImageCustomHeaderList.forEach(function(item){
              Dashboard.addCustomHTTPHeader(item);
          });
          $('#trackingImgUrlTxt').val(configJson.trackingImageGeneratedUrl);
          $('#trackingImgShortUrlTxt').val(configJson.trackingImageShortUrl);
          for(var linkId in configJson.trackingLinks)
              Dashboard.addTrackingLink(linkId, configJson.trackingLinks[linkId].original, configJson.trackingLinks[linkId].generated, configJson.trackingLinks[linkId].shortened);
          
          Dashboard.loadTrackingReports();
      }, function(error){
          Utils.showError(error, $('#trackUUIDMsgs'));
      });
  },
  
  newUUID : function(){
      Dashboard.cleanAll();
      Dashboard.addDefaultHTTPHeader();
      Dashboard.trackingUUID = Utils.generateUUID();
      
      $('#trackUUIDTxt').val(Utils.generateUUID());
      
      $('#configurationDiv').collapse('show');
      $('#reportsDiv').collapse('hide');
      
      var imageLink = Dashboard.generateTrackingImageUrl();
      $('#trackingImgUrlTxt').val(imageLink);
      
      Dashboard.services.callUrlShortnerService(imageLink, function(shortUrl){
          $('#trackingImgShortUrlTxt').val(shortUrl);
      }, function(error){
          $('#trackingImgShortUrlTxt').val('');
          Utils.showError(error, $('#trackingImageConfigDiv'));
      });
  },
  
  saveConfiguration : function(){
      var configJson = Dashboard.generateConfigJson();
      Dashboard.services.callConfigSaverService(configJson, function(successData){
          Dashboard.setCookie();
          Utils.showSuccess('Configuration Saved', $('#saveConfigMsgs'));
          Dashboard.loadTrackingReports();
          if(Utils.getURLParameter('uuid') != configJson.uuid)
              setTimeout(function(){
                  $('<form method="post" action="'+Utils.getCurrentPath()+'?uuid='+configJson.uuid+(Utils.getURLParameter('op')!=''?'&op='+Utils.getURLParameter('op'):'')+'"><input name="secret" type="hidden" value="'+Dashboard._dashboardSecret+'"></form>').appendTo($('body')).submit().remove();
              }, 1000);
      },function(error){
          Utils.showError(error, $('#saveConfigMsgs'));
      });
  },

  deleteConfiguration : function(){
      var uuid = $('#trackUUIDTxt').val();
      Dashboard.services.callConfigDeleteService(uuid, function(successData){
          Utils.showSuccess('Configuration Deleted', $('#trackUUIDMsgs'));
          Dashboard.newUUID();
      },function(error){
          Utils.showError(error, $('#trackUUIDMsgs'));
      });
  },

  deleteTrackingReports : function(){
      var uuid = $('#trackUUIDTxt').val();
      Dashboard.services.callTrackDeleteService(uuid, function(successData){
          Utils.showSuccess('Tracks Deleted', $('#trackReportMsgs'));
          Dashboard.loadTrackingReports();
      },function(error){
          Utils.showError(error, $('#trackReportMsgs'));
      });
  },
  
  loadTrackingReports : function(update){
      Dashboard.services.callLoadTrackingReportService($('#trackUUIDTxt').val(), function(trackingReportJson){
          Dashboard._trackTimestamp = trackingReportJson.time;
          
          if(!update)
              $('#reportsDiv').empty();
          
          var _generateAnalyzeIPButtons = function(ip){
              var ipAnalyzeServiceList = [{
                  name : 'shodan.io',
                  url : 'https://www.shodan.io/search?query='+ip
              }, {
                  name : 'centralops.net',
                  url : 'https://centralops.net/co/DomainDossier.aspx?addr='+ip+'&dom_whois=true&dom_dns=true&traceroute=true&net_whois=true&svc_scan=true'
              }, {
                  name : 'udger.com',
                  url : 'https://udger.com/resources/online-parser?action=analyze&Fip='+ip
              }, {
                  name : 'ip-tracker.org',
                  url : 'http://www.ip-tracker.org/locator/ip-lookup.php?ip='+ip
              }, {
                  name : 'infobyip.com',
                  url : 'https://www.infobyip.com/ip-'+ip+'.html'
              }, {
                  name : 'infobyip.com whois',
                  url : 'https://www.infobyip.com/ipwhois-'+ip+'.html'
              }, {
                  name : 'ipinfo.io', //json https://ipinfo.io/8.8.8.8/json
                  url : 'https://ipinfo.io/'+ip
              }, {
                  name : 'ipapi.co', //json https://ipapi.co/8.8.8.8/json/
                  url : 'https://ipapi.co/'+ip
              }, {
                  name : 'whois.com',
                  url : 'https://www.whois.com/whois/'+ip
              }, {
                  name : 'ripe.net statistics',
                  url : 'https://stat.ripe.net/'+ip
              }];
              //http://freegeoip.net/json/83.65.190.82
              //https://stackoverflow.com/questions/17290256/get-google-map-link-with-latitude-longitude
              //https://apps.db.ripe.net/search/query.html?searchtext=83.65.190.82&bflag=false&source=RIPE#resultsAnchor#resultsAnchor
              //https://whois.arin.net/rest/nets;q=8.8.8.8?showDetails=true&showARIN=false&showNonArinTopLevelNet=false&ext=netref2
              
              var list = '';
              ipAnalyzeServiceList.forEach(function(ipAnalyzeService){
                  list += '<li class="link"><a href="'+Dashboard._anonymRedirectService+ipAnalyzeService.url+'" target="_blank">'+ipAnalyzeService.name+'</a></li>';
              });
              
              return '<div class="input-group-btn dropdown"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Analyze <span class="caret"></span></button><ul class="dropdown-menu">'+list+'</ul></div>';
          };
          
          var _generateAnalyzeAgentButton = function(userAgent){
              var link = 'https://udger.com/resources/online-parser?action=analyzev&Fuas='+encodeURIComponent(userAgent);
              return '<a class="btn btn-default" role="button" href="'+Dashboard._anonymRedirectService+link+'" target="_blank">Analyze</a>';
          };
          
          var newTracksCounter = 0;
          trackingReportJson.trackList.forEach(function(track, trackIndex){
              if(trackIndex < $('#reportsDiv').children().length)
                  return;
              newTracksCounter++;
              
              if(!Dashboard._isFocused && newTracksCounter!=0)
                  document.title = '('+newTracksCounter+') '+Dashboard.documentTitle;
              
              var domId = Utils.generateUUID();
              $('#reportsDiv').prepend(
                  $('<div class="list-group-item">').append(
                      $('<div class="row link" id="'+domId+'_overview_div" title="Click for details">').append(
                          $('<div class="col-lg-2">').append(
                              '<h4><span class="label label-default">'+track.time+'</span></h4>'
                          )
                      ).append(
                          $('<div class="col-lg-4">').append(
                              $('<div class="input-group">').append(
                                  '<span class="input-group-addon">Remote IP: </span>'
                              ).append(
                                  $('<input type="text" class="form-control" value="'+track.ip+'" readonly>').click(function(e){
                                      $(this).select();
                                      document.execCommand("copy");
                                  })
                              ).append(
                                  _generateAnalyzeIPButtons(track.ip)
                              )
                          )
                      ).append(
                          $('<div class="col-lg-4">').append(
                                  $('<div class="input-group">').append(
                                      '<span class="input-group-addon">Owner: </span>'
                                  ).append(
                                      $('<input type="text" id="'+domId+'_ip_owner_txt" class="form-control" readonly>').click(function(e){
                                          $(this).select();
                                          document.execCommand("copy");
                                      })
                                  )
                              )
                          )
                      .click(function(e){
                          if (e.target.tagName != 'BUTTON' && e.target.tagName != 'A' && e.target.tagName != 'INPUT'){
                              $('#'+domId+'_headers_div').toggle();
                              $('#'+domId+'_headers_div').find('.headerTxt').each(function () {
                                  $(this).css("height", $(this).prop("scrollHeight")+"px");
                                  $(this).css("width", $(this).prop("scrollWidth")+"px"); 
                              });
                              $('#'+domId+'_headers_div').find('.valueTxt').each(function () {
                                  $(this).css("height", $(this).prop("scrollHeight")+"px");
                              });
                          }
                      })
                  ).append(
                      $('<div class="row" id="'+domId+'_headers_div" style="display:none;">').append(
                          $('<div class="col-lg-12">').append(
                              $('<table class="table table-condensed">').append(
                                  $('<tbody>').append(function(){
                                      var ret = [];
                                      ret.push('<tr><th style="vertical-align:middle; white-space:nowrap; width:1%;">Header Fields</th><th style="vertical-align:middle; white-space:nowrap; width:1%;"></th><th></th></tr>');
                                      for(var header in track.headers){
                                          var analysisButton = null;
                                          var headerLowCase = header.toLowerCase();
                                          if(headerLowCase == 'user-agent')
                                              analysisButton = _generateAnalyzeAgentButton(track.headers[header]);
                                          if(headerLowCase == 'x-forwarded-for')
                                              analysisButton = _generateAnalyzeIPButtons(track.headers[header].split(',')[0]);
                                          if(headerLowCase == 'x-real-ip')
                                              analysisButton = _generateAnalyzeIPButtons(track.headers[header]);
                                          //SECURITY FIX 15-11-2018 for XSS Vulnerability reported by elpsycongroo: header visualization inside a textarea avoid content parsing so XSS can not be exploited
                                          ret.push('<tr><td style="vertical-align:middle; white-space:nowrap;"><textarea wrap="off" class="headerTxt" readonly>'+header+'</textarea></td><td style="vertical-align:middle; white-space:nowrap;">'+(analysisButton!=null?analysisButton:'')+'</td><td style="vertical-align:middle;"><textarea wrap="on" class="valueTxt" readonly>'+track.headers[header]+'</textarea></td></tr>');
                                      }
                                      return ret;
                                  }())
                              )
                          )
                      )
                  ).hide().fadeIn(500)
              );
              Dashboard.services.callWhoisService(track.ip, function(whoisResults){
                  $('#'+domId+'_ip_owner_txt').val(whoisResults.netName).popover({
                      placement : 'auto right',
                      container : 'body',
                      html : true,
                      title : 'WHOIS ' + track.ip,
                      content : function(){
                          var html = '<pre>'+whoisResults.output+'</pre>';
                          return html;
                      }(),
                      
                      trigger : 'hover click'
                  });
              }, function(error){
                  Utils.showError(error, $('#trackReportMsgs'));
              });
              
          });
      
      }, function(error){
          Utils.showError(error, $('#trackReportMsgs'));
      });
  },
  
  generateTrackingImageUrl : function(){
      return Utils.getHost()+Utils.getCurrentPath()+'?op=i&tid='+Dashboard.trackingUUID;
  },
  
  generateTrackingLinkUrl : function(linkUrl){
      return Utils.getHost()+Utils.getCurrentPath()+'?op=l&tid='+Dashboard.trackingUUID+'&lid='+linkUrl;
  },
  
  addCustomHTTPHeader : function(value){
      var domId = Utils.generateUUID();
      Dashboard._imageCustomHeaderIds[domId]=1;
      
      $('#customHTTPHeaderTable').append(
          $('<tr id="'+domId+'_tr">').append(
              $('<td>').append(
                  $('<div class="input-group">').append(
                      $('<input id="header_'+domId+'_txt" type="text" class="form-control" placeholder="Add custom header here">').val(value)
                  ).append(
                      $('<div class="input-group-addon link" style="font-size:20px;font-weight:700;">&times;</div>').click(function(){
                          $('#'+domId+'_tr').remove();
                          delete Dashboard._imageCustomHeaderIds[domId];
                      })
                  )
              )
          )
      );
  },
  
  addDefaultHTTPHeader : function(){
      Dashboard.addCustomHTTPHeader('Content-Type: image/png');
      Dashboard.addCustomHTTPHeader('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
      Dashboard.addCustomHTTPHeader('Cache-Control: post-check=0, pre-check=0');
      Dashboard.addCustomHTTPHeader('Pragma: no-cache');
      Dashboard.addCustomHTTPHeader('Expires: 0');
      Dashboard.addCustomHTTPHeader('P3P: CP="OTI DSP COR CUR IVD CONi OTPi OUR IND UNI STA PRE"');
  },
  
  addTrackingLink :function(linkUUID, originalUrl, generatedUrl, shortUrl){
      var linkId = linkUUID!=null?linkUUID:Utils.generateUUID();
      Dashboard._trackingLinksIds[linkId]=1;
      var trackingLink = generatedUrl!=null?generatedUrl:Dashboard.generateTrackingLinkUrl(linkId);
      
      $('#trackingLinksTable').append(
          $('<tr id="'+linkId+'_tr">').append(
              $('<td>').append(
                  $('<div class="input-group">').append(
                      $('<input id="link_'+linkId+'_original_txt" type="text" class="form-control" placeholder="Add the original URL here">')
                  ).append(
                      '<span class="input-group-addon">Tracking Link Generated URL:</span>'
                  ).append(
                      $('<input id="link_'+linkId+'_generated_txt" type="text" class="form-control" placeholder="Auto-generated Tracking Link" readonly>').click(function(){
                          $('#link_'+linkId+'_generated_txt').select();
                          document.execCommand("copy");
                      })
                  ).append(
                      $('<span class="input-group-addon link" title="Copy to clipboard"><span class="glyphicon glyphicon-copy"></span></span>').click(function(){
                          $('#link_'+linkId+'_generated_txt').select();
                          document.execCommand("copy");
                      })
                  ).append(
                      $('<input id="link_'+linkId+'_short_txt" type="text" class="form-control" placeholder="Auto-generated Shortened Tracking Link" readonly>').click(function(){
                          $('#link_'+linkId+'_short_txt').select();
                          document.execCommand("copy");
                      })
                  ).append(
                      $('<span class="input-group-addon link" title="Copy to clipboard"><span class="glyphicon glyphicon-copy"></span></span>').click(function(){
                          $('#link_'+linkId+'_short_txt').select();
                          document.execCommand("copy");
                      })
                  ).append(
                      $('<div class="input-group-addon link" style="font-size:20px;font-weight:700;">&times;</div>').click(function(){
                          $('#'+linkId+'_tr').remove();
                          delete Dashboard._trackingLinksIds[linkId];
                      })
                  )
              )
          )
      );
      
      $('#link_'+linkId+'_original_txt').val(originalUrl);
      $('#link_'+linkId+'_generated_txt').val(trackingLink);
      
      if(shortUrl!=null)
          $('#link_'+linkId+'_short_txt').val(shortUrl);
      else{
          Dashboard.services.callUrlShortnerService(trackingLink, function(shortUrl){
              $('#link_'+linkId+'_short_txt').val(shortUrl);
          }, function(error){
              $('#link_'+linkId+'_short_txt').val('');
              Utils.showError(error, $('#trackingLinksTable'));
          });
      }
  },
  
  generateConfigJson : function(){
      return {
          uuid : $('#trackUUIDTxt').val(),
          trackUUID : Dashboard.trackingUUID,
          trackingEnabled : $('#trackingEnabledChk').is(':checked'),
          mailId : $('#mailIdTxt').val(),
          notificationAddress : $('#notificationMailTxt').val(),
          trackingImage : $('#trackingImageOriginalUrlTxt').val(),
          trackingImageStatusCode : parseInt($('#trackingImageHTTPStatusTxt').val()),
          trackingImageCustomHeaderList : function(){
              var ret = [];
              for(var id in Dashboard._imageCustomHeaderIds)
                  ret.push($('#header_'+id+'_txt').val());
              return ret;
          }(),
          trackingImageGeneratedUrl : $('#trackingImgUrlTxt').val(),
          trackingImageShortUrl : $('#trackingImgShortUrlTxt').val(),
          trackingLinks : function(){
              var ret = {};
              for(var id in Dashboard._trackingLinksIds)
                  ret[id] = {
                      original : $('#link_'+id+'_original_txt').val(),
                      generated : $('#link_'+id+'_generated_txt').val(),
                      shortened : $('#link_'+id+'_short_txt').val()
                  };
              return ret;
          }()
      };
  },
  
  startBackgroundTrackListUpdate : function(){
      var _updateFunction = function(){
          if(Dashboard.trackingUUID == '' || Dashboard._trackTimestamp == 0){
              if(Dashboard._backgroudTrackListUpdateIntervallInSeconds != 0)
                  Dashboard._backgroudTrackListUpdateId = setTimeout(_updateFunction, Math.round(Dashboard._backgroudTrackListUpdateIntervallInSeconds)*1000);
              return;
          }
          
          Dashboard.services.callPingTrackService(Dashboard.trackingUUID, Dashboard._trackTimestamp, function(isValid){
              if(isValid===false)
                  Dashboard.loadTrackingReports(true);
              if(Dashboard._backgroudTrackListUpdateIntervallInSeconds != 0)
                  Dashboard._backgroudTrackListUpdateId = setTimeout(_updateFunction, Math.round(Dashboard._backgroudTrackListUpdateIntervallInSeconds)*1000);
          }, function(error){
              console.log(error);
              //Utils.showError(error, $('#trackReportMsgs'));
              //Dashboard.stopBackgroundTrackListUpdate();
              if(Dashboard._backgroudTrackListUpdateIntervallInSeconds != 0)
                  Dashboard._backgroudTrackListUpdateId = setTimeout(_updateFunction, Math.round(Dashboard._backgroudTrackListUpdateIntervallInSeconds)*1000);
          });
          
      };
      _updateFunction();
  },
  
  stopBackgroundTrackListUpdate : function(){
      clearTimeout(Dashboard._backgroudTrackListUpdateId);
  },
  
  cleanAll : function(){
      $('#trackUUIDTxt').val('');
      Dashboard.trackingUUID='';
      Dashboard._trackTimestamp = 0;
      $('trackingEnabledChk').prop('checked', true);
      $('#mailIdTxt').val('');
      $('#notificationMailTxt').val('');
      $('#trackingImageOriginalUrlTxt').val('').trigger('change');
      $('#trackingImageHTTPStatusTxt').val('200');
      $('#customHTTPHeaderTable').empty();
      Dashboard._imageCustomHeaderIds = {};
      $('#trackingImgUrlTxt').val('');
      $('#trackingImgShortUrlTxt').val('');
      $('#trackingLinksTable').empty();
      Dashboard._trackingLinksIds = {};
      $('#reportsDiv').empty();
  },
  
  setCookie : function(){
      document.cookie = $('#trackUUIDTxt').val()+"=1";
  },
  
  initialize : function(){
      $('#uuidLoadBtn').click(function(){
          Dashboard.loadUUID();
      });
      $('#uuidNewBtn').click(function(){
          Dashboard.newUUID();
      });
      $('#uuidDeleteBtn').click(function(){
          Dashboard.deleteConfiguration();
      });
      $('#deleteTracksBtn').click(function(){
          Dashboard.deleteTrackingReports();
      });
      $('#trackUUIDCopyBtn').click(function(){
          $('#trackUUIDTxt').select();
          document.execCommand("copy");
      });
      $('#trackingImageOriginalUrlTxt').change(function(){
          $('#trackingImageImg').attr('src', $('#trackingImageOriginalUrlTxt').val());
      });
      $('#trackingImgUrlCopyBtn').click(function(){
          Utils.copyToClipboard('<img src="'+$('#trackingImgUrlTxt').val()+'"/>');
      });
      $('#trackingImgUrlTxt').click(function(){
          $('#trackingImgUrlTxt').select();
          document.execCommand("copy");
      });
      $('#trackingImgShortUrlCopyBtn').click(function(){
          Utils.copyToClipboard('<img src="'+$('#trackingImgShortUrlTxt').val()+'"/>');
      });
      $('#trackingImgShortUrlTxt').click(function(){
          $('#trackingImgShortUrlTxt').select();
          document.execCommand("copy");
      });
      $('#addCustomHTTPHeaderBtn').click(function(){
          Dashboard.addCustomHTTPHeader();
      });
      $('#trackingImageEmoji1Btn').click(function(){
          $('#trackingImageOriginalUrlTxt').val('https://www.facebook.com/images/emoji.php/v9/z6/1/32/1f642.png').trigger('change');
      });
      $('#trackingImageEmoji2Btn').click(function(){
          $('#trackingImageOriginalUrlTxt').val('https://www.facebook.com/images/emoji.php/v9/z11/1/32/1f609.png').trigger('change');
      });
      $('#trackingImageEmoji3Btn').click(function(){
          $('#trackingImageOriginalUrlTxt').val('https://www.facebook.com/images/emoji.php/v9/z78/1/32/1f4e7.png').trigger('change');
      });
      $('#trackingImageUploadBtn').click(function(e){
          e.preventDefault();
          $("#trackingImageFileInput").trigger('click');
      });
      $('#trackingImageFileInput').change(function(e){
          Utils.readFileAsDataURL(e.target.files[0], function(content){
              $('#trackingImageOriginalUrlTxt').val(content).trigger('change');
          }); 
      });
      $('#addTrackingLinkBtn').click(function(){
          Dashboard.addTrackingLink();
      });
      $('#saveConfigBtn').click(function(){
          Dashboard.saveConfiguration();
      });
      
      var uuidParam = Utils.getURLParameter('uuid');
      if(uuidParam!=null && uuidParam!=''){
          $('#trackUUIDTxt').val(uuidParam);
          $('#uuidLoadBtn').trigger('click');
      } else {
          Dashboard.newUUID();
      }
      
      Dashboard.startBackgroundTrackListUpdate();
      
      $(window).focus(function(){
          document.title = Dashboard.documentTitle;
          Dashboard._isFocused = true;
      }).blur(function(){
          Dashboard._isFocused = false;
      });

  }
};

var Utils = {
  showError : function(error, parentDom){
      console.log(error);
      $('<div class="alert alert-danger fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error occurred:<br>'+error+'</div>')
          .fadeTo(5000, 500)
          .appendTo((parentDom!=null)?parentDom:$('#mainContainer'));
  },
  
  showSuccess : function(info, parentDom){
      console.log(info);
      $('<div class="alert alert-success fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+info+'</div>')
          .fadeTo(5000, 500)
          .slideUp(500, function(){
              $(this).remove();
          })
          .appendTo((parentDom!=null)?parentDom:$('#mainContainer'));
  },
  
  generateUUID : function() {
      var d = new Date().getTime();
      if (typeof performance !== 'undefined' && typeof performance.now === 'function')
          d += performance.now(); //use high-precision timer if available
      
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
          var r = (d + Math.random() * 16) % 16 | 0;
          d = Math.floor(d / 16);
          return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
      });
  },
  
  copyToClipboard : function(text){
      if(window.clipboardData != null){ //IE
          window.clipboardData.setData('text', text);
      } else {
          var listener = function(e) {
              var clipboard = e.clipboardData || e.originalEvent.clipboardData;
              clipboard.setData('text/plain', text);
              clipboard.setData('text/html', text);
              clipboard.setData('text', text); //Edge
              e.preventDefault();
          }
          document.addEventListener('copy', listener);
          try{
            document.execCommand('copy');
          } finally {
            document.removeEventListener('copy', listener);
          }
      }
  },
  
  getURLParameter : function(sParam){
      var sPageURL = window.location.search.substring(1);
      var sURLVariables = sPageURL.split('&');
      for (var i = 0; i < sURLVariables.length; i++) {
          var sParameterName = sURLVariables[i].split('=');
          if (sParameterName[0] == sParam)
              return sParameterName[1];
      }
      return null;
  },
  
  getHost : function(){
      var ret = ((window.location.protocol == '')?'http:':window.location.protocol) + '//' + ((window.location.hostname == '')?'127.0.0.1':window.location.hostname) + ((window.location.port != '')?':'+window.location.port:'');        
      return ret;
  },
  
  getCurrentPath : function(){
      return window.location.pathname;
  },
  
  readFileAsDataURL : function(file, onLoadFunction){
      if(!file)
          return;
      if(!(window.File && window.FileReader && window.FileList && window.Blob)){
          alert('The File APIs are not fully supported in this browser.');
          return;
      }
      var reader = new FileReader();
      reader.onload = function(e) {
          var content = e.target.result;
          onLoadFunction(content);
      };
      reader.readAsDataURL(file);
  },
  
  callService : function(op, paramsQueryString, postData, successCallback, failureCallback){
      var serviceUrl = Utils.getCurrentPath()+'?op='+op+(paramsQueryString!=null?'&'+paramsQueryString:'');
      var ajaxConfig = {
          type: 'GET',
          url: serviceUrl,
          dataType : 'json',
          async: true,
          success : function(data, status){
              if(data.status==0)
                  successCallback(data);
              else
                  failureCallback('Internal error: ' + data.error);
          },
          error : function(request, status, error) {
              failureCallback('Error contacting the service: ' + serviceUrl + ' : ' + status + ' ' + error);
          }
      };
      
      if(postData!=null){
          ajaxConfig.type = 'POST';
          ajaxConfig.processData = false;
          ajaxConfig.contentType = 'application/json';
          ajaxConfig.data = JSON.stringify(postData);
      }
      
      $.ajax(ajaxConfig);
  }
};
    </script>
    <script type="text/javascript">
$(document).ready(Dashboard.initialize);
    </script>
    <style type="text/css">
@charset "UTF-8";
.slideThree {
    width: 150px;
    height: 26px;
    background: #eaeaea;
    position: relative;
    border-radius: 50px;
}
.slideThree:before {
    content: 'ENABLED';
    color: #00b503;
    position: absolute;
    left: 10px;
    z-index: 0;
    font: 12px/26px Arial, sans-serif;
    font-weight: bold;
}
.slideThree:after {
    content: 'DISABLED';
    color: #555;
    position: absolute;
    right: 10px;
    z-index: 0;
    font: 12px/26px Arial, sans-serif;
    font-weight: bold;
    text-shadow: 1px 1px 0px rgba(255, 255, 255, 0.15);
}
.slideThree input[type=checkbox] {
    visibility: hidden;
}
.slideThree input[type=checkbox]:checked + label {
    left: 74px;
}
.slideThree label {
    width: 73px;
    height: 20px;
    cursor: pointer;
    position: absolute;
    top: 3px;
    left: 3px;
    z-index: 1;
    background: ghostwhite;
    border-radius: 50px;
    transition: all 0.4s ease;
    box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.3);
}
.link{
    cursor: pointer;
    color: #428bca;
    white-space: nowrap;
}
.link:hover{
    color: #FFFFFF;
    background-color: #428bca;
}
.popover {
    max-width: 50em !important;
}
textarea:read-only {
    overflow:hidden;
    width: 100%;
    border-width: 0px;
    resize: none;
    <?php echo $darkTheme==true?'background-color: #2e3338;':'';?>
    <?php echo $darkTheme==true?'color: #c8c8c8;':'';?>
}
    </style>
</head>
<body>
    <div id="mainContainer" class="container">
        <div class="page-header text-center">
            <h1>IP-Biter Framework </h1><h1><small>Dashboard</small></h1>
        </div>
        
        <div class="row form-group">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon">Track UUID</span>
                    <input id="trackUUIDTxt" type="text" class="form-control" placeholder="Unique track id">
                    <span id="trackUUIDCopyBtn" class="input-group-addon link" title="Copy to clipboard"><span class="glyphicon glyphicon-copy"></span></span>
                    <span class="input-group-btn">
                        <button id="uuidLoadBtn" class="btn btn-default" type="button">Load</button>
                        <button id="uuidNewBtn" class="btn btn-default" type="button">New</button>
                        <button id="uuidDeleteBtn" class="btn btn-danger" type="button">Delete</button>
                    </span>
                </div>
            </div>
        </div>
        <div id="trackUUIDMsgs"></div>
        
        <div class="panel panel-default">
            <div class="panel-heading link" data-toggle="collapse" data-target="#configurationDiv">
                <h4 class="panel-title">Tracking Configuration <span class="caret"></span></h4>
            </div>
            <div id="configurationDiv" class="panel-collapse collapse">
                <div class="panel-body">
                    
                    <div class="row form-group">
                        <div class="col-lg-2">
                            <p class="lead">Tracking status:</p>
                        </div>
                        <div class="col-lg-2">
                            <div class="slideThree">
                                <input id="trackingEnabledChk" type="checkbox" checked/>
                                <label for="trackingEnabledChk"></label>
                                 <!-- <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary active"><input id="trackingEnabledChk" type="checkbox" autocomplete="off" checked/>Enabled</label></div>-->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon">Description</span>
                                <input id="mailIdTxt" type="text" class="form-control" placeholder="E-mail Subject / Description">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon">Notification address @</span>
                                <input id="notificationMailTxt" type="text" class="form-control" placeholder="E-mail where to receive notifications on new tracks">
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4 class="panel-title">Tracking Image</h4></div>
                        <div class="panel-body" id="trackingImageConfigDiv">
                            
                            <div class="row form-group">
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tracking Image to use</span>
                                        <input id="trackingImageOriginalUrlTxt" type="text" class="form-control" placeholder="Empty image. Provide an url or select one from the menu on the right">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Image selection <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="#" id="trackingImageEmoji1Btn">Emoji :)</a></li>
                                                <li><a href="#" id="trackingImageEmoji2Btn">Emoji ;)</a></li>
                                                <li><a href="#" id="trackingImageEmoji3Btn">Emoji @Mail</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" id="trackingImageUploadBtn">Upload custom image</a><input id="trackingImageFileInput" type="file" accept="image/*" style="display: none;"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="thumbnail">
                                        <div class="text-center"><b>Image Preview</b></div>
                                        <img id="trackingImageImg" src="">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading link" data-toggle="collapse" data-target="#trackingImageAdvSettingDiv">
                                    <h4 class="panel-title">Tracking Image Advanced settings <span class="caret"></span></h4>
                                </div>
                                <div id="trackingImageAdvSettingDiv" class="panel-collapse collapse">
                                    <div class="panel-body">
                    
                                        <div class="row form-group">
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Image HTTP response status code</span>
                                                    <input id="trackingImageHTTPStatusTxt" type="number" class="form-control" value="200">
                                                </div>
                                            </div>
                                        </div>
            
                                        <div class="row form-group">
                                            <div class="col-lg-2">
                                                <button id="addCustomHTTPHeaderBtn" class="btn btn-default" type="button">Add custom HTTP Header</button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-lg-12">
                                                <table class="table table-condensed table-hover">
                                                    <tbody id="customHTTPHeaderTable"></tbody>
                                                </table>
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tracking Image Generated URL:</span>
                                        <input id="trackingImgUrlTxt" type="text" class="form-control" placeholder="Auto-generated Image Tracking URL" readonly>
                                        <span id="trackingImgUrlCopyBtn" class="input-group-addon link" title="Copy to clipboard as HTML IMG Tag"><span class="glyphicon glyphicon-copy"></span></span>
                                        <input id="trackingImgShortUrlTxt" type="text" class="form-control" placeholder="Auto-generated Image Tracking Shortened URL" readonly>
                                        <span id="trackingImgShortUrlCopyBtn" class="input-group-addon link" title="Copy to clipboard as HTML IMG Tag"><span class="glyphicon glyphicon-copy"></span></span>
                                    </div>
                                </div>
                            </div>
            
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4 class="panel-title">Tracking Links</h4></div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-lg-2">
                                    <button id="addTrackingLinkBtn" class="btn btn-default" type="button">Create Tracking Link</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-condensed table-hover">
                                        <tbody id="trackingLinksTable"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-lg-2">
                            <button id="saveConfigBtn" class="btn btn-primary btn-lg" type="button">Save</button>
                        </div>
                        <div class="col-lg-10" id="saveConfigMsgs"></div>
                    </div>

                </div>
            </div>
        </div>
         
        <div class="panel panel-default">
            <div class="panel-heading link" data-toggle="collapse" data-target="#reportsDiv">
                <h4 class="panel-title">Tracking Reports<span class="caret"></span><input id="deleteTracksBtn" class="btn btn-danger btn-xs pull-right" type="button" value="Delete All"></h4>
                
            </div>
            <div class="panel-collapse list-group" id="reportsDiv">
            </div>
            <div id="trackReportMsgs"></div>
        </div>

    </div>
</body>
</html>
<?php
    exit();
}

if(
    ((isset($_REQUEST['op']) && $_REQUEST['op'] == $adminPage)) &&
    (($adminPageSecret == '') || (isset($_REQUEST['secret']) && $_REQUEST['secret'] == $adminPageSecret))
){
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="<?php echo $darkTheme==false?'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css':'https://bootswatch.com/3/slate/bootstrap.min.css';?>">
        <style type="text/css">
textarea:read-only {
    overflow:hidden;
    width: 100%;
    border-width: 0px;
    resize: none;
    <?php echo $darkTheme==true?'background-color: #2e3338;':'';?>
    <?php echo $darkTheme==true?'color: #c8c8c8;':'';?>
}
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
var Admin = {
    services : {
      callLoadConfigListService : function(successCallback, failureCallback){
          Utils.callService('loadConfigList', null, 'secret=<?php echo $adminPageSecret?>', function(data){
              successCallback(data.configList);
          }, failureCallback);
      }
    },
    initialize : function(){
        Admin.services.callLoadConfigListService(function (configList) {
            var tableHtml = '<table class="table table-condensed"><tbody><tr><th style="vertical-align:middle; white-space:nowrap;">UUID</th><th style="vertical-align:middle; white-space:nowrap;">Tracking Status</th><th style="vertical-align:middle; white-space:nowrap;">Notification Address @</th><th style="vertical-align:middle; white-space:nowrap;">Reports</th><th style="vertical-align:middle; white-space:nowrap;">Last Updates</th><th style="vertical-align:middle; white-space:nowrap;"></th></tr>';
            configList.forEach(function(config) {
                tableHtml += '<tr><td style="vertical-align:middle; white-space:nowrap;"><textarea rows="1" wrap="off" class="headerTxt" readonly>'+config.configUUID+'</textarea></td><td style="vertical-align:middle; white-space:nowrap;"><textarea rows="1" wrap="off" class="headerTxt" readonly>'+(config.trackingEnabled?'ENABLED':'DISABLED')+'</textarea></td><td style="vertical-align:middle; white-space:nowrap;"><textarea rows="1" wrap="off" class="headerTxt" readonly>'+config.notificationAddress+'</textarea></td><td style="vertical-align:middle; white-space:nowrap;"><textarea rows="1" wrap="off" class="headerTxt" readonly>'+config.trackListCount+'</textarea></td><td style="vertical-align:middle; white-space:nowrap;"><textarea rows="1" wrap="off" class="headerTxt" readonly>'+config.time+'</textarea></td><td style="vertical-align:middle; white-space:nowrap;"><a class="btn btn-default" role="button" href="'+Utils.getHost()+Utils.getCurrentPath()+'?op=<?php echo $dashboardPage?>&secret=<?php echo $dashboardPageSecret?>&uuid='+config.configUUID+'" target="_blank">View</a></td></tr>';
            });

            $('#adminDiv').empty().append(
                tableHtml
            );
        }, function(error) {
            Utils.showError(error, $('#adminMsgs'));
        });
    }
};
var Utils = {
  showError : function(error, parentDom){
      console.log(error);
      $('<div class="alert alert-danger fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error occurred:<br>'+error+'</div>')
          .fadeTo(5000, 500)
          .appendTo((parentDom!=null)?parentDom:$('#mainContainer'));
  },
  
  showSuccess : function(info, parentDom){
      console.log(info);
      $('<div class="alert alert-success fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+info+'</div>')
          .fadeTo(5000, 500)
          .slideUp(500, function(){
              $(this).remove();
          })
          .appendTo((parentDom!=null)?parentDom:$('#mainContainer'));
  },

  getHost : function(){
      var ret = ((window.location.protocol == '')?'http:':window.location.protocol) + '//' + ((window.location.hostname == '')?'127.0.0.1':window.location.hostname) + ((window.location.port != '')?':'+window.location.port:'');        
      return ret;
  },
  
  getCurrentPath : function(){
      return window.location.pathname;
  },

  callService : function(op, paramsQueryString, postData, successCallback, failureCallback){
      var serviceUrl = Utils.getCurrentPath()+'?op='+op+(paramsQueryString!=null?'&'+paramsQueryString:'');
      var ajaxConfig = {
          type: 'GET',
          url: serviceUrl,
          dataType : 'json',
          async: true,
          success : function(data, status){
              if(data.status==0)
                  successCallback(data);
              else
                  failureCallback('Internal error: ' + data.error);
          },
          error : function(request, status, error) {
              failureCallback('Error contacting the service: ' + serviceUrl + ' : ' + status + ' ' + error);
          }
      };
      
      if(postData!=null){
          ajaxConfig.type = 'POST';
          ajaxConfig.processData = false;
          ajaxConfig.contentType = 'application/x-www-form-urlencoded';
          ajaxConfig.data = encodeURI(postData);
      }
      
      $.ajax(ajaxConfig);
  }
};
        </script>
        <script type="text/javascript">
$(document).ready(Admin.initialize);
    </script>
    </head>
    <body>
        <div id="mainContainer" class="container">
            <div class="page-header text-center">
                <h1>IP-Biter Framework </h1><h1><small>Admin Overview</small></h1>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading link" data-toggle="collapse" data-target="#adminDiv">
                    <h4 class="panel-title">All Trackings<span class="caret"></span></h4>
                </div>
                <div class="panel-collapse list-group" id="adminDiv">
                </div>
                <div id="adminMsgs"></div>
            </div>
        </div>
    </body>
</html>
<?php
    exit();
}

if(
    (
        ((!isset($_REQUEST['op']) && $dashboardPage == '') || (isset($_REQUEST['op']) && $_REQUEST['op'] == $dashboardPage)) &&
        $dashboardPageSecret != '' &&
        (!isset($_REQUEST['secret']) || (isset($_REQUEST['secret']) && $_REQUEST['secret'] != $dashboardPageSecret))
    ) || (
        ((isset($_REQUEST['op']) && $_REQUEST['op'] == $adminPage)) &&
        $adminPageSecret != '' &&
        (!isset($_REQUEST['secret']) || (isset($_REQUEST['secret']) && $_REQUEST['secret'] != $adminPageSecret))
    )
){
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="<?php echo $darkTheme==false?'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css':'https://bootswatch.com/3/slate/bootstrap.min.css';?>">
        <style type="text/css">
            form{
                position: absolute;
                top: 50%;
                left: 50%;
                -moz-transform: translateX(-50%) translateY(-50%);
                -webkit-transform: translateX(-50%) translateY(-50%);
                transform: translateX(-50%) translateY(-50%);
            }
        </style>
    </head>
    <body>
        <form class="form-inline" method="post">
            <input name="secret" class="form-control" type="password" autofocus>
            <input type="submit" class="btn btn-primary" value="OK">
        </form>
    </body>
</html>
<?php
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'shortening'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['url']) || $_REQUEST['url']=='')
            throw new Exception('url parameter required');
        $shortenedUrl = @file_get_contents('http://tinyurl.com/api-create.php?url='.$_REQUEST['url']);
        echo '{"status" : 0, "shortenedUrl" : "'.$shortenedUrl.'"}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'save'){
    header('Content-Type: application/json');
    try{
        $configFolderFull = __DIR__.'/'.$configFolder;
        $reportFolderFull = __DIR__.'/'.$reportFolder;
        $jsonString = file_get_contents('php://input');
        $json = json_decode($jsonString);
        if(!isset($json->uuid) || $json->uuid=='')
            throw new Exception('uuid not specified');
        if(!isset($json->trackUUID) || $json->trackUUID=='')
            throw new Exception('trackUUID not specified');
        if (!file_exists($configFolderFull))
            mkdir($configFolderFull, 0777, true);
        if (!file_exists($reportFolderFull))
            mkdir($reportFolderFull, 0777, true);
        if (!file_exists($reportFolderFull.'/'.$json->trackUUID.'.json')){
            file_put_contents($reportFolderFull.'/'.$json->trackUUID.'.json', '{"uuid" : "'.$json->trackUUID.'", "configUUID" : "'.$json->uuid.'", "time" : "'.time().'", "trackList" : []}');
        } else {
            $reportJson = json_decode(file_get_contents($reportFolderFull.'/'.$json->trackUUID.'.json'));
            if($reportJson->configUUID != $json->uuid){
                throw new Exception('trackUUID already in use by another configuration.');
                //SECURITY FIX: allowing the uuid update into the tracking file (next two lines) give the possibility for a tracked user to save a new configuration using its traking uuid, stealing its traking file to the original configuration
                //$reportJson->configUUID = $json->uuid;
                //file_put_contents($reportFolderFull.'/'.$json->trackUUID.'.json', json_encode($reportJson));
            }
        }
        file_put_contents($configFolderFull.'/'.$json->uuid.'.json', $jsonString);
        echo '{"status" : 0}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'loadConfigList' && (($adminPageSecret == '') || (isset($_REQUEST['secret']) && $_REQUEST['secret'] == $adminPageSecret))){
    header('Content-Type: application/json');
    try{
        $ret = array();
        $files = array_diff(scandir(__DIR__.'/'.$configFolder.'/'), array('.', '..'));
        foreach ($files as &$file) {
            $configUUID = substr($file, 0, -5);
            $config = json_decode(file_get_contents(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'));
            $trackUUID = $config->trackUUID;
            if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
                throw new Exception('Invalid track id '.$trackUUID.' for config '.$configUUID);
            $reports = json_decode(file_get_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'));
            $ret[] = (object) [
                'configUUID' => $configUUID,
                'trackUUID' => $trackUUID,
                'time' => date('Y-m-d H:i:s', $reports->time),
                'trackingEnabled' => $config->trackingEnabled,
                'notificationAddress' => $config->notificationAddress,
                'trackListCount' => count($reports->trackList),
            ];
        }
        echo '{"status" : 0, "configList" : '.json_encode($ret).'}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'loadConfig'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['id']) || $_REQUEST['id']=='')
            throw new Exception('id parameter required');
        $configUUID = $_REQUEST['id'];
        if(!file_exists(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'))
            throw new Exception('Invalid id '. $configUUID);
        $configString = file_get_contents(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json');
        echo '{"status" : 0, "config" : '.$configString.'}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'loadTrack'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['id']) || $_REQUEST['id']=='')
            throw new Exception('id parameter required');
        $configUUID = $_REQUEST['id'];
        if(!file_exists(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'))
            throw new Exception('Invalid id '. $configUUID);
        $config = json_decode(file_get_contents(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'));
        $trackUUID = $config->trackUUID;
        if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Invalid track id '. $trackUUID);
        $trackString = file_get_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json');
        echo '{"status" : 0, "track" : '.$trackString.'}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'deleteConfig'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['id']) || $_REQUEST['id']=='')
            throw new Exception('id parameter required');
        $configUUID = $_REQUEST['id'];
        if(!file_exists(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'))
            throw new Exception('Invalid id '. $configUUID);
        $config = json_decode(file_get_contents(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'));
        $trackUUID = $config->trackUUID;
        if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Invalid track id '. $trackUUID);
        if(!unlink(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'))
            throw new Exception('Impossible to delete the configuration with id '. $configUUID);
        if(!unlink(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Impossible to delete the reports for the configuration with id '. $configUUID);
        echo '{"status" : 0}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'deleteTrack'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['id']) || $_REQUEST['id']=='')
            throw new Exception('id parameter required');
        $configUUID = $_REQUEST['id'];
        if(!file_exists(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'))
            throw new Exception('Invalid id '. $configUUID);
        $config = json_decode(file_get_contents(__DIR__.'/'.$configFolder.'/'.$configUUID.'.json'));
        $trackUUID = $config->trackUUID;
        if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Invalid track id '. $trackUUID);
        file_put_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json', '{"uuid" : "'.$trackUUID.'", "configUUID" : "'.$configUUID.'", "time" : "'.time().'", "trackList" : []}');
        echo '{"status" : 0}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'ping'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['id']) || $_REQUEST['id']=='')
            throw new Exception('id parameter required');
        if(!isset($_REQUEST['time']) || $_REQUEST['time']=='')
            throw new Exception('time parameter required');
        $trackUUID = $_REQUEST['id'];
        $localTime = $_REQUEST['time'];
        if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Invalid id '. $trackUUID);
        $fileTime = filemtime(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json');
        echo '{"status" : 0, "valid" : '.($fileTime <= $localTime?'true':'false').'}';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'ipwhois'){
    header('Content-Type: application/json; charset=utf8');
    try{
        if(!isset($_REQUEST['ip']) || $_REQUEST['ip']=='')
            throw new Exception('ip parameter required');
        $ip = $_REQUEST['ip'];
        
        if(empty(session_id()))
            session_start();
        
        if(!isset($_SESSION['whois_'.$ip])) {
            /*WHOIS IP Server list
             whois.afrinic.net -> Africa - but returns data for ALL locations worldwide
             whois.lacnic.net -> Latin America and Caribbean but returns data for ALL locations worldwide
             whois.apnic.net -> Asia/Pacific only
             whois.arin.net -> North America only
             whois.ripe.net -> Europe, Middle East and Central Asia only
             */
            $_SESSION['whois_'.$ip] = '';
            session_write_close();
            
            $whoisserver = 'whois.lacnic.net';
            if(!($fp = fsockopen($whoisserver, 43, $errno, $errstr, 10)))
                throw new Exception("Error contacting the WHOIS server $whoisserver : $errstr ($errno)");
            
            fprintf($fp, "%s\r\n", $ip);
            $whoisResultString = "";
            $netNameString = "";
            $lineCount = 0;
            while(!feof($fp)){
                $line = fgets($fp);
                if(trim($line[0])!='' && $line[0]!='#' && $line[0]!='%'){
                    $whoisResultString.=$line;
                    $lineCount++;
                    if(strpos($line, ':') !== false){
                        $lineSplit = explode(':', $line);
                        if(strtolower($lineSplit[0])=='netname')
                            $netNameString = trim($lineSplit[1]);
                    } else {
                        if($lineCount == 2)
                            $netNameString = trim(explode('(NET', $line)[0]);
                    }
                }
            }
            fclose($fp);
    
            $ret = json_encode((object) array(
                'status' => 0,
                'whoisResults' => (object) array(
                    'output' => utf8_encode($whoisResultString),
                    'netName' => utf8_encode($netNameString)
                )
            ));
            
            if(json_last_error()!=0)
                throw new Exception("Error encoding the JSON response");
            session_start();
            $_SESSION['whois_'.$ip] = $ret;
            echo $ret;
        } else if($_SESSION['whois_'.$ip]=='') {
            do {
                sleep(1);
            } while($_SESSION['whois_'.$ip]=='');
            echo $_SESSION['whois_'.$ip];
        } else {
            echo $_SESSION['whois_'.$ip];
        }
        session_write_close();
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'preventTracking'){
    header('Content-Type: application/json');
    try{
        if(!isset($_REQUEST['id']) || $_REQUEST['id']=='')
            throw new Exception('id parameter required');
        $configUUID = $_REQUEST['id'];
        if(!setcookie($configUUID, "1"))
            throw new Exception('Impossible to set the cookie: '.(error_get_last()!=null?error_get_last()['message']:'No PHP error detected'));
        echo '{"status" : 0 }';
    }catch(Throwable $ex){
        echo '{"status" : -1, "error" : "'.$ex->getMessage().'"}';
        $logError($ex->getMessage());
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'i'){
    try{
        if(!isset($_REQUEST['tid']) || $_REQUEST['tid']=='')
            throw new Exception('tid parameter required');
        $trackUUID = $_REQUEST['tid'];
        if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Invalid tid '. $trackUUID);
        $track = json_decode(file_get_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'));
        if(!file_exists(__DIR__.'/'.$configFolder.'/'.$track->configUUID.'.json'))
            throw new Exception('Internal Error: impossible to find the configuration file associated to the tid '. $trackUUID);
        $config = json_decode(file_get_contents(__DIR__.'/'.$configFolder.'/'.$track->configUUID.'.json'));
        if($config->trackingEnabled === TRUE && !isset($_COOKIE[$track->configUUID])){
            array_push($track->trackList, array(
                'time'=>date('d-m-Y H:i:s', time()), 
                'ip' => $_SERVER['REMOTE_ADDR'],
                'headers' => getallheaders()
            ));
            $track->time = time();
            file_put_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json', json_encode($track));
            if(function_exists('mail') && isset($config->notificationAddress) && $config->notificationAddress!=''){
                $mailText = '<html><body><p>Your tracking image has been visualized right now by '.$_SERVER['REMOTE_ADDR'].'.</p><p>Check all the details in the <a href="'.(isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].strtok($_SERVER['REQUEST_URI'],'?').'?op='.$dashboardPage.'&uuid='.$config->uuid.'">DASHBOARD</a></p></body></html>';
                $mailSent = mail($config->notificationAddress, '[Tracking Live Report] '.$config->mailId, wordwrap($mailText, 70, "\r\n"), "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n");
                if(!$mailSent)
                    $logError("Mail not sended: ". error_get_last()!=null?error_get_last()['message']:'No PHP error detected');
                
            }
        }  
        $headerAddedList = array();
        foreach($config->trackingImageCustomHeaderList as $header){
            $key = explode(':', $header)[0];
            header($header, !in_array($key, $headerAddedList, TRUE), $config->trackingImageStatusCode);
            $headerAddedList[] = $key;
        }
        http_response_code($config->trackingImageStatusCode);
        if(isset($config->trackingImage) && $config->trackingImage!='')
            echo file_get_contents($config->trackingImage);
    }catch(Throwable $ex){
        $logError($ex->getMessage());
        http_response_code(400);
    }
    exit();
}

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'l'){
    try{
        if(!isset($_REQUEST['tid']) || $_REQUEST['tid']=='')
            throw new Exception('tid parameter required');
        if(!isset($_REQUEST['lid']) || $_REQUEST['lid']=='')
            throw new Exception('lid parameter required');
        $trackUUID = $_REQUEST['tid'];
        $linkUUID = $_REQUEST['lid'];
        if(!file_exists(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'))
            throw new Exception('Invalid tid '. $trackUUID);
        $track = json_decode(file_get_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json'));
        if(!file_exists(__DIR__.'/'.$configFolder.'/'.$track->configUUID.'.json'))
            throw new Exception('Internal Error: impossible to find the configuration file associated to the tid '. $trackUUID);
        $config = json_decode(file_get_contents(__DIR__.'/'.$configFolder.'/'.$track->configUUID.'.json'));
        if($config->trackingEnabled === TRUE && !isset($_COOKIE[$track->configUUID])){
            array_push($track->trackList, array(
                'time'=>date('d-m-Y H:i:s', time()),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'headers' => getallheaders()
            ));
            $track->time = time();
            file_put_contents(__DIR__.'/'.$reportFolder.'/'.$trackUUID.'.json', json_encode($track));
            if(function_exists('mail') && isset($config->notificationAddress) && $config->notificationAddress!=''){
                $mailText = '<html><body><p>Your tracking link has been clicked right now by '.$_SERVER['REMOTE_ADDR'].'.</p><p>Check all the details in the <a href="'.(isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].strtok($_SERVER['REQUEST_URI'],'?').'?op='.$dashboardPage.'&uuid='.$config->uuid.'">DASHBOARD</a></p></body></html>';
                $mailSent = mail($config->notificationAddress, '[Tracking Live Report] '.$config->mailId, wordwrap($mailText, 70, "\r\n"), "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n");
                if(!$mailSent)
                    $logError("Mail not sended: ". error_get_last()!=null?error_get_last()['message']:'No PHP error detected');
            }
        }
        if(!isset($config->trackingLinks->$linkUUID))
            throw new Exception('Impossible to find the link '.$linkUUID);
        $redirectToUrl = $config->trackingLinks->$linkUUID->original;
        if(isset($redirectToUrl) && $redirectToUrl!='')
            header('Location: '.$redirectToUrl);
        else
            http_response_code(404);
    }catch(Throwable $ex){
        $logError($ex->getMessage());
        http_response_code(400);
    }
    exit();
}

header('Content-Type: application/json');
echo '{"status" : -1, "error" : "op parameter not valid"}';
$logError('op parameter not valid');
exit();
?>
