jQuery(document).ready(function() {
  // Set or update Cookie if current page has a campaign number
  if (typeof jQuery.cookie == 'function') {
    
    var unique = function(array) {
      return $.grep(array, function(el, index) {
        return index === $.inArray(el, array);
      });
    };
    
    if (window.formrelay_salesforce_campaign_number !== undefined && window.formrelay_salesforce_campaign_number !== '') {
      var sfCookie = jQuery.cookie('sfCampaignNumber');
      if(sfCookie == null) {
        sfCookie = window.formrelay_salesforce_campaign_number;
      } else {
        var cookieValues = sfCookie.split(',');
        cookieValues.push(window.formrelay_salesforce_campaign_number);
        sfCookie = unique(cookieValues);
      }
      var params = { path: '/' };
      if (window.formrelay_salesforce_campaign_number_cookie_expires != '') {
        params.expires = window.formrelay_salesforce_campaign_number_cookie_expires;
      }
      jQuery.cookie('sfCampaignNumber', sfCookie, params);
    }
  }
});