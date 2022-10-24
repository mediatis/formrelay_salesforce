(function(w, d) {
  var COOKIE_NAME = 'sfCampaignNumber';

  function getCookie(name) {
    return d.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || null;
  }

  function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    d.cookie = name + "=" + (value || "")  + expires + "; path=/";
  }

  function updateCampaignNumber(sfCampaignNumber, cookieExpires) {
    var sfCookie = getCookie(COOKIE_NAME);
    var cookieValues = sfCookie ? sfCookie.split(',') : [];
      if (cookieValues.indexOf(sfCampaignNumber) === -1) {
        cookieValues.push(sfCampaignNumber);
        sfCookie = cookieValues.join(',');
        setCookie(COOKIE_NAME, sfCookie, cookieExpires);
      }
  }

  d.addEventListener('DOMContentLoaded', function() {
    var formrelaySalesforce = w.formrelaySalesforce || {};
    var campaignNumber = formrelaySalesforce.campaignNumber || '';
    var cookieExpires = formrelaySalesforce.cookieExpires || '';
    if (campaignNumber) {
      updateCampaignNumber(campaignNumber, cookieExpires);
    }
  });
})(window, document);
