jQuery(document).ready(function() {
  // Set or update Cookie if current page has a campaign number
  if (typeof jQuery.cookie == 'function') {
    
    var unique = function(array) {
      return $.grep(array, function(el, index) {
        return index === $.inArray(el, array);
      });
    };
    
    if (formrelay_salesforce_campaign_number !== undefined && formrelay_salesforce_campaign_number !== '') {
      var sfCookie = jQuery.cookie('sfCampaignNumber');
      if(sfCookie == null) {
        sfCookie = formrelay_salesforce_campaign_number;
      } else {
        var cookieValues = sfCookie.split(',');
        cookieValues.push(formrelay_salesforce_campaign_number);
        sfCookie = unique(cookieValues);
      }
      jQuery.cookie('sfCampaignNumber', sfCookie, { path: '/' });
    }
  }
});